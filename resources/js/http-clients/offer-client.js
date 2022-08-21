import adminApi from "../api/adminAxios";

const BASE_URL = `/v1/dashboard/offers`;
export default {
    store(formValue) {
        return adminApi.post(BASE_URL, formValue);
    },
    update(formValue) {
        return adminApi.put(BASE_URL, formValue);
    },
    delete(id) {
        return adminApi.delete(`${BASE_URL}/${id}`);
    },
    toggleActivation(id) {
        return adminApi.get(`${BASE_URL}/toggle-activation/${id}`);
    },
    getPage(pageNo, pageSize, text) {
        return adminApi.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}&text=${text}`);
    },
}