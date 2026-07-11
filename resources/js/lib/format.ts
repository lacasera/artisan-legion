export function formatPoints(value: number): string {
    return value.toLocaleString('en-US');
}

export function formatCountdown(totalSeconds: number): string {
    const seconds = Math.max(0, totalSeconds);
    const days = Math.floor(seconds / 86400);
    const pad = (value: number) => String(value).padStart(2, '0');

    return `${days}d ${pad(Math.floor((seconds % 86400) / 3600))}:${pad(Math.floor((seconds % 3600) / 60))}:${pad(seconds % 60)}`;
}
