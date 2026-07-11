export type * from './auth';

export interface SharedProps {
    name: string;
    weekLabel: string;
    [key: string]: unknown;
}
