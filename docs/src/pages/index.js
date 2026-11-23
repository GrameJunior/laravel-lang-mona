import Head from "@docusaurus/Head";
import Link from "@docusaurus/Link";
import useBaseUrl from "@docusaurus/useBaseUrl";

export default function Home() {
    const introduction = useBaseUrl("/introduction.html");

    return (
        <>
            <Head>
                <title>Redirecting…</title>
                <meta httpEquiv="refresh" content={`0;url=${introduction}`} />
            </Head>
            <main className="container margin-vert--lg">
                <Link to={introduction}>Getting Started</Link>
            </main>
        </>
    );
}
