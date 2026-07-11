export interface PitchPlayer {
    handle: string;
    ovr: number;
    pos: string;
    lang: string;
    captain?: boolean;
}

export interface BenchPlayer {
    handle: string;
    ovr: number;
    pos: string;
    lang: string;
}

export interface LegionCaptain {
    name: string;
    handle: string;
    pos: string;
    ovr: number;
    initials: string;
    weeklySummary: string;
}

export interface LegionSquad {
    formation: string;
    averageOvr: string;
    attack: PitchPlayer[];
    midfield: PitchPlayer[];
    defense: PitchPlayer[];
    goalkeeper: PitchPlayer[];
    bench: BenchPlayer[];
    captain: LegionCaptain;
}

export interface LegionHeaderProps {
    code: string;
    name: string;
    soldierCount: string;
    standing: number;
    standingMove: string;
    points: number;
    behindText: string;
    flagCss: string;
}

export interface PlayerChipProps {
    player: PitchPlayer;
}
