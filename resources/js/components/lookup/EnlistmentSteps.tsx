import type { EnlistStep } from '@/components/lookup/types';
import { cn } from '@/lib/utils';

export default function EnlistmentSteps({ steps }: { steps: EnlistStep[] }) {
    return (
        <div className="relative flex flex-col px-12">
            {steps.map((step, index) => (
                <div key={step.label} className="flex gap-4">
                    <div className="flex flex-col items-center">
                        <span
                            className={cn(
                                'mt-1 size-[11px] shrink-0 rounded-full border',
                                step.phase === 'pending'
                                    ? 'border-ink-500 bg-transparent'
                                    : 'border-live-500 bg-live-500',
                                step.phase === 'active' && 'animate-al-pulse',
                            )}
                        />
                        {index < steps.length - 1 && (
                            <span className="min-h-10 w-px flex-1 bg-ink-700" />
                        )}
                    </div>
                    <div className="flex flex-col gap-0.5 pb-6">
                        <span
                            className={cn(
                                'font-mono text-xs font-bold tracking-caps',
                                step.phase === 'active'
                                    ? 'text-fg-1'
                                    : step.phase === 'done'
                                      ? 'text-fg-2'
                                      : 'text-fg-4',
                            )}
                        >
                            {step.label}
                        </span>
                        <span className="text-[13px] text-fg-3">
                            {step.desc}
                        </span>
                    </div>
                </div>
            ))}
            <span className="mt-2 font-mono text-[11px] tracking-[0.06em] text-fg-4">
                Do not close. Your card is being struck.
            </span>
        </div>
    );
}
