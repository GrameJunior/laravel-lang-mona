import type {Options, ThemeConfig} from "@docusaurus/preset-classic";
import type {Config} from "@docusaurus/types";
import {themes as prismThemes} from "prism-react-renderer";
import documentationArtifacts from "./plugins/documentation-artifacts";

const config: Config = {
    title: "Laravel Lang",
    url: "https://laravel-lang.com",
    baseUrl: "/",
    trailingSlash: false,
    favicon: "images/favicon.svg",
    onBrokenLinks: "throw",
    onBrokenAnchors: "throw",
    onDuplicateRoutes: "throw",
    markdown: {
        format: "detect",
        hooks: {
            onBrokenMarkdownLinks: "throw",
        },
    },
    i18n: {
        defaultLocale: "en",
        locales: ["en", "ru"],
        localeConfigs: {
            en: {label: "English"},
            ru: {label: "Русский", translate: true},
        },
    },
    presets: [
        [
            "classic",
            {
                docs: {
                    routeBasePath: "/",
                    sidebarPath: "./sidebars.ts",
                    lastVersion: "current",
                    versions: {
                        current: {label: "20.x", path: "20.x"},
                        legacy: {label: "Legacy", path: "legacy", banner: "none"},
                    },
                },
                blog: false,
                theme: {customCss: "./src/css/custom.css"},
            } satisfies Options,
        ],
    ],
    plugins: [documentationArtifacts],
    themeConfig: {
        colorMode: {respectPrefersColorScheme: true},
        navbar: {
            title: "Laravel Lang",
            logo: {
                src: "images/logo.svg",
                srcDark: "images/logo_dark.svg",
            },
            items: [
                {type: "docSidebar", sidebarId: "documentation", label: "Documentation", position: "left"},
                {type: "docsVersionDropdown", position: "right"},
                {type: "localeDropdown", position: "right"},
            ],
        },
        footer: {
            links: [
                {label: "GitHub", href: "https://github.com/Laravel-Lang/mona"},
            ],
            copyright: `Copyright © 2013-${new Date().getFullYear()} Laravel-Lang Team`,
        },
        prism: {
            theme: prismThemes.vsLight,
            darkTheme: prismThemes.vsDark,
            additionalLanguages: ["php"],
        },
    } satisfies ThemeConfig,
};

export default config;
