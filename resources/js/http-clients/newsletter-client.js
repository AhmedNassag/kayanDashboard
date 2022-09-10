import adminApi from "../api/adminAxios";
const BASE_URL = `/v1/dashboard/newsletters`;
export default {
    getPage(pageNo, pageSize, text) {
        return adminApi.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}&text=${text}`);
    },
    getNewsletters() {
        return adminApi.get(`${BASE_URL}/all`);
    },
}