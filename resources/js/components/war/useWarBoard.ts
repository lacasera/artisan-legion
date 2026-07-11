import { usePoll } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import type { GainFlash, WarBoardEntry } from '@/components/war/types';

const POLL_INTERVAL_MS = 10000;

export function useWarBoard(board: WarBoardEntry[], resetsAt: string) {
    usePoll(POLL_INTERVAL_MS, { only: ['board', 'pushingCount'] });

    const [previousBoard, setPreviousBoard] = useState<WarBoardEntry[] | null>(
        null,
    );
    const [gains, setGains] = useState<Record<string, GainFlash>>({});

    if (previousBoard !== board) {
        setPreviousBoard(board);

        if (previousBoard !== null) {
            const before = Object.fromEntries(
                previousBoard.map((entry) => [entry.code, entry.points]),
            );
            const deltas: Record<string, number> = {};

            for (const entry of board) {
                const earlier = before[entry.code];

                if (earlier !== undefined && entry.points > earlier) {
                    deltas[entry.code] = entry.points - earlier;
                }
            }

            if (Object.keys(deltas).length > 0) {
                setGains((current) => {
                    const next = { ...current };

                    for (const [code, amount] of Object.entries(deltas)) {
                        next[code] = {
                            amount,
                            key: (current[code]?.key ?? 0) + 1,
                        };
                    }

                    return next;
                });
            }
        }
    }

    const [nowMs, setNowMs] = useState(() => Date.now());

    useEffect(() => {
        const interval = setInterval(() => setNowMs(Date.now()), 1000);

        return () => clearInterval(interval);
    }, []);

    const countdown = Math.max(
        0,
        Math.floor((new Date(resetsAt).getTime() - nowMs) / 1000),
    );

    return { gains, countdown };
}
