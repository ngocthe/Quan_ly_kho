const systemRouter = [
    {
        name: "Người dùng",
        meta: {
            en_name: "User"
        },
        path: "users",
        component: () => import("@/views/dashboard/system/user/index")
    },
    {
        name: "Menu",
        meta: {
            en_name: "Menu"
        },
        path: "menus",
        component: () => import("@/views/dashboard/system/menu/index")
    },
    {
        name: "Quyền",

        meta: { en_name: "Permission" },
        path: "roles",
        component: () => import("@/views/dashboard/system/role/index")
    },
    {
        name: "Nhóm chức năng",
        meta: {
            en_name: "Function Group"
        },
        path: "action/action-groups",
        component: () =>
            import("@/views/dashboard/system/action/action-group/index")
    },
    {
        name: "Danh sách chức năng",
        meta: {
            en_name: "Function List"
        },
        path: "action/list",
        component: () => import("@/views/dashboard/system/action/list/index")
    }
];

export default systemRouter;
