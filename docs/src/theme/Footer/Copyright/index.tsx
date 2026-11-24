import useDocusaurusContext from "@docusaurus/useDocusaurusContext";
import useIsBrowser from "@docusaurus/useIsBrowser";
import type {Props} from "@theme/Footer/Copyright";
import OriginalCopyright from "@theme-original/Footer/Copyright";

export default function Copyright({copyright}: Props) {
    const {siteConfig} = useDocusaurusContext();
    const isBrowser = useIsBrowser();
    const year = isBrowser ? new Date().getFullYear() : siteConfig.customFields?.copyrightYear;

    return <OriginalCopyright copyright={copyright.replace("{year}", String(year))} />;
}
