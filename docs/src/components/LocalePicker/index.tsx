import {useEffect, useState} from "react";
import {useLocation} from "@docusaurus/router";
import useDocusaurusContext from "@docusaurus/useDocusaurusContext";
import CodeBlock from "@theme/CodeBlock";
import Heading from "@theme/Heading";
import locales from "./locales.json";
import styles from "./styles.module.css";

type LocaleCode = keyof typeof locales;

type Locale = {
    name: string;
    nativeDescription?: string;
    description: string;
};

const anchorPrefix = "lists-available-locales-";

function isLocaleCode(code: string): code is LocaleCode {
    return Object.hasOwn(locales, code);
}

export default function LocalePicker() {
    const {i18n: {currentLocale}} = useDocusaurusContext();
    const {hash} = useLocation();
    const defaultLocaleCode = isLocaleCode(currentLocale) ? currentLocale : "en";
    const [localeCode, setLocaleCode] = useState(defaultLocaleCode);
    const {name, nativeDescription, description}: Locale = locales[localeCode];
    const languageTag = localeCode.replaceAll("_", "-");

    useEffect(() => {
        const prefix = `#${anchorPrefix}`;
        const linkedCode = hash.startsWith(prefix) ? hash.slice(prefix.length) : "";

        setLocaleCode(isLocaleCode(linkedCode) ? linkedCode : defaultLocaleCode);
    }, [defaultLocaleCode, hash]);

    return (
        <>
            <label className={styles.field}>
                <strong>Locale</strong>
                <select
                    className={styles.select}
                    value={localeCode}
                    onChange={(event) => {
                        const code = event.currentTarget.value;

                        if (isLocaleCode(code)) {
                            setLocaleCode(code);
                        }
                    }}
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
