import {useEffect, useState} from "react";
import {useLocation} from "@docusaurus/router";
import Translate from "@docusaurus/Translate";
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
    const {i18n: {currentLocale, defaultLocale}} = useDocusaurusContext();
    const {hash} = useLocation();
    const currentLocaleCode = currentLocale === "uz" ? "uz_Latn" : currentLocale.replaceAll("-", "_");
    const defaultLocaleCode = isLocaleCode(currentLocaleCode) ? currentLocaleCode : "en";
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
                <strong><Translate id="localePicker.label">Locale</Translate></strong>
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
                {nativeDescription && localeCode !== currentLocaleCode && (
                    <p lang={languageTag} dir="auto">{nativeDescription}</p>
                )}
                {currentLocale === defaultLocale ? (
                    <p lang="en">{description}</p>
                ) : (
                    <p>
                        <Translate id="localePicker.install" values={{language: <bdi lang={languageTag}>{name}</bdi>}}>
                            {"Run the following command to install translations for {language}."}
                        </Translate>
                    </p>
                )}
                <CodeBlock key={localeCode} language="bash">{`php artisan lang:add ${localeCode}`}</CodeBlock>
            </div>
        </>
    );
}
