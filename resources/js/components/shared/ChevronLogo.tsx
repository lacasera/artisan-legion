import type { ChevronLogoProps } from '@/components/shared/types';

export default function ChevronLogo({
    size = 22,
    color = '#FF2E4D',
    strokeWidth = 2.4,
}: ChevronLogoProps) {
    return (
        <svg
            width={size}
            height={(size * 20) / 22}
            viewBox="0 0 22 20"
            fill="none"
            stroke={color}
            strokeWidth={strokeWidth}
        >
            <path d="M2 8 L11 2 L20 8" />
            <path d="M2 13 L11 7 L20 13" />
            <path d="M2 18 L11 12 L20 18" />
        </svg>
    );
}
