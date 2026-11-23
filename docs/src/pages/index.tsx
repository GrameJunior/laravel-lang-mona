import Link from "@docusaurus/Link";
import useBaseUrl from "@docusaurus/useBaseUrl";
import CodeBlock from "@theme/CodeBlock";
import Layout from "@theme/Layout";

import styles from "./index.module.css";

const description = "Install translations and localize routes and Eloquent models with Laravel-Lang.";

const guides = [
    {
        title: "Manage locales",
        description: "Add, update, and remove translations with Artisan.",
        to: "/translations",
    },
    {
        title: "Configure localization",
        description: "Set up locale aliases, localized routes, and model translations.",
        to: "/configure",
    },
    {
        title: "Translation plugins",
        description: "Install translation packs or create your own.",
        to: "/plugins",
    },
    {
        title: "Upgrade to Mona",
        description: "Migrate your dependencies, configuration, and translations.",
        to: "/upgrade",
    },
];

export default function Home() {
    const llmsUrl = useBaseUrl("/llms.txt");

    return (
        <Layout title="Documentation" description={description}>
            <main>
                <section className={styles.hero} aria-labelledby="home-title">
                    <div className={styles.heroInner}>
                        <div className={styles.heroCopy}>
                            <h1 id="home-title">Localize your Laravel application</h1>
                            <p className={styles.summary}>{description}</p>
                            <div className={styles.actions}>
                                <Link className="button button--primary button--lg" to="/start">
                                    Start in 1 minute
                                </Link>
                                <Link className="button button--secondary button--lg" to="/legacy">
                                    Legacy documentation
                                </Link>
                            </div>
                            <CodeBlock className={styles.installCommand} language="bash">
                                {"composer require laravel-lang/common\nphp artisan lang:update"}
                            </CodeBlock>
                        </div>
                        <div className={styles.codePreview}>
                            <CodeBlock language="bash" title="Install French translations">
                                {"php artisan lang:add fr"}
                            </CodeBlock>
                            <CodeBlock language="php" title="Use a translation">
                                {"__('auth.failed', locale: 'fr');"}
                            </CodeBlock>
                        </div>
                    </div>
                </section>

                <section className={styles.guides} aria-labelledby="guides-title">
                    <h2 id="guides-title">Localization guides</h2>
                    <div className={styles.guideGrid}>
                        {guides.map((guide) => (
                            <Link key={guide.to} className={styles.guide} to={guide.to}>
                                <h3>{guide.title} <span aria-hidden="true">→</span></h3>
                                <p>{guide.description}</p>
                            </Link>
                        ))}
                    </div>
                    <a className={styles.compatibility} href={llmsUrl}>Documentation for AI agents (llms.txt)</a>
                </section>
            </main>
        </Layout>
    );
}
