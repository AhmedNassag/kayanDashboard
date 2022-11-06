import adminApi from "../api/adminAxios";
const BASE_URL = `/v1/dashboard/footer-links`;
export default {
    update(formValue) {
        return adminApi.post(`${BASE_URL}`, formValue);
    },
    getFooterLinks() {
        return adminApi.get(`${BASE_URL}`);
    },
}