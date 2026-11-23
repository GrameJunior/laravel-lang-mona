import assert from "node:assert/strict";
import {mkdir, readFile, writeFile} from "node:fs/promises";
import {dirname, join} from "node:path";
import type {LoadedContent} from "@docusaurus/plugin-content-docs";
import type {LoadContext, Plugin} from "@docusaurus/types";

function redirectPage(target: string): string {
    const href = encodeURI(target);

    return `<!doctype html>
<html lang="en">
<meta charset="utf-8">
<title>Documentation has moved</title>
<link rel="canonical" href="${href}">
<meta name="robots" content="noindex">
<script>location.replace(${JSON.stringify(href)} + location.search + location.hash);</script>
<p><a href="${href}">Go to the documentation</a></p>
</html>
`;
}

export default function documentationArtifacts({baseUrl, siteDir, siteConfig}: LoadContext): Plugin {
    return {
        name: "documentation-artifacts",
        injectHtmlTags: () => ({
            headTags: [{tagName: "link", attributes: {rel: "describedby", href: `${baseUrl}llms.txt`}}],
        }),
        async postBuild({outDir, plugins}) {
            const docs = plugins.find(({name}) => name === "docusaurus-plugin-content-docs");
            const content = docs?.content as LoadedContent | undefined;
            const current = content?.loadedVersions.find(({versionName}) => versionName === "current");
            const legacy = content?.loadedVersions.find(({versionName}) => versionName === "legacy");
            const start = current?.docs.find(({id}) => id === "start");
            assert(current && legacy && start, "Expected the current and legacy documentation and the start page.");

            const pages = [start, ...current.docs.filter((doc) => doc !== start && !doc.unlisted)];
            const links = await Promise.all(pages.map(async ({title, description, source, permalink}) => {
                const markdown = source.endsWith(".md");
                const pageUrl = markdown ? `${permalink}.md` : permalink;

                if (markdown) {
                    const original = await readFile(join(siteDir, source.replace(/^@site\//, "")), "utf8");
                    const body = original.replace(/^\uFEFF?---\r?\n[\s\S]*?\r?\n---\r?\n/, "").trim();
                    const filename = join(outDir, pageUrl.slice(baseUrl.length));
                    await mkdir(dirname(filename), {recursive: true});
                    await writeFile(filename, `# ${title}\n\n${body}\n`);
                }

                return `- [${title}](${new URL(pageUrl, siteConfig.url)})${description ? `: ${description}` : ""}`;
            }));

            await writeFile(join(outDir, "llms.txt"), [
                "# Laravel-Lang",
                "> The first-party packages share the same release version.",
                "## Documentation",
                links.join("\n"),
                "## Optional",
                `- [Legacy documentation](${new URL(legacy.path, siteConfig.url)}): Guides for pre-Mona installations.`,
                "",
            ].join("\n\n"));

            await Promise.all(legacy.docs.filter(({slug}) => slug.endsWith(".html")).map(async ({slug, permalink}) => {
                try {
                    // A root route like /license already uses license.html in the output.
                    await writeFile(join(outDir, slug), redirectPage(permalink), {flag: "wx"});
                } catch (error) {
                    if (!(error instanceof Error && "code" in error && error.code === "EEXIST")) {
                        throw error;
                    }
                }
            }));
        },
    };
}
