import adminApi from "../api/adminAxios";
const BASE_URL = `/v1/dashboard/about-banners`;
export default {
    update(formValue) {
        return adminApi.post(`${BASE_URL}`, formValue);
    },
    getAboutBanners() {
        return adminApi.get(`${BASE_URL}`);
    },
}