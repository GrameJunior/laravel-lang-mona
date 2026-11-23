import type {Options, ThemeConfig} from "@docusaurus/preset-classic";
import type {Config} from "@docusaurus/types";
import {themes as prismThemes} from "prism-react-renderer";

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
                },
                blog: false,
                theme: {customCss: "./src/css/custom.css"},
            } satisfies Options,
        ],
    ],
    themeConfig: {
        colorMode: {respectPrefersColorScheme: true},
        navbar: {
            title: "Laravel Lang",
            logo: {
                src: "images/logo.svg",
                srcDark: "images/logo_dark.svg",
            },
            items: [
                {type: "doc", docId: "basic-usage", label: "Documentation", position: "left"},
                {type: "localeDropdown", position: "right"},
            ],
        },
        footer: {
            links: [
                {label: "GitHub", href: "https://github.com/Laravel-Lang/common"},
            ],
            copyright: `Copyright © 2013-${new Date().getFullYear()} Laravel-Lang Team`,
        },
        prism: {
            theme: prismThemes.github,
            darkTheme: prismThemes.dracula,
            additionalLanguages: ["php"],
        },
    } satisfies ThemeConfig,
};

export default config;
