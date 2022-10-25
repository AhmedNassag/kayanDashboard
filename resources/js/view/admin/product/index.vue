<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.Products") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t("dashboard.Dashboard") }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t("global.Products") }}</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->
            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <loader v-if="loading"/>
                        <div class="card-body">
                            <div class="card-header pt-0">
                                <div class="row justify-content-between">
                                    <div class="col-5">
                                        {{ $t("global.Search") }}:
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>
                                    <div class="col-5 row justify-content-end">
                                        <router-link
                                            v-if="permission.includes('product create')"
                                           :to="{name: 'createProduct'}"
                                            class="btn btn-custom btn-warning">
                                            {{ $t("global.Add") }}
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">{{ $t("global.NameAr") }}</th>
                                        <th class="text-center">{{ $t("global.NameEn") }}</th>
                                        <th class="text-center"> {{ $t("global.Category") }}</th>
                                        <th class="text-center">{{ $t("global.SubCategory") }}</th>
                                        <th class="text-center">{{ $t("global.Pharmacist Form") }}</th>
                                        <th class="text-center">{{ $t("global.Tax") }}</th>
                                        <th class="text-center">{{ $t("global.Image") }}</th>
                                        <th class="text-center">{{ $t("global.Status") }}</th>
                                        <th class="text-center">{{ $t("global.Action") }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="products.length">
                                    <tr v-for="(item,index) in products" :key="item.id">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td class="text-center" v-if="item.nameAr">{{ item.nameAr }}</td>
                                        <td class="text-center" v-else>---</td>
                                        <td class="text-center" v-if="item.nameEn">{{ item.nameEn }}</td>
                                        <td class="text-center" v-else>---</td>
                                        <td class="text-center">{{ item.category.name }}</td>
                                        <td class="text-center">{{ item.sub_category.name }}</td>
                                        <td class="text-center">{{ item.pharmacist_form.name }}</td>
                                        <td class="text-center">{{ item.tax.name }}</td>
                                        <td class="text-center">
                                            <img
                                                :src="'/upload/product/' + item.image"
                                                :alt="item.name"
                                                class="custom-img"
                                            />
                                        </td>
                                        <td class="text-center">
                                            <a href="#" @click="activationProduct(item.id,item.status,index)">
                                                <span :class="[parseInt(item.status) ? 'text-success hover': 'text-danger hover']">{{
                                                        parseInt(item.status) ? 'تفعيل' : 'ايقاف تفعيل' }}</span>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <router-link
                                                :to="{name: 'editProduct',params:{id:item.id}}"
                                               v-if="permission.includes('product edit')"
                                               class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>
                                            <a href="#" @click="deleteProduct(item.id,index)"
                                                v-if="permission.includes('product delete')"
                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="7">{{ $t("global.NoDataFound") }}</th>
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
            <Pagination :data="productsPaginate" @pagination-change-page="getProduct">
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
import {onMounted, watch, ref,computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
    name: "index",
    setup() {

        // get packages
        let products = ref([]);
        let productsPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getProduct = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/product?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    productsPaginate.value = l.products;
                    products.value = l.products.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getProduct();
        });


        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getProduct();
            }
        });


        function deleteProduct(id, index) {
            Swal.fire({
                title: `هل انت متأكد من الحذف ؟`,
                text: `لن تتمكن من التراجع عن هذا!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم',
                cancelButtonText: 'لا'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/product/${id}`)
                        .then((res) => {
                            products.value.splice(index, 1);

                            Swal.fire({
                                icon: 'success',
                                title: `تم الحذف بنجاح`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `يوجد خطا في النظام...`,
                                text: `لا تستطيع الحذف !`,
                            });
                        });
                }
            });
        }

        function activationProduct(id, active,index) {
            Swal.fire({
                title: `${active ? 'هل انت متاكد من ايقاف التفعيل ؟' : 'هل انت متاكد من التفعيل ؟'} `,
                text: `لن تتمكن من التراجع عن هذا!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم',
                cancelButtonText: 'لا'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.get(`/v1/dashboard/activationProduct/${id}`)
                        .then((res) => {
                            Swal.fire({
                                icon: 'success',
                                title: `${active ? 'تم التفعيل بنجاح' :'تم ايقاف التفعيل بنجاح'}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            products.value[index]['status'] =  active ? 0:1
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `يوجد خطا`,
                                text: `يوجد خطا في النظام!`,
                            });
                        });
                }
            });
        }

        return {getProduct, loading,permission, search, deleteProduct, activationProduct, productsPaginate,products};

    },
    data() {
        return {
            locale: localStorage.getItem("langAdmin")
        }
    }
}
</script>

<style scoped>
.card {
    position: relative;
}

.btn-custom {
    width: 30%;
}

.custom {
    border: 1px solid #D7D7D7;
    padding: 2px;
    border-radius: 5px;
    width: 35%;
}

.btn {
    color: #fff;
}
.hover:hover{
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}

.custom-img {
    width: 100px;
}
</style>
