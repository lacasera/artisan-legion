import type { FlagChipProps } from '@/components/shared/types';

export default function FlagChip({
    flagCss,
    width = 34,
    height = 22,
}: FlagChipProps) {
    return (
        <span
            className="inline-block rounded-xs border border-white/14"
            style={{ width, height, background: flagCss }}
        />
    );
}
