import type { RatingBreakdown } from '@/components/card/types';

export interface LegionPlayer {
    id: string;
    handle: string;
    ovr: number;
    pos: string;
    topLanguage: string;
    captain: boolean;
    breakdown: RatingBreakdown | null;
}

export interface LegionCaptain {
    id: string;
    name: string;
    handle: string;
    pos: string;
    ovr: number;
    initials: string;
}

export interface Legion {
    code: string;
    rank: number;
    soldierCount: number;
    averageOvr: string;
    attack: LegionPlayer[];
    midfield: LegionPlayer[];
    defense: LegionPlayer[];
    goalkeeper: LegionPlayer[];
    captain: LegionCaptain | null;
    reserves: LegionPlayer[];
    recentEnlistments: number;
}

export interface LegionSummary {
    code: string;
    soldiers: number;
    averageOvr: string;
    topSoldier: string;
}

export interface LegionHeaderProps {
    code: string;
    name: string;
    soldierCount: number;
    rank: number;
    averageOvr: string;
    flagCss: string;
}

export interface PlayerChipProps {
    player: LegionPlayer;
    onSelect?: (player: LegionPlayer) => void;
}
