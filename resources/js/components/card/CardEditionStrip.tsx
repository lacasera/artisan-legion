import type { CardEditionStripProps } from '@/components/card/types';

export default function CardEditionStrip({
    serial,
    tier,
    chevronColor,
}: CardEditionStripProps) {
    return (
        <div className="absolute inset-x-0 bottom-0 flex items-center justify-between border-t border-line-1 px-6 py-3">
            <span className="font-mono text-[10px] font-medium tracking-widest text-fg-3">
                ARTISAN LEGION · S03 · W27 · № {serial}
            </span>
            <div className="flex items-end gap-[3px]">
                {Array.from({ length: tier }, (_, index) => (
                    <svg
                        key={index}
                        width="14"
                        height="9"
                        viewBox="0 0 14 9"
                        fill="none"
                        stroke={chevronColor}
                        strokeWidth="1.8"
                    >
                        <path d="M1.5 7.5 L7 2 L12.5 7.5" />
                    </svg>
                ))}
            </div>
        </div>
    );
}
