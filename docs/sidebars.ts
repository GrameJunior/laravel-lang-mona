import type {SidebarsConfig} from "@docusaurus/plugin-content-docs";
import type {SidebarItemCategoryConfig, SidebarItemConfig} from "@docusaurus/plugin-content-docs/lib/sidebars/types.js";

function section(label: string, items: SidebarItemConfig[], id?: string): SidebarItemCategoryConfig {
    return {
        type: "category",
        label,
        items,
        ...(id && {link: {type: "doc", id}}),
    };
}

const sidebars: SidebarsConfig = {
    documentation: [
        section("Prologue", [
            "release-notes",
            section("Upgrade Guide", [
                "upgrade-common",
                "upgrade-publisher",
                "upgrade-json-fallback",
            ]),
            "security-policy",
            "contributions",
            {type: "doc", id: "license", label: "License"},
        ]),
        section("Getting Started", [
            "basic-usage",
            "configuration",
            "available-locales-list",
        ]),
        section("Managing Locales", [
            "usage-add-locales",
            "usage-update-locales",
            "usage-reset-locales",
            "usage-remove-locales",
        ], "manage-locales"),
        section("Packages", [
            "packages-starter-kits",
            "packages-common",
            "packages-routes",
            "packages-models",
            "packages-locales",
            "packages-lang",
            "packages-actions",
            "packages-attributes",
            "packages-http-statuses",
            "packages-native-locale-names",
            "packages-native-country-names",
            "packages-native-currency-names",
            "packages-moonshine",
            "packages-publisher",
            "packages-translator",
            "packages-locale-list",
            "packages-json-fallback",
        ]),
        section("Plugins", [
            "plugins-installation",
            "plugins-official",
            "plugins-community",
        ]),
    ],
};

export default sidebars;
