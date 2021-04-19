import { login, logout, getInfo, initCSRFToken } from "@/api/system/user";
import {
    getAuth,
    setAuth,
    setLang,
    getLang,
    setCompany,
    getCompany
} from "@/utils/auth";
import { resetRouter } from "@/router";

const state = {
    auth: getAuth(),
    name: "",
    id: "",
    roles: [],
    menus: [],
    routes: [],
    avatar: "",
    phone_number: "",
    username: "",
    company_id:getCompany(),
    email: "",
    home_url: "",
    lang: getLang(),
    company:getCompany()
};

const mutations = {
    SET_COMPANY: (state, company) => {
        state.company = company;
        setCompany(company)
    },
    SET_COMPANY_ID: (state, company_id) => {
        state.company_id = company_id;
        setCompany(company_id)
        
    },
    SET_AUTH: (state, payload) => {
        state.auth = payload;
        setAuth(payload);
    },
    SET_ID: (state, id) => {
        state.id = id;
    },
    SET_USERNAME: (state, username) => {
        state.username = username;
    },
    SET_NAME: (state, name) => {
        state.name = name;
    },
    SET_ROLES: (state, roles) => {
        state.roles = roles;
    },
    SET_MENUS: (state, menus) => {
        state.menus = menus;
    },
    SET_ROUTES: (state, routes) => {
        state.routes = routes;
    },
    SET_PHONE_NUMBER: (state, phone_number) => {
        state.phone_number = phone_number;
    },
    SET_EMAIL: (state, email) => {
        state.email = email;
    },
    SET_HOME_URL: (state, home_url) => {
        state.home_url = home_url;
    },
    SET_AVATAR: (state, avatar) => {
        state.avatar = avatar && process.env.MIX_VUE_APP_BASE_URL + avatar;
    },
    SET_LANG(state, lang) {
        state.lang = lang;
        setLang(lang);
    }
};

const actions = {
    login({ commit }, userInfo) {
        const { username, password } = userInfo;
        return new Promise((resolve, reject) => {

            login({ username: username.trim(), password: password })
                .then(res => {
                    commit("SET_AUTH", true);
                    resolve(res.home_url);
                })
                .catch(error => {
                    reject(error);
                });

        });
    },

    // get user info
    getInfo({ commit }) {
        return new Promise((resolve, reject) => {
            getInfo()
                .then(response => {
                    const { data } = response;
                    if (!data) {
                        reject("Verification failed, please Login again.");
                    }
                    const {
                        id,
                        roles,
                        name,
                        phone_number,
                        email,
                        username,
                        menus,
                        routes,
                        avatar,
                        home_url,
                        company_id
                    } = data;

                    // roles must be a non-empty array
                    if (!roles || roles.length <= 0) {
                        reject("getInfo: roles must be a non-null array!");
                    }

                    commit("SET_ROLES", roles);
                    commit("SET_MENUS", menus);
                    commit("SET_ROUTES", routes);
                    commit("SET_NAME", name);
                    commit("SET_USERNAME", username);
                    commit("SET_ID", id);
                    commit("SET_PHONE_NUMBER", phone_number);
                    commit("SET_EMAIL", email);
                    commit("SET_HOME_URL", home_url);
                    commit("SET_AVATAR", avatar);
                    console.log(getCompany()=='null')
                    if(![2,3,4].includes(id)||getCompany()=='null')
                        commit("SET_COMPANY_ID", company_id);

                    resolve(data);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },

    // user logout
    logout({ commit }) {
        return new Promise((resolve, reject) => {
            logout()
                .then(() => {
                    commit("SET_AUTH", false);
                    commit("SET_ROLES", []);
                    commit("SET_COMPANY_ID", null);
                    resetRouter();
                    resolve();
                })
                .catch(error => {
                    console.log(error);
                    reject(error);
                });
        });
    },

    // remove auth
    removeAuth({ commit }) {
        return new Promise(resolve => {
            commit("SET_AUTH", false);
            commit("SET_COMPANY_ID", null);
            commit("SET_ROLES", []);
            resetRouter();
            resolve();
        });
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions
};
