import adminApi from "../api/adminAxios";
const BASE_URL = `/v1/dashboard/about-sections`;
export default {
    update(formValue) {
        return adminApi.post(`${BASE_URL}`, formValue);
    },
    getAboutSections() {
        return adminApi.get(`${BASE_URL}`);
    },
}