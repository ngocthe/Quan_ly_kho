import Vue from "vue";
import VueI18n from "vue-i18n";
import { getLang } from "@/utils/auth";
import en from "vuetify/lib/locale/en";

Vue.use(VueI18n);
const messages = {
    en: {
        ...require("@/locales/en/en.json"),
        ...require("@/locales/en/partner.json"),
        ...require("@/locales/en/bank.json"),
        ...require("@/locales/en/re_ex.json"),
        ...require("@/locales/en/debt.json"),

        $vuetify: en
    },
    vi: {
        ...require("@/locales/vi/vi.json"),
        ...require("@/locales/vi/partner.json"),
        ...require("@/locales/vi/bank.json"),
        ...require("@/locales/vi/re_ex.json"),
        ...require("@/locales/vi/debt.json")
    }
};

export default new VueI18n({
    locale: getLang(),
    fallbackLocale: "en",
    messages
});
