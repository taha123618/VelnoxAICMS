export interface BlockData {
    id: string;
    sortOrder: number;
    label: string;
    image?: string;
    data: string;
    [key: string]: any; // allows other optional keys
}

const files = import.meta.glob('./**/*.json', { eager: true });

const blocks: BlockData[] = Object.values(files) as BlockData[];

blocks.sort((a, b) => (a.sortOrder ?? 0) - (b.sortOrder ?? 0));

export { blocks };

