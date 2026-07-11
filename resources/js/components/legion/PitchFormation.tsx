import PlayerChip from '@/components/legion/PlayerChip';
import type { LegionSquad } from '@/components/legion/types';

export default function PitchFormation({ squad }: { squad: LegionSquad }) {
    return (
        <div className="relative border-line-1 p-6 lg:border-r lg:px-12 lg:pt-10 lg:pb-12">
            <div className="mb-6 flex items-center justify-between">
                <span className="font-mono text-xs font-semibold tracking-caps text-fg-2">
                    STARTING XI · {squad.formation}
                </span>
                <span className="font-mono text-xs text-fg-4">
                    AVG OVR {squad.averageOvr}
                </span>
            </div>
            <div className="relative h-[880px] overflow-hidden rounded-lg border border-white/9 bg-ink-850">
                <div className="absolute inset-x-0 top-1/2 h-px bg-white/8" />
                <div className="absolute top-1/2 left-1/2 size-[140px] -translate-1/2 rounded-full border border-white/8" />
                <div className="absolute -top-px left-1/2 h-[90px] w-[300px] -translate-x-1/2 border border-t-0 border-white/8" />
                <div className="absolute -bottom-px left-1/2 h-[90px] w-[300px] -translate-x-1/2 border border-b-0 border-white/8" />
                <div className="absolute inset-0 flex flex-col justify-between px-8 py-11">
                    <div className="flex justify-around">
                        {squad.attack.map((player) => (
                            <PlayerChip key={player.handle} player={player} />
                        ))}
                    </div>
                    <div className="flex justify-around px-15">
                        {squad.midfield.map((player) => (
                            <PlayerChip key={player.handle} player={player} />
                        ))}
                    </div>
                    <div className="flex justify-around">
                        {squad.defense.map((player) => (
                            <PlayerChip key={player.handle} player={player} />
                        ))}
                    </div>
                    <div className="flex justify-center">
                        {squad.goalkeeper.map((player) => (
                            <PlayerChip key={player.handle} player={player} />
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}
