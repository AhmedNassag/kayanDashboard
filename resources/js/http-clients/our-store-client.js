import adminApi from "../api/adminAxios";
const BASE_URL = `/v1/dashboard/our-store`;
export default {
    save(formValue) {
        return adminApi.post(BASE_URL, formValue);
    },
    getOurStore() {
        return adminApi.get(BASE_URL);
    },
}