import type {SidebarsConfig} from "@docusaurus/plugin-content-docs";

const sidebars: SidebarsConfig = {
    documentation: [
        "start",
        "upgrade",
        {
            type: "category",
            label: "Localize an application",
            items: ["translations", "configure", "available-locales", "locales", "routes", "models"],
        },
        {
            type: "category",
            label: "Packages and plugins",
            items: ["plugins", "plugins-community", "toolkit"],
        },
        {
            type: "category",
            label: "Project",
            items: ["release-notes", "contributing", "security-policy", "license"],
        },
    ],
};

export default sidebars;
