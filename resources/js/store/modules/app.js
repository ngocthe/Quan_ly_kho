const state = {
    barColor: "rgba(0, 0, 0, .8), rgba(0, 0, 0, .8)",
    barImage:
        "https://demos.creative-tim.com/material-dashboard-pro/assets/img/sidebar-1.jpg",
    drawer: null,
    snackbarShow: false,
    snackbarText: "",
    snackbarType: "info",
    loader: false,
    expandOnHover: false
};
const mutations = {
    SET_EXPAND_ON_HOVER(state, payload) {
        state.expandOnHover = payload;
    },
    SET_BAR_IMAGE(state, payload) {
        state.barImage = payload;
    },
    SET_DRAWER(state, payload) {
        state.drawer = payload;
    },
    SET_SCRIM(state, payload) {
        state.barColor = payload;
    },
    SET_SNACKBAR_SHOW(state, payload) {
        state.snackbarShow = payload;
    },
    SET_SNACKBAR_TEXT(state, payload) {
        state.snackbarText = payload;
    },
    SET_SNACKBAR_TYPE(state, payload) {
        state.snackbarType = payload;
    },
    SET_LOADER(state, payload) {
        state.loader = payload;
    }
};
const actions = {
    setBarImage({ commit }, payload) {
        commit("SET_BAR_IMAGE", payload);
    },
    setDrawer({ commit }, payload) {
        commit("SET_DRAWER", payload);
    },
    setScrim({ commit }, payload) {
        commit("SET_SCRIM", payload);
    },
    setLoader({ commit }, payload) {
        commit("SET_LOADER", payload);
    },
    showSnackbar({ commit }, { text, type, timeout = 6 }) {
        commit("SET_SNACKBAR_TEXT", text);
        commit("SET_SNACKBAR_TYPE", type);
        commit("SET_SNACKBAR_SHOW", true);
        setTimeout(() => commit("SET_SNACKBAR_SHOW", false), timeout);
    }
};
export default {
    namespaced: true,
    state,
    mutations,
    actions
};
