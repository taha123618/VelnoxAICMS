declare namespace App.Enums {
export type Status = 'Published' | 'Draft';
}
declare namespace Modules.Auth.Data {
export type AuthenticatedUserData = {
id: string | number;
first_name: string;
last_name: string;
name: string;
email: string;
isVerified: boolean;
avatar: string | null;
can: Modules.Auth.Data.AuthorizationData;
};
export type AuthorizationData = {
create_pages: boolean;
update_pages: boolean;
create_posts: boolean;
update_posts: boolean;
create_users: boolean;
update_users: boolean;
create_categories: boolean;
update_categories: boolean;
create_layouts: boolean;
update_layouts: boolean;
create_folders: boolean;
update_folders: boolean;
create_menus: boolean;
update_menus: boolean;
create_roles: boolean;
update_roles: boolean;
create_testimonials: boolean;
update_testimonials: boolean;
};
export type RoleData = {
id: string;
name: string;
label: string;
total_permissions: string;
total_users: string;
created_at: string;
permissions: any | Array<any>;
can?: Array<any>;
};
export type UserData = {
id: string | number;
first_name: string;
last_name: string;
name: string;
role_name: string | null;
role_label: string | null;
role_id: string | null;
avatar: string | null;
email: string;
created_at: string;
can?: Array<any>;
};
}
declare namespace Modules.Builder.Enums {
export type LinkType = 'page' | 'post' | 'external' | 'category';
}
declare namespace Modules.Category.Data {
export type CategoryData = {
id: string | number;
name: string;
slug: string;
totalPosts: number;
parentId: string | number | null;
parentName: string | null;
description: string | null;
children?: any | null;
can?: Array<any>;
};
}
declare namespace Modules.Contacts.Data {
export type ContactData = {
id: string | number;
name: string;
email: string;
subject: string;
body: string;
isSubscribed: boolean;
created_at: string;
updated_at: string;
};
}
declare namespace Modules.Dashboard.Data {
export type DashboardData = {
country_data: Array<any>;
visits_over_time: any | Array<any>;
visits_by_browser: any | Array<any>;
visits_by_url: any | Array<any>;
stats: Modules.Dashboard.Data.DashboardStatsData;
};
export type DashboardStatsData = {
total_pageviews: number | null;
unique_visitors: number | null;
pending_pages: number | null;
pending_posts: number | null;
total_pages: number | null;
total_posts: number | null;
};
export type VisitorData = {
id: string;
};
}
declare namespace Modules.Layout.Data {
export type LayoutData = {
id: string | number;
name: string;
totalPages: string;
totalPosts: string;
created: string | null;
updated: string | null;
statusColor: string | null;
content?: Array<any>;
can?: Array<any>;
};
}
declare namespace Modules.Media.Data {
export type FolderData = {
id: string | number;
name: string;
can?: Array<any>;
};
export type MediaData = {
id: string | number;
uuid: string;
name: string;
size: string;
updatedAt: string;
url?: string;
thumbnail?: string;
};
}
declare namespace Modules.Menu.Data {
export type MenuData = {
id: string | number;
name: string;
items?: any;
totalItems: number;
created_at: string;
can?: Array<any>;
};
export type MenuItemData = {
id: string | number;
isRecent: boolean;
menuId: string;
parentId: string | number | null;
type: Modules.Menu.Enums.MenuItemType;
label: string;
path: string;
href: string;
to: string;
target: string;
defaultOpen: boolean;
children?: any | null;
};
}
declare namespace Modules.Menu.Enums {
export type MenuItemType = 'page' | 'post' | 'category' | 'custom';
}
declare namespace Modules.Page.Data {
export type PageData = {
id: string | number;
type?: string;
layoutId: string | number;
layoutName: string;
slug: string;
title: string;
status: App.Enums.Status;
statusColor: string;
url: string;
description: string | null;
content: Array<any>;
isFrontpage: boolean;
isPublished: boolean;
keywords?: Array<any>;
can?: Array<any>;
created_at: string;
updated_at: string;
isDifferentFromPublishedVersion: boolean;
};
export type PostData = {
id: string | number;
type?: string;
layoutId: string | number | null;
layoutName: string | null;
categoryName: string | null;
categoryId: string | number | null;
slug: string;
title: string;
status: App.Enums.Status;
statusColor: string;
url: string;
isPublished: boolean;
description: string | null;
excerpt: string | null;
featuredImage: string | null;
content: Array<any>;
keywords?: Array<any>;
created_at: string;
isDifferentFromPublishedVersion: boolean;
can?: Array<any>;
};
}
declare namespace Modules.Page.Enums {
export type PageType = 'page' | 'post';
}
declare namespace Modules.Testimonial.Data {
export type TestimonialData = {
id: string | number;
name: string;
avatar: string | null;
title: string | null;
comment: string;
status: App.Enums.Status;
statusColor: string;
created_at: string;
isPublished: boolean;
can?: Array<any>;
};
}
