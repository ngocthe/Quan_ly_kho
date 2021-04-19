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
        name: "Phế Liệu",
        meta: {
            en_name: "Phế liệu"
        },
        path: "danhmuc/phelieus",
        component: () =>
            import("@/views/dashboard/business/danhmuc/phelieu/index")
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
   
];
export default businessRouter;
