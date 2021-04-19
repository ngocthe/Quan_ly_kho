import { asyncRoutes } from "@/router/index";
import store from "@/store";

export function filterAsyncRoutes(asyncRoutes, routes) {
    return asyncRoutes.filter(item => {
        return routes.includes(item.name);
    });
}

const actions = {
    generateRoutes() {
        return new Promise(resolve => {
            let accessedRoutes = [
                {
                    path: "/",
                    component: () => import("@/layouts/dashboard/Index"),
                    redirect: store.state.user.home_url,
                    children: [
                        ...asyncRoutes,
                        {
                            path: "/profile",
                            name: "Hồ sơ cá nhân",
                            component: () =>
                                import("@/views/dashboard/system/profile/index")
                        }
                    ]
                },
                {
                    path: "*",
                    redirect: "/404"
                }
            ];
            resolve(accessedRoutes);
        });
    }
};

export default {
    namespaced: true,
    actions
};
