import Vue from "vue";
import App from "./App.vue";
import router from "./router/index";
import store from "./store/index";
import "./sass/custom.scss";
import "./plugins/base";
import vuetify from "./plugins/vuetify";
import VuetifyConfirm from "vuetify-confirm";
Vue.use(VuetifyConfirm, { vuetify });
import i18n from "./i18n";
import "@/permission";
import Vuelidate from "vuelidate";
import axios from "axios";
axios.default.withCredentials = true;
const EventBus = new Vue();
Vue.prototype.$eventBus = EventBus;
Vue.use(Vuelidate);
Vue.prototype.$snackbar = (text, type, timeout = 2000) => {
    store.dispatch("app/showSnackbar", { text, type, timeout });
};
Vue.prototype.$loader = loader => {
    store.dispatch("app/setLoader", loader);
};
Vue.config.productionTip = false;
Vue.filter("date", function(value) {
    if (value) return new Date(value).toLocaleDateString("en-GB");
    else return null;
});
Vue.filter("time", function(value) {
    if (value) return new Date(value).toLocaleTimeString("en-GB");
    else return null;
});
Vue.filter("datetime", function(value) {
    if (value)
        return (
            new Date(value).toLocaleDateString("en-GB") +
            " " +
            new Date(value).toLocaleTimeString("en-GB")
        );
    else return null;
});
Vue.filter("money", function(value) {
    if (value != null)
        return value.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
});
new Vue({
    el: "#app",
    router,
    store,
    vuetify,
    i18n,
    render: h => h(App)
});
