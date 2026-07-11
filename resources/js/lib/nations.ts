export interface NationDisplay {
    name: string;
    flagCss: string;
}

export const WORLD_CODE = 'WLD';

export const NATIONS: Record<string, NationDisplay> = {
    USA: {
        name: 'United States',
        flagCss:
            'linear-gradient(#3C3B6E,#3C3B6E) left top / 45% 55% no-repeat, linear-gradient(180deg,#B22234 0 18%,#FFFFFF 18% 36%,#B22234 36% 54%,#FFFFFF 54% 72%,#B22234 72% 88%,#FFFFFF 88%)',
    },
    IND: {
        name: 'India',
        flagCss:
            'linear-gradient(180deg,#FF9933 0 33%,#FFFFFF 33% 66%,#138808 66%)',
    },
    NGA: {
        name: 'Nigeria',
        flagCss:
            'linear-gradient(90deg,#008751 0 33%,#FFFFFF 33% 66%,#008751 66%)',
    },
    GHA: {
        name: 'Ghana',
        flagCss:
            'linear-gradient(180deg,#CE1126 0 33%,#FCD116 33% 66%,#006B3F 66%)',
    },
    GER: {
        name: 'Germany',
        flagCss:
            'linear-gradient(180deg,#000000 0 33%,#DD0000 33% 66%,#FFCE00 66%)',
    },
    BRA: {
        name: 'Brazil',
        flagCss:
            'radial-gradient(circle at 50% 50%, #002776 0 22%, #FFDF00 22% 52%, #009C3B 52%)',
    },
    NED: {
        name: 'Netherlands',
        flagCss:
            'linear-gradient(180deg,#AE1C28 0 33%,#FFFFFF 33% 66%,#21468B 66%)',
    },
    FRA: {
        name: 'France',
        flagCss:
            'linear-gradient(90deg,#0055A4 0 33%,#FFFFFF 33% 66%,#EF4135 66%)',
    },
    JPN: {
        name: 'Japan',
        flagCss:
            'radial-gradient(circle at 50% 50%, #BC002D 0 28%, #FFFFFF 30%)',
    },
    POR: {
        name: 'Portugal',
        flagCss: 'linear-gradient(90deg,#006600 0 40%,#FF0000 40%)',
    },
    ARG: {
        name: 'Argentina',
        flagCss:
            'linear-gradient(180deg,#74ACDF 0 33%,#FFFFFF 33% 66%,#74ACDF 66%)',
    },
    GBR: {
        name: 'United Kingdom',
        flagCss:
            'linear-gradient(0deg, transparent 42%, #C8102E 42% 58%, transparent 58%), linear-gradient(90deg, transparent 42%, #C8102E 42% 58%, transparent 58%), #012169',
    },
    CAN: {
        name: 'Canada',
        flagCss:
            'linear-gradient(90deg, #D80621 0 27%, #FFFFFF 27% 73%, #D80621 73%)',
    },
    AUS: {
        name: 'Australia',
        flagCss:
            'radial-gradient(circle at 72% 55%, #FFFFFF 0 8%, transparent 9%), #012169',
    },
    ESP: {
        name: 'Spain',
        flagCss:
            'linear-gradient(180deg, #AA151B 0 25%, #F1BF00 25% 75%, #AA151B 75%)',
    },
    ITA: {
        name: 'Italy',
        flagCss:
            'linear-gradient(90deg, #009246 0 33%, #FFFFFF 33% 66%, #CE2B37 66%)',
    },
    KEN: {
        name: 'Kenya',
        flagCss:
            'linear-gradient(180deg, #000000 0 30%, #FFFFFF 30% 38%, #BB0000 38% 62%, #FFFFFF 62% 70%, #006600 70%)',
    },
    ZAF: {
        name: 'South Africa',
        flagCss:
            'linear-gradient(180deg, #E03C31 0 33%, #FFFFFF 33% 42%, #007749 42% 58%, #FFFFFF 58% 67%, #001489 67%)',
    },
    EGY: {
        name: 'Egypt',
        flagCss:
            'linear-gradient(180deg, #CE1126 0 33%, #FFFFFF 33% 66%, #000000 66%)',
    },
    POL: {
        name: 'Poland',
        flagCss: 'linear-gradient(180deg, #FFFFFF 0 50%, #DC143C 50%)',
    },
    UKR: {
        name: 'Ukraine',
        flagCss: 'linear-gradient(180deg, #0057B7 0 50%, #FFD700 50%)',
    },
    SWE: {
        name: 'Sweden',
        flagCss:
            'linear-gradient(0deg, transparent 40%, #FECC02 40% 60%, transparent 60%), linear-gradient(90deg, transparent 28%, #FECC02 28% 42%, transparent 42%), #006AA7',
    },
    IDN: {
        name: 'Indonesia',
        flagCss: 'linear-gradient(180deg, #CE1126 0 50%, #FFFFFF 50%)',
    },
    PAK: {
        name: 'Pakistan',
        flagCss: 'linear-gradient(90deg, #FFFFFF 0 25%, #01411C 25%)',
    },
    BGD: {
        name: 'Bangladesh',
        flagCss:
            'radial-gradient(circle at 45% 50%, #F42A41 0 30%, transparent 31%), #006A4E',
    },
    CHN: {
        name: 'China',
        flagCss:
            'radial-gradient(circle at 20% 30%, #FFDE00 0 12%, transparent 13%), #DE2910',
    },
    [WORLD_CODE]: {
        name: 'World XI',
        flagCss:
            'radial-gradient(circle at 50% 50%, #21E0C4 0 22%, transparent 23%), linear-gradient(135deg, #1F2430, #3A4256)',
    },
};

export function flagCssFor(code: string | null | undefined): string {
    return code ? (NATIONS[code]?.flagCss ?? '') : '';
}

export function nationNameFor(code: string): string {
    return NATIONS[code]?.name ?? code;
}
