import adminApi from "../api/adminAxios";
const BASE_URL = `/v1/dashboard/deal`;
export default {
    insertDeal(formValue) {
        return adminApi.post(BASE_URL, formValue);
    },
    getDeal() {
        return adminApi.get(BASE_URL);
    },
    getProducts(){
        return adminApi.get(`${BASE_URL}/products`);
    }
}