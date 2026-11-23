import Link from "@docusaurus/Link";
import useBaseUrl from "@docusaurus/useBaseUrl";
import CodeBlock from "@theme/CodeBlock";
import Layout from "@theme/Layout";

import styles from "./index.module.css";

const description = "Install translations and localize routes and Eloquent models with Mona 20.x, the shared release line for Laravel-Lang packages.";

const guides = [
    {
        title: "Manage locales",
        description: "Add, update, and remove translations with Artisan.",
        to: "/20.x/translations",
    },
    {
        title: "Configure localization",
        description: "Set up locale aliases, localized routes, and model translations.",
        to: "/20.x/configure",
    },
    {
        title: "Explore packages",
        description: "Choose the packages your application needs.",
        to: "/20.x/packages",
    },
    {
        title: "Upgrade to Mona",
        description: "Migrate your dependencies, configuration, and translations to 20.x.",
        to: "/20.x/upgrade",
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
                                <Link className="button button--primary button--lg" to="/20.x/start">
                                    Get started with 20.x
                                </Link>
                                <Link className="button button--secondary button--lg" to="/legacy">
                                    Legacy documentation
                                </Link>
                            </div>
                            <CodeBlock className={styles.installCommand} language="bash">
                                {'composer require "laravel-lang/common:^20.0"'}
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
