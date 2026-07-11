export interface WarNation {
    code: string;
    name: string;
    pts: number;
    day: number;
    top: string;
    move: string;
    pushing: boolean;
    soldiers: number;
}

export interface BoardRow extends WarNation {
    rank: string;
    livePts: number;
    pct: number;
    isMine: boolean;
    gain: number;
    gainKey: string;
}

export interface WarTickerState {
    board: BoardRow[];
    pushingCount: number;
    countdown: number;
}

export interface WarBoardRowProps {
    row: BoardRow;
    isLeader: boolean;
}
