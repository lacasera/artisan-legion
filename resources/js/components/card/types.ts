export interface CardStat {
    name: string;
    val: number;
}

export interface CardDev {
    name: string;
    handle: string;
    avatar: string | null;
    initials?: string;
    ovr: number;
    pos: string;
    nation: string | null;
    flagCss: string;
    rankLabel: string;
    serial: string;
    specialist?: boolean;
    stats: CardStat[];
}

export interface ServerCardDev extends Omit<CardDev, 'flagCss' | 'initials'> {
    id: string;
    specialist: boolean;
}

export interface CardTheme {
    isGold: boolean;
    tier: number;
    chevronColor: string;
}

export interface LegionCardProps {
    dev: CardDev;
    foil?: boolean;
}

export interface CardHeaderProps {
    dev: CardDev;
    isGold: boolean;
}

export interface CardStatsProps {
    stats: CardStat[];
    isGold: boolean;
    specialist: boolean;
}

export interface CardEditionStripProps {
    serial: string;
    tier: number;
    chevronColor: string;
}
