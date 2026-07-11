export interface WarBoardEntry {
    code: string;
    rank: number;
    move: string;
    points: number;
    dayPoints: number;
    topSoldier: string;
    pushing: boolean;
    pct: number;
}

export interface GainFlash {
    amount: number;
    key: number;
}

export interface WarPageProps {
    board: WarBoardEntry[];
    pushingCount: number;
    resetsAt: string;
}

export interface WarBoardRowProps {
    entry: WarBoardEntry;
    gain: GainFlash | undefined;
}
