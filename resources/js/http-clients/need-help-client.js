import adminApi from "../api/adminAxios";
const BASE_URL = `/v1/dashboard/need-help`;
export default {
    save(formValue) {
        return adminApi.post(BASE_URL, formValue);
    },
    getNeedHelp() {
        return adminApi.get(BASE_URL);
    },
}