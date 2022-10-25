import adminApi from "../api/adminAxios";
const BASE_URL = `/v1/dashboard/also-bought`;
export default {
    insertAlsoBoughtSettings(formValue) {
        return adminApi.post(`${BASE_URL}/settings`, formValue);
    },
    getAlsoBoughtSettings() {
        return adminApi.get(`${BASE_URL}/settings`);
    },
    getPage(pageNo, pageSize, text) {
        return adminApi.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}&text=${text}`);
    },
    store(formValue) {
        return adminApi.post(`${BASE_URL}`, formValue);
    },
    update(formValue) {
        return adminApi.put(`${BASE_URL}`, formValue);
    },
    delete(id) {
        return adminApi.delete(`${BASE_URL}/${id}`);
    },
    getProducts() {
        return adminApi.get(`${BASE_URL}/products`);
    },

}