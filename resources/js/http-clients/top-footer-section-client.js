import adminApi from "../api/adminAxios";
const BASE_URL = `/v1/dashboard/top-footer-sections`;
export default {
    save(formValue) {
        return adminApi.post(BASE_URL, formValue);
    },
    getTopFooterSections() {
        return adminApi.get(BASE_URL);
    },
}