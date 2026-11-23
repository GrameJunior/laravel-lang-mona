import Head from "@docusaurus/Head";

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
