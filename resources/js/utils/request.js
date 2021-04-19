import axios from "axios";
import store from "@/store";

//axios.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;

const service = axios.create({
    baseURL: process.env.MIX_VUE_APP_BASE_URL + '/api',
     // url = base url + request url
    withCredentials: true, // send cookies when cross-domain requests
    timeout: 10000 // request timeout,
    
});

// request interceptor
service.interceptors.request.use(
    config => {
        config.headers={...config.headers,
            "x-company-id" :store.state.user.company_id
        }
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

// response interceptor
service.interceptors.response.use(
    /**
     * If you want to get http information such as headers or status
     * Please return  response => response
     */

    /**
     * Determine the request status by custom code
     * Here is just an example
     * You can also judge the status by HTTP Status Code
     */
    response => {
        const res = response.data;

        // if the custom code is not 20000, it is judged as an error.
        // if (res.code !== 20000) {
        //     Message({
        //         message: res.message || 'Error',
        //         type: 'error',
        //         duration: 5 * 1000
        //     })

        //     // 50008: Illegal token; 50012: Other clients logged in; 50014: Token expired;
        //     if (res.code === 50008 || res.code === 50012 || res.code === 50014) {
        //         // to re-login
        //         MessageBox.confirm('You have been logged out, you can cancel to stay on this page, or log in again', 'Confirm logout', {
        //             confirmButtonText: 'Re-Login',
        //             cancelButtonText: 'Cancel',
        //             type: 'warning'
        //         }).then(() => {
        //             store.dispatch('user/removeAuth').then(() => {
        //                 location.reload()
        //             })
        //         })
        //     }
        //     return Promise.reject(new Error(res.message || 'Error'))
        // } else {

        return res;
        //}
    },
    error => {
        if (error.response.status === 422) {
            store.dispatch("app/showSnackbar", {
                text:
                    error.response.data.errors[
                        Object.keys(error.response.data.errors)[0]
                    ][0],
                type: "error",
                timeout: 2000
            });
        } else if (error.response.data.message == "Unauthenticated.") {
            // to re-login
            store.dispatch("app/showSnackbar", {
                text: "Bạn đã đăng xuất",
                type: "error",
                timeout: 2000
            });
            store.dispatch("user/removeAuth").then(() => {
                location.reload();
            });
        } else if (error.response.status === 400)
            store.dispatch("app/showSnackbar", {
                text: error.response.data.message,
                type: "error",
                timeout: 2000
            });
        else if (error.response.status === 401) {
            store.dispatch("app/showSnackbar", {
                text: error.response.data.message,
                type: "error",
                timeout: 2000
            });
        } else if (error.response.status === 404) router.push("/404");
        return Promise.reject(error);
    }
);

export default service;
