import assert from "node:assert/strict";
import {mkdir, readFile, writeFile} from "node:fs/promises";
import {dirname, join} from "node:path";
import type {LoadedContent} from "@docusaurus/plugin-content-docs";
import type {LoadContext, Plugin} from "@docusaurus/types";

const defaultMessages = {
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

function formatDocumentBody(body: string, canonicalUrl: URL): string {
    // Preserve fenced examples while nesting headings and rebasing links.
    const chunks = body.split(/(^ {0,3}```[^\r\n]*\r?\n[\s\S]*?^ {0,3}```[ \t]*$)/gm);

    return chunks.map((chunk, index) => {
        if (index % 2 === 1) {
            return chunk;
        }

        return chunk
            .replace(/^(#{1,5})(?=[ \t])/gm, "$1#")
            .replace(/\]\(((?:\.{1,2}\/|#)[^)\s]+)\)/g, (_, link: string) => {
                const url = new URL(link, canonicalUrl);
                if (link.startsWith(".")) {
                    url.pathname = url.pathname.replace(/\.md$/, "");
                }
                return `](${url})`;
            });
    }).join("");
}

export default function documentationArtifacts({baseUrl, siteDir, siteConfig, i18n}: LoadContext): Plugin {
    const {htmlLang, direction} = i18n.localeConfigs[i18n.currentLocale];
    const isDefaultLocale = i18n.currentLocale === i18n.defaultLocale;
    const defaultBaseUrl = i18n.localeConfigs[i18n.defaultLocale].baseUrl;

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
            headTags: [{tagName: "link", attributes: {rel: "describedby", href: `${defaultBaseUrl}llms.txt`}}],
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
            const sections = await Promise.all(pages.map(async ({title, source, permalink}) => {
                if (!source.endsWith(".md")) {
                    return `## ${title}\n\n[Read the documentation](${new URL(permalink, siteConfig.url)})`;
                }

                const pageUrl = `${permalink}.md`;
                const original = await readFile(join(siteDir, source.replace(/^@site\//, "")), "utf8");
                const body = original.replace(/^\uFEFF?---\r?\n[\s\S]*?\r?\n---\r?\n/, "").trim();
                const filename = join(outDir, pageUrl.slice(baseUrl.length));
                await mkdir(dirname(filename), {recursive: true});
                await writeFile(filename, `# ${title}\n\n${body}\n`);

                if (!isDefaultLocale) {
                    return "";
                }

                return `## ${title}\n\n${formatDocumentBody(body, new URL(permalink, siteConfig.url))}`;
            }));

            if (isDefaultLocale) {
                await writeFile(join(outDir, "llms.txt"), [
                    "# Laravel-Lang",
                    "> Laravel-Lang publishes translations and localizes routes and Eloquent models in Laravel applications. Its first-party packages share the same release version.",
                    ...sections,
                    "## Optional",
                    `- [Legacy documentation](${new URL(legacy.path, siteConfig.url)}): Guides for pre-Mona installations.`,
                    "",
                ].join("\n\n"));
            }

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
