import router from "./router/index";
import store from "./store/index";
import NProgress from "nprogress"; // progress bar
import "nprogress/nprogress.css"; // progress bar style
import { getAuth } from "@/utils/auth"; // get auth from cookie
import getPageTitle from "@/utils/get-page-title";

NProgress.configure({ showSpinner: false }); // NProgress Configuration

const whiteList = ["/dgsp"]; // no redirect whitelist

router.beforeEach(async (to, from, next) => {
    // start progress bar
    NProgress.start();

    // set page title
    document.title = getPageTitle(to.name);
    // determine whether the user has logged in
    const isAuth = getAuth();
    if (isAuth) {
        if (to.path === "/dgsp") {
            // if is logged in, redirect to the home page
            next({ path: "/" });
        } else if (to.path === "/logout") {
            store
                .dispatch("user/logout")
                .then(res => {
                    next({ path: "/dgsp" });
                })
                .catch(error => next({ path: "/dgsp" }));
        } else {
            //determine whether the user has obtained his permission roles through getInfo
            const hasRoles =
                store.getters.roles && store.getters.roles.length > 0;
            if (hasRoles) {
                next();
            } else {
                try {
                    // get user info
                    // note: roles must be a object array! such as: ['admin'] or ,['developer','editor']
                    const { routes } = await store.dispatch("user/getInfo");
                    // store.dispatch("permission/getPermissions", menus);
                    // generate accessible routes map based on roles

                    const accessRoutes = await store.dispatch(
                        "permission/generateRoutes",
                        routes
                    );
                    // store.commit("permission/SET_PERMISSIONS");
                    // // dynamically add accessible routes
                    router.addRoutes(accessRoutes);
                    // hack method to ensure that addRoutes is complete
                    // set the replace: true, so the navigation will not leave a history record
                    next({ path: to.path,query:to.query });
                } catch (error) {
                    // remove token and go to login page to re-login
                    // await store.dispatch("user/removeAuth");
                    // Message.error(error || "Has Error");
                    // next(`/login?redirect=${to.path}`);
                    // NProgress.done();
                }
            }
            next();

        }
    } else {
        /* has no token*/

        if (whiteList.indexOf(to.path) !== -1) {
            // in the free login whitelist, go directly
            next();
        } else {
            // other pages that do not have permission to access are redirected to the login page.
            // if (to.path != "/home") next(`/account/login?redirect=${to.path}`);
            //else
            next("/dgsp");
        }
    }
});

router.afterEach(() => {
    // finish progress bar
    NProgress.done();
});
