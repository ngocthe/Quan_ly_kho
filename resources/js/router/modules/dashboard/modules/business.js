const businessRouter = [
    // {
    //     name: "Danh sách kho",
    //     meta: {
    //         en_name: "Danh sách kho"
    //     },
    //     path: "khos",
    //     component: () => import("@/views/dashboard/business/kho/index")
    // },
    {
        name: "Xe",
        meta: {
            en_name: "Xe"
        },
        path: "danhmuc/xes",
        component: () =>
            import("@/views/dashboard/business/danhmuc/xe/index")
    },
    {
        name: "Kho",
        meta: {
            en_name: "Kho"
        },
        path: "khos",
        component: () =>
            import("@/views/dashboard/business/kho/index")
    },
    {
        name: "Quản lý nhập kho",
        meta: {
            en_name: "Nhập kho"
        },
        path: "nhapkhos",
        component: () =>
            import("@/views/dashboard/business/kho/nhapkho/index")
    },
    {
        name: "Phế Liệu",
        meta: {
            en_name: "Phế liệu"
        },
        path: "danhmuc/phelieus",
        component: () =>
            import("@/views/dashboard/business/danhmuc/phelieu/index")
    },
    {
        name: "Tài khoản",
        meta: {
            en_name: "Tài khoản"
        },
        path: "danhmuc/taikhoans",
        component: () =>
            import("@/views/dashboard/business/danhmuc/taikhoan/index")
    },
    {
        name: "Thủ kho",
        meta: {
            en_name: "Thủ kho"
        },
        path: "nhansu/thukhos",
        component: () =>
            import("@/views/dashboard/business/nhansu/thukho/index")
    },
    {
        name: "NVBH",
        meta: {
            en_name: "NVBH"
        },
        path: "nhansu/nvbhs",
        component: () =>
            import("@/views/dashboard/business/nhansu/nvbh/index")
    },
    {
        name: "Bảo vệ",
        meta: {
            en_name: "Bảo vệ"
        },
        path: "nhansu/baoves",
        component: () =>
            import("@/views/dashboard/business/nhansu/baove/index")
    },
    {
        name: "Khách Hàng",
        meta: {
            en_name: "Khách Hàng"
        },
        path: "danhmuc/khachhangs",
        component: () =>
            import("@/views/dashboard/business/danhmuc/khachhang/index")
    },
    {
        name: "Đối Tác",
        meta: {
            en_name: "Đối Tác"
        },
        path: "danhmuc/doitacs",
        component: () =>
            import("@/views/dashboard/business/danhmuc/doitac/index")
    },
   
];
export default businessRouter;
