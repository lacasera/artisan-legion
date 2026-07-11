import type { LogLine } from '@/components/lookup/types';
import { cn } from '@/lib/utils';

const TONE_CLASSES: Record<LogLine['tone'], string> = {
    faint: 'text-fg-4',
    mid: 'text-fg-2',
    bright: 'text-fg-1',
};

export default function TerminalLog({ lines }: { lines: LogLine[] }) {
    return (
        <div className="relative flex flex-col gap-3 px-12 font-mono text-[13px] leading-normal">
            {lines.map((line) => (
                <div
                    key={line.text}
                    className={cn('flex gap-2.5', TONE_CLASSES[line.tone])}
                >
                    <span className="text-ink-500">&gt;</span>
                    <span>{line.text}</span>
                </div>
            ))}
            <div className="flex gap-2.5 text-fg-1">
                <span className="text-ink-500">&gt;</span>
                <span>
                    striking card
                    <span className="ml-1.5 inline-block h-3.5 w-2 -translate-y-0.5 animate-al-blink bg-live-500 align-middle" />
                </span>
            </div>
        </div>
    );
}
