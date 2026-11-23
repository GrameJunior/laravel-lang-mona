import {useEffect, useState} from "react";
import {useLocation} from "@docusaurus/router";
import useDocusaurusContext from "@docusaurus/useDocusaurusContext";
import CodeBlock from "@theme/CodeBlock";
import Heading from "@theme/Heading";
import locales from "./locales.json";
import styles from "./styles.module.css";

const anchorPrefix = "lists-available-locales-";

export default function LocalePicker() {
    const {i18n: {currentLocale}} = useDocusaurusContext();
    const {hash} = useLocation();
    const [localeCode, setLocaleCode] = useState(currentLocale);
    const {name, nativeDescription, description} = locales[localeCode];
    const languageTag = localeCode.replaceAll("_", "-");

    useEffect(() => {
        const prefix = `#${anchorPrefix}`;
        const linkedCode = hash.startsWith(prefix) ? hash.slice(prefix.length) : "";

        setLocaleCode(Object.hasOwn(locales, linkedCode) ? linkedCode : currentLocale);
    }, [currentLocale, hash]);

    return (
        <>
            <label className={styles.field}>
                <strong>Locale</strong>
                <select
                    className={styles.select}
                    value={localeCode}
                    onChange={(event) => setLocaleCode(event.target.value)}
                    aria-controls="locale-installation"
                >
                    {Object.entries(locales).map(([code, locale]) => (
                        <option key={code} value={code} lang={code.replaceAll("_", "-")}>
                            {locale.name} ({code})
                        </option>
                    ))}
                </select>
            </label>

            <div id="locale-installation" aria-live="polite">
                <Heading as="h2" id={`${anchorPrefix}${localeCode}`} lang={languageTag} dir="auto">
                    {name}
                </Heading>
                {nativeDescription && <p lang={languageTag} dir="auto">{nativeDescription}</p>}
                <p lang="en">{description}</p>
                <CodeBlock key={localeCode} language="bash">{`php artisan lang:add ${localeCode}`}</CodeBlock>
            </div>
        </>
    );
}
