export type * from './auth';

export interface SharedProps {
    name: string;
    weekLabel: string;
    appHost: string;
    [key: string]: unknown;
}
