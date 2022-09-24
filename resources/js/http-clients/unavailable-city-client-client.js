import adminApi from "../api/adminAxios";

const BASE_URL = `/v1/dashboard/unavailable-cities-clients`;
export default {
    getUnavailableCitiesClients(pageNo, pageSize, text) {
        return adminApi.get(`${BASE_URL}?page=${pageNo}&page_size=${pageSize}&text=${text}`);
    },
    getAllUnavailableCitiesClients() {
        return adminApi.get(`${BASE_URL}/all`);
    },
}