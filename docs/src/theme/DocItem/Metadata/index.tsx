import Head from "@docusaurus/Head";
import {useDoc} from "@docusaurus/plugin-content-docs/client";
import DocItemMetadata from "@theme-original/DocItem/Metadata";

export default function Metadata() {
    const {metadata} = useDoc();

    return (
        <>
            <DocItemMetadata />
            {metadata.version === "current" && metadata.source.endsWith(".md") && !metadata.unlisted && (
                <Head>
                    <link rel="alternate" type="text/markdown" href={`${metadata.permalink}.md`} />
                </Head>
            )}
        </>
    );
}
