import { useEffect, useState } from 'react';
import type { BoardRow, WarTickerState } from '@/components/war/types';
import { MY_LEGION_CODE, WAR_NATIONS } from '@/lib/mock/war';

const TICK_MS = 2000;
const INITIAL_COUNTDOWN = 2 * 86400 + 51727;

interface TickState {
    pts: Record<string, number>;
    gains: Record<string, number>;
    gainKeys: Record<string, number>;
    countdown: number;
    tick: number;
}

const initialState: TickState = {
    pts: Object.fromEntries(
        WAR_NATIONS.map((nation) => [nation.code, nation.pts]),
    ),
    gains: {},
    gainKeys: {},
    countdown: INITIAL_COUNTDOWN,
    tick: 0,
};

export function useWarTicker(): WarTickerState {
    const [state, setState] = useState<TickState>(initialState);

    useEffect(() => {
        const interval = setInterval(() => {
            setState((current) => {
                const pts = { ...current.pts };
                const gains: Record<string, number> = {};
                const gainKeys = { ...current.gainKeys };
                const hits = 1 + Math.floor(Math.random() * 3);

                for (let index = 0; index < hits; index++) {
                    const nation =
                        WAR_NATIONS[
                            Math.floor(Math.random() * WAR_NATIONS.length)
                        ];
                    const gain = 20 + Math.floor(Math.random() * 280);
                    pts[nation.code] += gain;
                    gains[nation.code] = gain;
                    gainKeys[nation.code] = (gainKeys[nation.code] ?? 0) + 1;
                }

                return {
                    pts,
                    gains,
                    gainKeys,
                    countdown: current.countdown - TICK_MS / 1000,
                    tick: current.tick + 1,
                };
            });
        }, TICK_MS);

        return () => clearInterval(interval);
    }, []);

    const ranked = WAR_NATIONS.map((nation) => ({
        ...nation,
        livePts: state.pts[nation.code],
    })).sort((a, b) => b.livePts - a.livePts);
    const maxPts = ranked[0].livePts;
    const board: BoardRow[] = ranked.map((nation, index) => ({
        ...nation,
        rank: String(index + 1).padStart(2, '0'),
        pct: Math.round((nation.livePts / maxPts) * 100),
        isMine: nation.code === MY_LEGION_CODE,
        gain: state.gains[nation.code] ?? 0,
        gainKey: `${nation.code}-${state.gainKeys[nation.code] ?? 0}`,
    }));

    return {
        board,
        pushingCount: 2412 + state.tick * 3,
        countdown: state.countdown,
    };
}
