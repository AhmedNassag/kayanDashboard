<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.Kayan Prices") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t("dashboard.Dashboard") }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t("global.Kayan Prices") }}</li>
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
                                        <!-- v-if="permission.includes('kayanProduct create')" -->
                                        <router-link
                                           :to="{name: 'createKayanPrice'}"
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
                                        <th>#</th>
                                        <th class="text-center">{{ $t("global.Product Name") }}</th>
                                        <th class="text-center">{{ $t("global.MainCategory") }}</th>
                                        <th class="text-center">{{ $t("global.SubCategory") }}</th>
                                        <th class="text-center">{{ $t("global.Company") }}</th>
                                        <th class="text-center">{{ $t("global.Supplier") }}</th>
                                        <th class="text-center">{{ $t("global.Product Price") }}</th>
                                        <th class="text-center">{{ $t("global.Product Discount") }}</th>
                                        <th class="text-center">{{ $t("global.Pharmacy Price") }}</th>
                                        <th class="text-center">{{ $t("global.Public Price") }}</th>
                                        <th class="text-center">{{ $t("global.Collection Price") }}</th>
                                        <th class="text-center">{{ $t("global.Partial Price") }}</th>
                                        <th class="text-center">{{ $t("global.Destribution Price") }}</th>
                                        <th class="text-center">{{ $t("global.Average Price") }}</th>
                                        <th class="text-center">{{ $t("global.Kayan Collection Profit") }}</th>
                                        <th class="text-center">{{ $t("global.Kayan Partial Profit") }}</th>
                                        <th class="text-center">{{ $t("global.Kayan Destribution Profit") }}</th>
                                        <th class="text-center">{{ $t("global.Kayan Collection Profit Percentage") }}</th>
                                        <th class="text-center">{{ $t("global.Kayan Partial Profit Percentage") }}</th>
                                        <th class="text-center">{{ $t("global.Kayan Destribution Profit Percentage") }}</th>
                                        <th class="text-center">{{ $t("global.Maximum Limit") }}</th>
                                        <th class="text-center">{{ $t("global.ReOrder Limit") }}</th>
                                        <th class="text-center">{{ $t("global.Action") }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="kayanPrices.length">
                                    <tr v-for="(item,index) in kayanPrices" :key="item.id">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td class="text-center">{{ item.productName.nameAr }}</td>
                                        <td class="text-center">{{ item.category.name }}</td>
                                        <td class="text-center">{{ item.sub_category.name }}</td>
                                        <td class="text-center" v-if="item.company_id">{{ item.company.name }}</td>
                                        <td class="text-center" v-else>---</td>
                                        <td class="text-center" v-if="item.supplier_id">{{ item.supplier.name }}</td>
                                        <td class="text-center" v-else>---</td>
                                        <td class="text-center">{{ item.productPrice }}</td>
                                        <td class="text-center">{{ item.productDiscount }}</td>
                                        <td class="text-center">{{ item.pharmacyPrice }}</td>
                                        <td class="text-center">{{ item.publicPrice }}</td>
                                        <td class="text-center">{{ item.collectionPrice }}</td>
                                        <td class="text-center">{{ item.partialPrice }}</td>
                                        <td class="text-center">{{ item.destributionPrice }}</td>
                                        <td class="text-center">{{ item.averagePrice }}</td>
                                        <td class="text-center">{{ item.collectionKayanProfit }}</td>
                                        <td class="text-center">{{ item.partialKayanProfit }}</td>
                                        <td class="text-center">{{ item.destributionKayanProfit }}</td>
                                        <td class="text-center">{{ item.collectionPercentageKayanProfit }}</td>
                                        <td class="text-center">{{ item.partialPercentageKayanProfit }}</td>
                                        <td class="text-center">{{ item.destributionPercentageKayanProfit }}</td>
                                        <td class="text-center">{{ item.maximumLimit }}</td>
                                        <td class="text-center">{{ item.reOrderLimit }}</td>

                                        <td class="text-center">

                                            <router-link
                                                :to="{name: 'editKayanPrice',params:{id:item.id}}"
                                                v-if="permission.includes('kayanPrice edit')"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>

                                            <a
                                                href="#"
                                                @click="deleteKayanPrice(item.id,index)"
                                                v-if="permission.includes('kayanPrice delete')"
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
            <Pagination :data="kayanPricesPaginate" @pagination-change-page="getKayanPrice">
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
        let kayanPrices = ref([]);
        let kayanPricesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getKayanPrice = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/kayanPrice?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    kayanPricesPaginate.value = l.kayanPrices;
                    kayanPrices.value = l.kayanPrices.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getKayanPrice();
        });


        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getKayanPrice();
            }
        });


        function deleteKayanPrice(id, index) {
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

                    adminApi.delete(`/v1/dashboard/kayanPrice/${id}`)
                    .then((res) => {
                        kayanPrices.value.splice(index, 1);

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

        return {getKayanPrice, loading,permission, search, deleteKayanPrice, kayanPricesPaginate,kayanPrices};

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
