import Vue from "vue";
import Router from "vue-router";
import pagesRouter from "./modules/pages/index";
import daugiasRouter from "./modules/daugias/index";

import dashboardRouter from "./modules/dashboard/index";
Vue.use(Router);
export const asyncRoutes = dashboardRouter;
export const routes = [
    daugiasRouter,
    pagesRouter,
    // {
    //     path: "/",
    //     component: () => import("@/layouts/dashboard/Index"),
    //     children: dashboardRouter
    // },
    {
        path: "/pdf",
        name: "PDF",
        component: () => import("@/views/pdf/index.vue")
    },
    {
        path: "/dgsp",
        name: "Đấu giá",
        component: () => import("@/views/DauGia.vue")
    },
    {
        path: "/logout",
        name: "Logout"
    }
];
const createRouter = () =>
    new Router({
        mode: "history",
        routes
    });
const router = createRouter();
export function resetRouter() {
    const newRouter = createRouter();
    router.matcher = newRouter.matcher; // reset router
}

export default router;
