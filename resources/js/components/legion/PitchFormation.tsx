import PlayerChip from '@/components/legion/PlayerChip';
import type { Legion, LegionPlayer } from '@/components/legion/types';

export default function PitchFormation({
    legion,
    onSelect,
    selectedId,
}: {
    legion: Legion;
    onSelect: (player: LegionPlayer) => void;
    selectedId: string | null;
}) {
    const formation = [
        legion.defense.length,
        legion.midfield.length,
        legion.attack.length,
    ]
        .filter((count) => count > 0)
        .join('–');

    const rows: LegionPlayer[][] = [
        legion.attack,
        legion.midfield,
        legion.defense,
        legion.goalkeeper,
    ];

    return (
        <div className="relative border-line-1 p-6 lg:border-r lg:px-12 lg:pt-10 lg:pb-12">
            <div className="mb-6 flex items-center justify-between">
                <span className="font-mono text-xs font-semibold tracking-caps text-fg-2">
                    STARTING XI{formation && ` · ${formation}`}
                </span>
                <span className="font-mono text-xs text-fg-4">
                    AVG OVR {legion.averageOvr}
                </span>
            </div>
            <div className="relative h-[640px] overflow-hidden rounded-lg border border-white/9 bg-ink-850 lg:h-[880px]">
                <div className="absolute inset-x-0 top-1/2 h-px bg-white/8" />
                <div className="absolute top-1/2 left-1/2 size-[140px] -translate-1/2 rounded-full border border-white/8" />
                <div className="absolute -top-px left-1/2 h-[90px] w-[300px] -translate-x-1/2 border border-t-0 border-white/8" />
                <div className="absolute -bottom-px left-1/2 h-[90px] w-[300px] -translate-x-1/2 border border-b-0 border-white/8" />
                <div className="absolute inset-0 flex flex-col justify-between px-3 py-6 lg:px-8 lg:py-11">
                    {rows.map((row, index) => (
                        <div
                            key={index}
                            className={
                                index === 1
                                    ? 'flex justify-around px-4 lg:px-15'
                                    : index === 3
                                      ? 'flex justify-center'
                                      : 'flex justify-around'
                            }
                        >
                            {row.map((player) => (
                                <PlayerChip
                                    key={player.id}
                                    player={player}
                                    onSelect={onSelect}
                                    selected={player.id === selectedId}
                                />
                            ))}
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
