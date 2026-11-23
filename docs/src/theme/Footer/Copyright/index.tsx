import useIsBrowser from "@docusaurus/useIsBrowser";
import type {Props} from "@theme/Footer/Copyright";
import OriginalCopyright from "@theme-original/Footer/Copyright";

export default function Copyright({copyright}: Props) {
    const isBrowser = useIsBrowser();

    return (
        <OriginalCopyright
            copyright={isBrowser ? copyright.replace(/-\d{4}/, `-${new Date().getFullYear()}`) : copyright}
        />
    );
}
