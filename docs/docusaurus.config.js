import {themes as prismThemes} from "prism-react-renderer";

const github = "https://github.com/Laravel-Lang/common";

/** @type {import('@docusaurus/types').Config} */
export default {
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
                    sidebarPath: "./sidebars.js",
                },
                blog: false,
                theme: {customCss: "./src/css/custom.css"},
            },
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
                {type: "localeDropdown", position: "right"},
            ],
        },
        footer: {
            links: [
                {
                    items: [
                        {label: "Boosty", href: "https://boosty.to/laravel-lang"},
                        {label: "GitHub", href: github},
                        {label: "Issues", href: `${github}/issues`},
                        {label: "Discussions", href: `${github}/discussions`},
                    ],
                },
            ],
        },
        prism: {
            theme: prismThemes.github,
            darkTheme: prismThemes.dracula,
            additionalLanguages: ["php"],
        },
    },
};
