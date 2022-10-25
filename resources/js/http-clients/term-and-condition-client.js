import adminApi from "../api/adminAxios";
const BASE_URL = `/v1/dashboard/terms-and-conditions`;
export default {
    getTermsAndConditions() {
        return adminApi.get(`${BASE_URL}`);
    },
    insertTermsAndConditions(formValue) {
        return adminApi.post(`${BASE_URL}`,formValue);
    },
}