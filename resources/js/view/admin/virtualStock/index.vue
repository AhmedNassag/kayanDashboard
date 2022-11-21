<template>
    <div :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.Virtual Stocks") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{ name: 'dashboard' }">
                                    {{ $t("dashboard.Dashboard") }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t("global.Virtual Stocks") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <loader v-if="loading" />
                        <div class="card-body">
                            <!-- <div class="card-header pt-0">
                                <div class="row justify-content-between">
                                    <div class="col-5">
                                        {{ $t("global.Search") }}:
                                        <input type="search" v-model="search" class="custom" />
                                    </div>
                                    <div class="col-5 row justify-content-end">
                                        <router-link
                                        v-if="permission.includes('virtualStock create')"
                                        :to="{ name: 'createVirtualStock' }"
                                        class="btn btn-custom btn-warning"
                                        >
                                        {{ $t("global.Add") }}
                                        </router-link>
                                    </div>
                                </div>
                            </div> -->
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">{{ $t("global.Name") }}</th>
                                            <th class="text-center">{{ $t("global.Address") }}</th>
                                            <th class="text-center">{{ $t("global.Phone") }}</th>
                                            <th class="text-center">{{ $t("global.Action") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="virtualStocks.length">
                                        <tr v-for="(item, index) in virtualStocks" :key="item.id">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td class="text-center">{{ item.name }}</td>
                                            <td class="text-center">{{ item.address }}</td>
                                            <td class="text-center">{{ item.phone }}</td>
                                            <td class="text-center">
                                                <router-link :to="{
                                                    name: 'showVirtualStock',
                                                    params: { id: item.id },
                                                }" v-if="permission.includes('virtualStock edit')" class="btn btn-sm btn-info me-2">
                                                    <i class="fas fa-book-open"></i>
                                                    {{ $t("global.ShowDetails") }}
                                                </router-link>

                                                <router-link :to="{
                                                    name: 'createVirtualStock',
                                                    params: { id: item.id },
                                                }" v-if="permission.includes('virtualStock create')" class="btn btn-sm btn-warning me-2">
                                                    <i class="fas fa-truck"></i>
                                                    {{ $t("global.Create Product") }}
                                                </router-link>

                                                <router-link :to="{
                                                    name: 'editVirtualStock',
                                                    params: { id: item.id },
                                                }" v-if="permission.includes('virtualStock edit')" class="btn btn-sm btn-secondary me-2">
                                                    <i class="fas fa-truck"></i>
                                                    {{ $t("global.Create Alternative") }}
                                                </router-link>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr class="text-center">
                                            <th class="text-center" colspan="15">
                                                {{ $t("global.NoDataFound") }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
            <!-- start Pagination -->
            <Pagination :data="virtualStocksPaginate" @pagination-change-page="getVirtualStock">
                <template #prev-nav>
                    <span>&lt; {{ $t("global.Previous") }}</span>
                </template>
                <template #next-nav>
                    <span>{{ $t("global.Next") }} &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import { onMounted, inject, watch, ref, computed } from "vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
    name: "index",
    setup() {
        const emitter = inject("emitter");
        const { t } = useI18n({});

        // get packages
        let virtualStocks = ref([]);
        let virtualStocksPaginate = ref({});
        let loading = ref(false);
        const search = ref("");
        let store = useStore();

        let permission = computed(() => store.getters["authAdmin/permission"]);

        let getVirtualStock = (page = 1) => {
            loading.value = true;

            adminApi
            .get(`/v1/dashboard/virtualStock?page=${page}&search=${search.value}`)
            .then((res) => {
                let l = res.data.data;
                virtualStocksPaginate.value = l.virtualStocks;
                virtualStocks.value = l.virtualStocks.data;
            })
            .catch((err) => {
                console.log(err.response.data);
                this.errors = err.response.data.errors;
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ...',
                    text: `يوجد خطأ..!!`,
                });
            })
            .finally(() => {
                loading.value = false;
            });
        };

        onMounted(() => {
            getVirtualStock();
        });

        emitter.on("get_lang", () => {
            getVirtualStock(search.value);
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getVirtualStock();
            }
        });

        function deleteVirtualStock(id, index) {
            Swal.fire({
                title: `${t("global.AreYouSureDelete")}`,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: t("global.Yeas"),
                cancelButtonText: t("global.No"),
            })
            .then((result) => {
                if (result.isConfirmed) {
                    adminApi
                    .delete(`/v1/dashboard/virtualStock/${id}`)
                    .then((res) => {
                        virtualStocks.value.splice(index, index + 1);

                        Swal.fire({
                            icon: "success",
                            title: `${t("global.DeletedSuccessfully")}`,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: `${t("global.ThereIsAnErrorInTheSystem")}`,
                            text: `${t("global.YouCanNotDelete")}`,
                        });
                    });
                }
            });
        }

        return {
            getVirtualStock,
            loading,
            permission,
            search,
            deleteVirtualStock,
            virtualStocksPaginate,
            virtualStocks,
        };
    },
    data() {
        return {
            locale: localStorage.getItem("langAdmin"),
        };
    },
};
</script>

<style scoped>
.card {
    position: relative;
}

.btn-custom {
    width: 30%;
}

.custom {
    border: 1px solid #d7d7d7;
    padding: 2px;
    border-radius: 5px;
    width: 35%;
}

.btn {
    color: #fff;
}

.hover:hover {
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}

.custom-img {
    width: 100px;
}
</style>
