import type {HTMLAttributes} from "react";
import Head from "@docusaurus/Head";

declare module "react" {
    namespace JSX {
        interface IntrinsicElements {
            "pagefind-modal-trigger": HTMLAttributes<HTMLElement>;
            "pagefind-modal": HTMLAttributes<HTMLElement>;
        }
    }
}

export default function SearchBar() {
    if (process.env.NODE_ENV !== "production") {
        return null;
    }

    return (
        <>
            <Head>
                <link rel="stylesheet" href="/pagefind/pagefind-component-ui.css" />
                <script type="module" src="/pagefind/pagefind-component-ui.js" />
            </Head>
            <pagefind-modal-trigger />
            <pagefind-modal />
        </>
    );
}
