export interface ITableData {
    data: any;
    current_page: number;
    current_page_url: string;
    first_page_url: string;
    last_page_url: string;
    next_page_url: string;
    prev_page_url: string | null;
    path: string;
    per_page: number;
    from: number;
    to: number;
    total: number;
    last_page: number;
}
