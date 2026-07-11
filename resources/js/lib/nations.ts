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
    BEL: {
        name: 'Belgium',
        flagCss:
            'linear-gradient(90deg, #000000 0 33%, #FDDA24 33% 66%, #EF3340 66%)',
    },
    IRL: {
        name: 'Ireland',
        flagCss:
            'linear-gradient(90deg, #169B62 0 33%, #FFFFFF 33% 66%, #FF883E 66%)',
    },
    CHE: {
        name: 'Switzerland',
        flagCss:
            'linear-gradient(0deg, transparent 40%, #FFFFFF 40% 60%, transparent 60%), linear-gradient(90deg, transparent 40%, #FFFFFF 40% 60%, transparent 60%), #D52B1E',
    },
    AUT: {
        name: 'Austria',
        flagCss:
            'linear-gradient(180deg, #ED2939 0 33%, #FFFFFF 33% 66%, #ED2939 66%)',
    },
    NOR: {
        name: 'Norway',
        flagCss:
            'linear-gradient(0deg, transparent 36%, #FFFFFF 36% 64%, transparent 64%), linear-gradient(90deg, transparent 28%, #FFFFFF 28% 44%, transparent 44%), linear-gradient(0deg, transparent 40%, #00205B 40% 60%, transparent 60%), linear-gradient(90deg, transparent 32%, #00205B 32% 40%, transparent 40%), #BA0C2F',
    },
    DNK: {
        name: 'Denmark',
        flagCss:
            'linear-gradient(0deg, transparent 40%, #FFFFFF 40% 60%, transparent 60%), linear-gradient(90deg, transparent 32%, #FFFFFF 32% 44%, transparent 44%), #C8102E',
    },
    FIN: {
        name: 'Finland',
        flagCss:
            'linear-gradient(0deg, transparent 40%, #003580 40% 60%, transparent 60%), linear-gradient(90deg, transparent 28%, #003580 28% 44%, transparent 44%), #FFFFFF',
    },
    MEX: {
        name: 'Mexico',
        flagCss:
            'linear-gradient(90deg, #006847 0 33%, #FFFFFF 33% 66%, #CE1126 66%)',
    },
    COL: {
        name: 'Colombia',
        flagCss:
            'linear-gradient(180deg, #FCD116 0 50%, #003893 50% 75%, #CE1126 75%)',
    },
    CHL: {
        name: 'Chile',
        flagCss:
            'linear-gradient(180deg, #FFFFFF 0 50%, #D52B1E 50%), radial-gradient(circle at 16% 26%, #FFFFFF 0 5%, transparent 6%), linear-gradient(90deg, #0039A6 0 33%, transparent 33%)',
    },
    RUS: {
        name: 'Russia',
        flagCss:
            'linear-gradient(180deg, #FFFFFF 0 33%, #0039A6 33% 66%, #D52B1E 66%)',
    },
    TUR: {
        name: 'Turkey',
        flagCss:
            'radial-gradient(circle at 40% 50%, #FFFFFF 0 14%, transparent 15%), #E30A17',
    },
    GRC: {
        name: 'Greece',
        flagCss:
            'repeating-linear-gradient(180deg, #0D5EAF 0 11.1%, #FFFFFF 11.1% 22.2%), linear-gradient(#0D5EAF, #0D5EAF)',
    },
    CZE: {
        name: 'Czechia',
        flagCss:
            'linear-gradient(180deg, #FFFFFF 0 50%, #D7141A 50%), linear-gradient(135deg, #11457E 40%, transparent 40%)',
    },
    ROU: {
        name: 'Romania',
        flagCss:
            'linear-gradient(90deg, #002B7F 0 33%, #FCD116 33% 66%, #CE1126 66%)',
    },
    HUN: {
        name: 'Hungary',
        flagCss:
            'linear-gradient(180deg, #CE2939 0 33%, #FFFFFF 33% 66%, #477050 66%)',
    },
    KOR: {
        name: 'South Korea',
        flagCss:
            'radial-gradient(circle at 50% 50%, #CD2E3A 0 8%, #0047A0 8% 16%, transparent 16%), #FFFFFF',
    },
    SGP: {
        name: 'Singapore',
        flagCss: 'linear-gradient(180deg, #EF3340 0 50%, #FFFFFF 50%)',
    },
    PHL: {
        name: 'Philippines',
        flagCss:
            'linear-gradient(90deg, transparent 22%, #0038A8 22% 61%, transparent 61%), linear-gradient(180deg, #0038A8 50%, #CE1126 50%)',
    },
    VNM: {
        name: 'Vietnam',
        flagCss:
            'radial-gradient(circle at 50% 50%, #FFFF00 0 14%, transparent 15%), #DA251D',
    },
    THA: {
        name: 'Thailand',
        flagCss:
            'linear-gradient(180deg, #A51931 0 16.6%, #F4F5F8 16.6% 33.3%, #2D2A4A 33.3% 66.6%, #F4F5F8 66.6% 83.3%, #A51931 83.3%)',
    },
    NZL: {
        name: 'New Zealand',
        flagCss:
            'radial-gradient(circle at 72% 55%, #FFFFFF 0 7%, transparent 8%), #00247D',
    },
    ISR: {
        name: 'Israel',
        flagCss:
            'linear-gradient(180deg, transparent 15%, #0038B8 15% 28%, transparent 28% 72%, #0038B8 72% 85%, transparent 85%), #FFFFFF',
    },
    ARE: {
        name: 'United Arab Emirates',
        flagCss:
            'linear-gradient(90deg, #FF0000 0 25%, transparent 25%), linear-gradient(180deg, #00732F 0 33%, #FFFFFF 33% 66%, #000000 66%)',
    },
    MAR: {
        name: 'Morocco',
        flagCss:
            'radial-gradient(circle at 50% 50%, #006233 0 12%, transparent 13%), #C1272D',
    },
    ETH: {
        name: 'Ethiopia',
        flagCss:
            'linear-gradient(180deg, #078930 0 33%, #FCDD09 33% 66%, #DA121A 66%)',
    },
    UGA: {
        name: 'Uganda',
        flagCss:
            'linear-gradient(180deg, #000000 0 16.6%, #FCDC04 16.6% 33.3%, #D90000 33.3% 50%, #000000 50% 66.6%, #FCDC04 66.6% 83.3%, #D90000 83.3%)',
    },
    SEN: {
        name: 'Senegal',
        flagCss:
            'linear-gradient(90deg, #00853F 0 33%, #FDEF42 33% 66%, #E31B23 66%)',
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
