<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">
            <SettingsForm :dealSettings="dealSettings" @loading="loading = $event" />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.Prices") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t("dashboard.Dashboard") }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t("global.Prices") }}</li>
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
                                    <div class="col-md-4 col-sm-12">
                                        {{ $t("global.Search") }}:
                                        <input type="search" v-model="search" class="form-control"/>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        {{ $t("global.Filter Best Offers") }}:
                                        <select v-model="filter_best_offers" class="form-control" @change="getPrice">
                                            <option value=""></option>
                                            <option value="best_offers">{{$t('global.Best Offers')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-12 row justify-content-end">
                                        <button
                                            data-toggle="modal"
                                            data-target="#settingsFormModal"
                                            v-if="permission.includes('product edit')"
                                            class="btn btn-custom btn-warning"
                                            >
                                            {{ $t("global.Best Offers") }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">{{ $t("global.Product") }}</th>
                                        <th class="text-center">{{ $t("global.Supplier") }}</th>
                                        <th class="text-center">{{ $t("global.Quantity") }}</th>
                                        <th class="text-center">{{ $t("global.Pharmacy Price") }}</th>
                                        <th class="text-center">{{ $t("global.Public Price") }}</th>
                                        <th class="text-center">{{ $t("global.Client Discount") }}</th>
                                        <th class="text-center">{{ $t("global.Kayan Discount") }}</th>
                                        <th class="text-center">{{ $t("global.Kayan Profit") }}</th>
                                        <th class="text-center">{{ $t("global.Best Offer") }}</th>
                                        <th class="text-center">{{ $t("global.Action") }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="prices.length">
                                    <tr v-for="(item,index) in prices"  :key="item.id">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td class="text-center">{{ item.product.nameAr }}</td>
                                        <td class="text-center">{{ item.supplier.name }}</td>
                                        <td class="text-center">{{ item.quantity }}</td>
                                        <td class="text-center">{{ item.pharmacyPrice }}</td>
                                        <td class="text-center">{{ item.publicPrice }}</td>
                                        <td class="text-center">{{ item.clientDiscount }}</td>
                                        <td class="text-center">{{ item.kayanDiscount }}</td>
                                        <td class="text-center">{{ item.kayanProfit }}</td>
                                        <td class="text-center"><button @click="markProductAsBestOffer(item.id)" :class="['btn btn-sm' , item.best_offer == 1 ? 'btn-primary':'btn-secondary']"><i class="fa fa-star"></i></button></td>
                                        <td class="text-center">
                                            <router-link
                                                :to="{name: 'editPrice',params:{id:item.id}}"
                                                v-if="permission.includes('product edit')"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>
                                            <a href="#" @click="deletePrice(item.id,index)" v-if="permission.includes('product delete')"
                                                data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="10">{{ $t("global.NoDataFound") }}</th>
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
            <Pagination :data="pricesPaginate" @pagination-change-page="getPrice">
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
import SettingsForm from "./settingsForm.vue";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
    name: "index",
    components: {
        SettingsForm,
    },
    setup() {

        // get packages
        let prices = ref([]);
        let {t} = useI18n();
        let pricesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        const filter_best_offers = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getPrice = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/price?page=${page}&search=${search.value}&filter_best_offers=${filter_best_offers.value}`)
                .then((res) => {
                    let l = res.data.data;
                    pricesPaginate.value = l.prices;
                    prices.value = l.prices.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getPrice();
        });


        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getPrice();
            }
        });

        const markProductAsBestOffer = async(id)=>{
            adminApi.post(`/v1/dashboard/price/markProductAsBestOffer`,{product_id:id}).then(()=>{
                getPrice();
            }).catch((e) => {
                if(e.response.data.limit){
                    notify({
                        title: `${t('global.The Maximum limit of best offers is ')} ${e.response.data.limit}`,
                        type: "error",
                        duration: 5000,
                        speed: 2000,
                    });
                }
            })
        }


        function deletePrice(id, index) {
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

                    adminApi.delete(`/v1/dashboard/price/${id}`)
                    .then((res) => {
                        prices.value.splice(index, 1);

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

        // function activationPrice(id, active,index) {
        //     Swal.fire({
        //         title: `${active ? 'هل انت متاكد من ايقاف التفعيل ؟' : 'هل انت متاكد من التفعيل ؟'} `,
        //         text: `لن تتمكن من التراجع عن هذا!`,
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'نعم',
        //         cancelButtonText: 'لا'
        //     }).then((result) => {
        //         if (result.isConfirmed) {

        //             adminApi.get(`/v1/dashboard/activationPrice/${id}`)
        //                 .then((res) => {
        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: `${active ? 'تم التفعيل بنجاح' :'تم ايقاف التفعيل بنجاح'}`,
        //                         showConfirmButton: false,
        //                         timer: 1500
        //                     });
        //                     prices.value[index]['status'] =  active ? 0:1
        //                 })
        //                 .catch((err) => {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: `يوجد خطا`,
        //                         text: `يوجد خطا في النظام!`,
        //                     });
        //                 });
        //         }
        //     });
        // }



        return {getPrice,filter_best_offers, loading,permission, search, deletePrice, pricesPaginate,prices,markProductAsBestOffer};

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
