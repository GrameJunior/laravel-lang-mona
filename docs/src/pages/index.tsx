import Link from "@docusaurus/Link";
import CodeBlock from "@theme/CodeBlock";
import Layout from "@theme/Layout";

import styles from "./index.module.css";

const description = "Install translations and localize routes and Eloquent models in your Laravel application.";

const guides = [
    {
        title: "Manage locales",
        description: "Add, update, reset, and remove translations with Artisan.",
        to: "/manage-locales.html",
    },
    {
        title: "Configure localization",
        description: "Set up locale aliases, localized routes, and model translations.",
        to: "/configuration.html",
    },
    {
        title: "Explore packages",
        description: "Use Common to install the packages that support application localization.",
        to: "/packages-common.html",
    },
    {
        title: "Install plugins",
        description: "Add translation packs maintained by Laravel-Lang and the community.",
        to: "/plugins-installation.html",
    },
];

export default function Home() {
    return (
        <Layout title="Documentation" description={description}>
            <main>
                <section className={styles.hero} aria-labelledby="home-title">
                    <div className={styles.heroInner}>
                        <div className={styles.heroCopy}>
                            <h1 id="home-title">Localize your Laravel application</h1>
                            <p className={styles.summary}>{description}</p>
                            <div className={styles.actions}>
                                <Link className="button button--primary button--lg" to="/basic-usage.html">
                                    Get started
                                </Link>
                                <Link className="button button--secondary button--lg" to="/available-locales-list.html">
                                    Browse locales
                                </Link>
                            </div>
                            <CodeBlock className={styles.installCommand} language="bash">
                                {"composer require laravel-lang/common:^6.9"}
                            </CodeBlock>
                        </div>
                        <div className={styles.codePreview}>
                            <CodeBlock language="bash" title="Install French translations">
                                {"php artisan lang:add fr"}
                            </CodeBlock>
                            <CodeBlock language="php" title="Use a translation">
                                {"__('Remember me', locale: 'fr');\n// Se souvenir de moi"}
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
                    <Link className={styles.compatibility} to="/packages-common.html#compatibility">
                        Laravel and PHP compatibility
                    </Link>
                </section>
            </main>
        </Layout>
    );
}
