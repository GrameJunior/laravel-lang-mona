import {type HTMLAttributes, useEffect, useState} from "react";
import Head from "@docusaurus/Head";
import useDocusaurusContext from "@docusaurus/useDocusaurusContext";

import be from "../../i18n/be/pagefind.json";
import uz from "../../i18n/uz/pagefind.json";

const searchTranslations: Partial<Record<string, Record<string, string>>> = {be, uz};
let pagefindReady: Promise<void> | undefined;

declare global {
    interface Window {
        PagefindComponents?: {
            getInstanceManager(): {
                getInstance(name: string): {
                    setTranslations(translations: Record<string, string>): void;
                };
            };
        };
    }
}

declare module "react" {
    namespace JSX {
        interface IntrinsicElements {
            "pagefind-modal-trigger": HTMLAttributes<HTMLElement>;
            "pagefind-modal": HTMLAttributes<HTMLElement>;
        }
    }
}

export default function SearchBar() {
    const {i18n: {currentLocale}} = useDocusaurusContext();
    const [ready, setReady] = useState(false);

    useEffect(() => {
        if (process.env.NODE_ENV !== "production") {
            return;
        }

        // Set translations before Pagefind creates the search dialog.
        pagefindReady ??= customElements.whenDefined("pagefind-modal").then(() => {
            const translations = searchTranslations[currentLocale];
            if (translations) {
                window.PagefindComponents?.getInstanceManager().getInstance("default").setTranslations(translations);
            }
        });
        void pagefindReady.then(() => setReady(true));
    }, [currentLocale]);

    if (process.env.NODE_ENV !== "production") {
        return null;
    }

    return (
        <>
            <Head>
                <link rel="stylesheet" href="/pagefind/pagefind-component-ui.css" />
                <script type="module" src="/pagefind/pagefind-component-ui.js" />
            </Head>
            {ready && (
                <>
                    <pagefind-modal-trigger />
                    <pagefind-modal />
                </>
            )}
        </>
    );
}
