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
    customFields: {copyrightYear: new Date().getFullYear()},
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
        locales: ["ar", "be", "zh-CN", "en", "fr", "de", "ja", "ko", "fa", "pt-BR", "ru", "sr-Cyrl", "es", "tr", "uk", "uz"],
        localeConfigs: {
            ar: {label: "العربية", direction: "rtl"},
            be: {label: "Беларуская"},
            "zh-CN": {label: "简体中文"},
            en: {label: "English"},
            fr: {label: "Français"},
            de: {label: "Deutsch"},
            ja: {label: "日本語"},
            ko: {label: "한국어"},
            fa: {label: "فارسی", direction: "rtl"},
            "pt-BR": {label: "Português (Brasil)"},
            ru: {label: "Русский"},
            "sr-Cyrl": {label: "Српски"},
            es: {label: "Español"},
            tr: {label: "Türkçe"},
            uk: {label: "Українська"},
            uz: {label: "O‘zbekcha"},
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
                        current: {label: "20.x", badge: false},
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
            copyright: "Copyright © 2013-{year} Laravel-Lang Team",
        },
        prism: {
            theme: prismThemes.vsLight,
            darkTheme: prismThemes.vsDark,
            additionalLanguages: ["php"],
        },
    } satisfies ThemeConfig,
};

export default config;
