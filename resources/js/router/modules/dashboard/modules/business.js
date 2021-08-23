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
        path: "danhmuc/khos",
        component: () =>
            import("@/views/dashboard/business/kho/index")
    },
    {
        name: "Tồn Kho",
        meta: {
            en_name: "Tồn Kho"
        },
        path: "kho/tonkhos",
        component: () =>
            import("@/views/dashboard/business/kho/components/TonKho")
    },
    {
        name: "Xuống hàng",
        meta: {
            en_name: "Xuống hàng"
        },
        path: "xuonghangs/danhsach",
        component: () =>
            import("@/views/dashboard/business/xuonghang/danhsach/index")
    },
    {
        name: "Quản lý nhập kho",
        meta: {
            en_name: "Nhập kho"
        },
        path: "kho/nhapkhos",
        component: () =>
            import("@/views/dashboard/business/kho/nhapkho/index")
    },
    {
        name: "Lịch sử xuất nhập theo mặt hàng - Chi Tiết",
        meta: {
            en_name: "Lịch sử xuất nhập theo mặt hàng - Chi Tiết"
        },
        path: "kho/lichsus",
        component: () =>
            import("@/views/dashboard/business/kho/lichsu/index")
    },
    {
        name: "Nhập phân loại",
        meta: {
            en_name: "Nhập phân loại"
        },
        path: "kho/nhapphanloais",
        component: () =>
            import("@/views/dashboard/business/kho/nhapphanloai/index")
    },
    {
        name: "Danh sách nhập kho",
        meta: {
            en_name: "Danh sách nhập kho"
        },
        path: "kho/nhapkhoadmins",
        component: () =>
            import("@/views/dashboard/business/kho/nhapkhoadmin/index")
    },
    {
        name: "Quản lý xuất kho",
        meta: {
            en_name: "Xuất kho"
        },
        path: "kho/xuatkhos",
        component: () =>
            import("@/views/dashboard/business/kho/xuatkho/index")
    },
    {
        name: "Chuyển kho",
        meta: {
            en_name: "Chuyển kho"
        },
        path: "kho/chuyenkhos",
        component: () =>
            import("@/views/dashboard/business/kho/chuyenkho/index")
    },
    {
        name: "Phân loại",
        meta: {
            en_name: "Phân loại"
        },
        path: "kho/phanloais",
        component: () =>
            import("@/views/dashboard/business/kho/phanloai/index")
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
