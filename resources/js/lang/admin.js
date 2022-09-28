import { createI18n } from 'vue-i18n';
// import en from "../locales/admin/en.json";
import ar from "../locales/admin/ar.json";


const i18n = createI18n({
    locale: 'ar', // set locale
    fallbackLocale: 'en', // set fallback locale,
    messages: {
        ar
    },
    legacy:false
});


export default i18n;
