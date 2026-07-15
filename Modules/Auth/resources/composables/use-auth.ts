import { SharedData } from '@/types';

export const useAuth = () => {
    return computed<Modules.Auth.Data.AuthenticatedUserData>(
        () => usePage<SharedData>().props.auth.user,
    );
};
