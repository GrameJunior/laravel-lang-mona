import assert from "node:assert/strict";
import {mkdir, readFile, writeFile} from "node:fs/promises";
import {dirname, join} from "node:path";
import type {LoadedContent} from "@docusaurus/plugin-content-docs";
import type {LoadContext, Plugin} from "@docusaurus/types";

const defaultMessages = {
    releaseLine: "The first-party packages share the same release version.",
    documentation: "Documentation",
    optional: "Optional",
    legacy: "Legacy documentation",
    legacyDescription: "Guides for pre-Mona installations.",
    moved: "Documentation has moved",
    redirect: "Go to the documentation",
};

type Messages = typeof defaultMessages;

function escapeText(text: string): string {
    return text.replaceAll("&", "&amp;").replaceAll("<", "&lt;").replaceAll(">", "&gt;");
}

function redirectPage(target: string, language: string, direction: string, messages: Messages): string {
    const href = encodeURI(target);

    return `<!doctype html>
<html lang="${language}" dir="${direction}">
<meta charset="utf-8">
<title>${escapeText(messages.moved)}</title>
<link rel="canonical" href="${href}">
<meta name="robots" content="noindex">
<script>location.replace(${JSON.stringify(href)} + location.search + location.hash);</script>
<p><a href="${href}">${escapeText(messages.redirect)}</a></p>
</html>
`;
}

export default function documentationArtifacts({baseUrl, siteDir, siteConfig, i18n}: LoadContext): Plugin {
    const {htmlLang, direction} = i18n.localeConfigs[i18n.currentLocale];

    return {
        name: "documentation-artifacts",
        getTranslationFiles: () => [{
            path: "messages",
            content: Object.fromEntries(Object.entries(defaultMessages).map(([key, message]) => [key, {message}])),
        }],
        translateContent: ({translationFiles}) => Object.fromEntries(
            Object.entries(translationFiles[0].content).map(([key, {message}]) => [key, message]),
        ),
        injectHtmlTags: () => ({
            headTags: [{tagName: "link", attributes: {rel: "describedby", href: `${baseUrl}llms.txt`}}],
        }),
        async postBuild({outDir, plugins, content}) {
            const messages = {...defaultMessages, ...(content as Partial<Messages> | undefined)};
            const docs = plugins.find(({name}) => name === "docusaurus-plugin-content-docs");
            const docsContent = docs?.content as LoadedContent | undefined;
            const current = docsContent?.loadedVersions.find(({versionName}) => versionName === "current");
            const legacy = docsContent?.loadedVersions.find(({versionName}) => versionName === "legacy");
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
                `> ${messages.releaseLine}`,
                `## ${messages.documentation}`,
                links.join("\n"),
                `## ${messages.optional}`,
                `- [${messages.legacy}](${new URL(legacy.path, siteConfig.url)}): ${messages.legacyDescription}`,
                "",
            ].join("\n\n"));

            await Promise.all(legacy.docs.filter(({slug}) => slug.endsWith(".html")).map(async ({slug, permalink}) => {
                try {
                    // A root route like /license already uses license.html in the output.
                    await writeFile(join(outDir, slug), redirectPage(permalink, htmlLang, direction, messages), {flag: "wx"});
                } catch (error) {
                    if (!(error instanceof Error && "code" in error && error.code === "EEXIST")) {
                        throw error;
                    }
                }
            }));
        },
    };
}
