export type User = {
    id: number;
    name: string;
    nombre_u: string;
    a_paterno: string;
    a_materno: string | null;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    direccion?: string | null;
    ci?: string | null;
    fecha_ingreso?: string | null;
    fecha_fin?: string | null;
    telefono?: string | null;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
    roles: string[];
    permissions: string[];
    isAdmin: boolean;
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
