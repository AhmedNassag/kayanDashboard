<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">المنتجات</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    الرئيسية
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">المنتجات</li>
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
                                <div class="row justify-content-between mb-4">
                                    <div class="col-5">
                                        بحث :
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>
                                    <div class="col-5 text-center">
                                        <router-link
                                            v-if="permission.includes('product create')"
                                           :to="{name: 'createProduct'}"
                                            class="btn btn-custom btn-warning">
                                            أضف
                                        </router-link>
                                        <a
                                            class="btn btn-sm btn-secondary mx-2"
                                            @click.prevent="printSection()"
                                            ><i class="fa fa-print"></i> </a
                                        >
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="form-group col-4">
                                        <label for="">{{ $t('global.Filter By Category') }}</label>
                                        <select v-model="category_id" class="form-control" @change="getProduct">
                                            <option value="">{{ $t('global.All Categories') }}</option>
                                            <option :value="category.id" v-for="category in categories" :key="category.id">{{ category.name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="">{{ $t('global.Filter Products') }}</label>
                                        <select v-model="product_filter" class="form-control" @change="getProduct">
                                            <option value="">{{ $t('global.All Products') }}</option>
                                            <option value="most_seller">{{ $t('global.Most Seller') }}</option>
                                            <option value="least_seller">{{ $t('global.Least Seller') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="">{{ $t('global.Paginate Products') }}</label>
                                        <select v-model="pagination" class="form-control" @change="getProduct">
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="200">200</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0" id="div">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الصورة</th>
                                        <th>اسم المنتج بالعربية</th>
                                        <th>اسم المنتج بالإنجليزية</th>
                                        <th>الفئه</th>
                                        <th>الكود الدوائي</th>
                                        <th>الكمية المباعة</th>
                                        <th>الحاله</th>
                                        <th>الاجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="products.length">
                                    <tr v-for="(item,index) in products"  :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>
                                            <img
                                                :src="item.image ? '/upload/product/' + item.image : '/admin/img/Logo Dashboard.png'"
                                                :alt="item.name"
                                                class="custom-img"
                                            />
                                        </td>
                                        <td>{{ item.nameAr }}</td>
                                        <td>{{ item.nameEn }}</td>
                                        <td>{{ item.category ? item.category.name:'' }}</td>
                                        <td>{{ item.product_code  }}</td>
                                        <td>{{ item.sold_quantity??0  }}</td>

                                        <td>
                                            <a href="#" @click="activationProduct(item.id,item.status,index)">
                                                <span :class="[parseInt(item.status) ? 'text-success hover': 'text-danger hover']">{{
                                                        parseInt(item.status) ? 'تفعيل' : 'ايقاف تفعيل' }}</span>
                                            </a>
                                        </td>
                                        <td>
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
                                            <th class="text-center" colspan="7">لا يوجد بيانات</th>
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
            <Pagination :limit="2" :data="productsPaginate" @pagination-change-page="getProduct">
                <template #prev-nav>
                    <span>&lt; السابق</span>
                </template>
                <template #next-nav>
                    <span>التالي &gt;</span>
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
        let categories = ref({});
        let loading = ref(false);
        const search = ref('');
        const pagination = ref(25);
        const product_filter = ref('');
        const category_id = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getProduct = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/product?page=${page}&search=${search.value}&pagination=${pagination.value}&category_id=${category_id.value}&product_filter=${product_filter.value}`)
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
            getCategories();
        });


        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getProduct();
            }
        });
        const  getCategories = () => {
        adminApi
            .get(`/v1/dashboard/getCategories`)
            .then((res) => {
                categories.value =res.data.data.categories ;
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
    }



        function deleteProduct(id, index) {
            Swal.fire({
                title: `هل تريد هذف هذا العنصر ؟ `,
                text: `لن تتمكن من التراجع عن هذا`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
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
                                title: `يوجد خطا`,
                                text: `يوجد خطا في النظام!`,
                            });
                        });
                }
            });
        }

        function activationProduct(id, active,index) {
            Swal.fire({
                title: `${active ? 'هل انت متاكد من ايقاف التفعيل ؟' : 'هل انت متاكد من التفعيل  ؟'} `,
                text: `لم تتمكن من التراجع عن هذا`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
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
        let printSection = () => {
          $("#div").printThis({});
        }

        return {getProduct, loading,permission,product_filter,pagination,category_id,printSection, search, deleteProduct, activationProduct, productsPaginate,products , categories};

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
