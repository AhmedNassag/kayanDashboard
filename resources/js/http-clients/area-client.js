import adminApi from "../api/adminAxios";
const BASE_URL = `/v1/dashboard/areas`;
export default {
    store(formValue) {
        return adminApi.post(BASE_URL, formValue);
    },
    update(formValue) {
        return adminApi.put(BASE_URL, formValue);
    },
    getPage(pageNo, pageSize, text) {
        return adminApi.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}&text=${text}`);
    },
    getCities(){
        return adminApi.get(`${BASE_URL}/cities`);
    }
}