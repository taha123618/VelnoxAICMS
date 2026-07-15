import type { PageProps } from '@inertiajs/core';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: Modules.Auth.Data.AuthenticatedUserData;
}

export interface SharedData extends PageProps {
    name: string;
    version: string;
    flash: Record<string, any>;
    auth: Auth;
    ziggy: Config & { location: string };
}
