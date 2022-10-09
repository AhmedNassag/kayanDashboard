<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.Reports') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.Sale Reports') }}</li>
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
                                    <div class="col-12 row justify-content-end">

                                        <!--Start Sale Date Search Form-->
                                        <form @submit.prevent="getIncome" class="needs-validation">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label >{{$t('global.FromDate')}}</label>
                                                    <input type="date" class="form-control date-input" v-model="fromDate">
                                                </div>
                                                <div class="col-md-4">
                                                    <label >{{$t('global.ToDate')}}</label>
                                                    <input type="date" class="form-control date-input" v-model="toDate">
                                                </div>

                                                <div class="col-md-4">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Search')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!--End Sale Date Search Form-->


                                        <!--Start Product Search Form-->
                                        <form @submit.prevent="saleReportByProduct" class="needs-validation">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label for="validationCustom00">
                                                        {{ $t("global.Product Name") }}
                                                    </label>
                                                    <Select2 v-model="product_id" :options="products" :settings="{ width: '100%' }" />
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Search')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!--End Product Search Form-->

                                        <!--Start Category Search Form-->
                                        <form @submit.prevent="saleReportByCategory" class="needs-validation">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label for="validationCustom00">
                                                        {{ $t("global.MainCategory") }}
                                                    </label>
                                                    <Select2 v-model="category_id" :options="categories" :settings="{ width: '100%' }" />
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Search')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!--End Category Search Form-->

                                        <!--Start Return Date Search Form-->
                                        <form @submit.prevent="saleReportByReturn" class="needs-validation">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label >{{$t('global.FromDate')}}</label>
                                                    <input type="date" class="form-control date-input" v-model="dateFrom">
                                                </div>
                                                <div class="col-md-4">
                                                    <label >{{$t('global.ToDate')}}</label>
                                                    <input type="date" class="form-control date-input" v-model="dateTo">
                                                </div>

                                                <div class="col-md-4">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Search')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!--End Return Date Search Form-->

                                    </div>
                                    <!-- <div class="col-md-3">
                                        {{ $t('global.Search') }}:
                                        <input type="search" v-model="search" class="custom"/>
                                    </div> -->
                                </div>
                            </div>

                            <!--Start Sale By Date-->
                            <div class="table-responsive" v-if="sales.length">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{$t('global.InvoiceNumber')}}</th>
                                            <th class="text-center">{{ $t('global.Type') }}</th>
                                            <th class="text-center">{{ $t('global.Client') }}</th>
                                            <th class="text-center">{{ $t('global.Store') }}</th>
                                            <th class="text-center">{{ $t('global.Payment Method') }}</th>
                                            <th class="text-center">{{ $t('global.Notes') }}</th>
                                            <th class="text-center">{{ $t('global.TotalPrice') }}</th>
                                            <th class="text-center">{{ $t('global.Date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="sales.length">
                                        <tr v-for="(item, index) in sales" :key="item.id">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td class="text-center" v-if="item.type == 'Sale Invoice'">{{ $t('global.Sales Invoice') }}</td>
                                            <td class="text-center" v-else-if="item.type == 'Sale Return'">{{ $t('global.Return Invoice') }}</td>
                                            <td class="text-center" v-else-if="item.type == 'Prices Offer'">{{ $t('global.Prices Offer') }}</td>
                                            <td class="text-center" v-else>{{ $t('global.Prices List') }}</td>
                                            <td class="text-center">{{ item.client.user.name }}</td>
                                            <td class="text-center">{{ item.store.name }}</td>
                                            <td class="text-center" v-if="item.payment_method == 'Cach'">{{ $t('global.Cach') }}</td>
                                            <td class="text-center" v-else>{{ $t('global.Delay') }}</td>
                                            <td class="text-center">{{ item.note }}</td>
                                            <td class="text-center">{{ item.total_price }}</td>
                                            <td class="text-center">{{ dateFormat(item.created_at) }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                        </tr>
                                    </tbody>
                                    <!--Start Statistics-->
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ $t('global.Total') }}</th>
                                            <th class="text-center">{{ total }}</th>
                                        </tr>
                                    </thead>
                                    <!--End Statistics-->
                                </table>
                                <!-- start Pagination -->
                                <Pagination :data="salesPaginate" @pagination-change-page="getIncome">
                                    <template #prev-nav>
                                        <span>&lt; {{$t('global.Previous')}}</span>
                                    </template>
                                    <template #next-nav>
                                        <span>{{$t('global.Next')}} &gt;</span>
                                    </template>
                                </Pagination>
                                <!-- end Pagination -->
                            </div>
                            <!--End Sale By Date-->


                            <!--Start Sale By Product-->
                            <div class="table-responsive" v-if="saleProducts.length">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">{{ $t('global.Sale Type') }}</th>
                                            <th class="text-center">{{ $t('global.Sale Price') }}</th>
                                            <th class="text-center">{{ $t('global.Quantity') }}</th>
                                            <th class="text-center">{{ $t('global.Price Before Discount') }}</th>
                                            <th class="text-center">{{ $t('global.Price After Discount') }}</th>
                                            <th class="text-center">{{ $t('global.Date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="saleProducts.length">
                                        <tr v-for="(item, index) in saleProducts" :key="item.id">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td class="text-center">{{ item.sale.type }}</td>
                                            <td class="text-center">{{ item.sale.price }}</td>
                                            <td class="text-center">{{ item.quantity }}</td>
                                            <td class="text-center">{{ item.price_before_discount }}</td>
                                            <td class="text-center">{{ item.price_after_discount }}</td>
                                            <td class="text-center">{{ dateFormat(item.created_at) }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                        </tr>
                                    </tbody>
                                    <!--Start Statistics-->
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ $t('global.Total Quantity') }}</th>
                                            <th class="text-center">{{ totalQuantity }}</th>
                                        </tr>
                                    </thead>
                                    <!--End Statistics-->
                                </table>
                                <!-- start Pagination -->
                                <Pagination :data="saleProductsPaginate" @pagination-change-page="saleReportByProduct">
                                    <template #prev-nav>
                                        <span>&lt; {{$t('global.Previous')}}</span>
                                    </template>
                                    <template #next-nav>
                                        <span>{{$t('global.Next')}} &gt;</span>
                                    </template>
                                </Pagination>
                                <!-- end Pagination -->
                            </div>
                            <!--End Sale By Product-->


                            <!--Start Sale By Category-->
                            <div class="table-responsive" v-if="saleCategories.length">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">{{ $t('global.Sale Type') }}</th>
                                            <th class="text-center">{{ $t('global.Sale Price') }}</th>
                                            <th class="text-center">{{ $t('global.Quantity') }}</th>
                                            <th class="text-center">{{ $t('global.Price Before Discount') }}</th>
                                            <th class="text-center">{{ $t('global.Price After Discount') }}</th>
                                            <th class="text-center">{{ $t('global.Date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="saleCategories.length">
                                        <tr v-for="(item, index) in saleCategories" :key="item.id">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td class="text-center">{{ item.sale.type }}</td>
                                            <td class="text-center">{{ item.sale.price }}</td>
                                            <td class="text-center">{{ item.quantity }}</td>
                                            <td class="text-center">{{ item.price_before_discount }}</td>
                                            <td class="text-center">{{ item.price_after_discount }}</td>
                                            <td class="text-center">{{ dateFormat(item.created_at) }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                        </tr>
                                    </tbody>
                                    <!--Start Statistics-->
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ $t('global.Total Quantity') }}</th>
                                            <th class="text-center">{{ totalQuantity }}</th>
                                        </tr>
                                    </thead>
                                    <!--End Statistics-->
                                </table>
                                <!-- start Pagination -->
                                <Pagination :data="saleProductsPaginate" @pagination-change-page="saleReportByCategory">
                                    <template #prev-nav>
                                        <span>&lt; {{$t('global.Previous')}}</span>
                                    </template>
                                    <template #next-nav>
                                        <span>{{$t('global.Next')}} &gt;</span>
                                    </template>
                                </Pagination>
                                <!-- end Pagination -->
                            </div>
                            <!--End Sale By Category-->


                            <!--Start Sale Return By Date-->
                            <!-- <div class="table-responsive" v-if="saleReturns.length">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{$t('global.InvoiceNumber')}}</th>
                                            <th class="text-center">{{ $t('global.Type') }}</th>
                                            <th class="text-center">{{ $t('global.Client') }}</th>
                                            <th class="text-center">{{ $t('global.Store') }}</th>
                                            <th class="text-center">{{ $t('global.Payment Method') }}</th>
                                            <th class="text-center">{{ $t('global.Notes') }}</th>
                                            <th class="text-center">{{ $t('global.TotalPrice') }}</th>
                                            <th class="text-center">{{ $t('global.Date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="saleReturns.length">
                                        <tr v-for="(item, index) in saleReturns" :key="item.id">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td class="text-center" v-if="item.type == 'Sale Invoice'">{{ $t('global.Sales Invoice') }}</td>
                                            <td class="text-center" v-else-if="item.type == 'Sale Return'">{{ $t('global.Return Invoice') }}</td>
                                            <td class="text-center" v-else-if="item.type == 'Prices Offer'">{{ $t('global.Prices Offer') }}</td>
                                            <td class="text-center" v-else>{{ $t('global.Prices List') }}</td>
                                            <td class="text-center">{{ item.client.user.name }}</td>
                                            <td class="text-center">{{ item.store.name }}</td>
                                            <td class="text-center" v-if="item.payment_method == 'Cach'">{{ $t('global.Cach') }}</td>
                                            <td class="text-center" v-else>{{ $t('global.Delay') }}</td>
                                            <td class="text-center">{{ item.note }}</td>
                                            <td class="text-center">{{ item.total_price }}</td>
                                            <td class="text-center">{{ dateFormat(item.created_at) }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                        </tr>
                                    </tbody> -->
                                    <!--Start Statistics-->
                                    <!-- <thead>
                                        <tr>
                                            <th class="text-center">{{ $t('global.Total') }}</th>
                                            <th class="text-center">{{ totalReturnQuantity }}</th>
                                        </tr>
                                    </thead> -->
                                    <!--End Statistics-->
                                <!-- </table> -->
                                <!-- start Pagination -->
                                <!-- <Pagination :data="saleReturnsPaginate" @pagination-change-page="saleReportByReturn">
                                    <template #prev-nav>
                                        <span>&lt; {{$t('global.Previous')}}</span>
                                    </template>
                                    <template #next-nav>
                                        <span>{{$t('global.Next')}} &gt;</span>
                                    </template>
                                </Pagination> -->
                                <!-- end Pagination -->
                            <!-- </div> -->
                            <!--End Sale Return By Date-->

                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, inject, watch, ref, computed} from "vue";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";
import {useStore} from "vuex";

export default {
    name: "index",
    setup() {
        const {t} = useI18n({});
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);
        let sales = ref([]);
        let total = ref([]);
        let products = ref([]);
        let categories = ref([]);
        let saleProducts = ref([]);
        let saleCategories = ref([]);
        let saleReturns = ref([]);
        let totalQuantity = ref([]);
        let totalReturnQuantity = ref([]);
        let product_id = ref('');
        let category_id = ref('');
        let fromDate = ref('');
        let toDate = ref('');
        let dateFrom = ref('');
        let dateTo = ref('');
        let loading = ref(false);
        const search = ref('');
        let salesPaginate = ref({});
        let saleProductsPaginate = ref({});
        let saleCategoriesPaginate = ref({});
        let saleReturnsPaginate = ref({});

        let getIncome = (page = 1) => {
            loading.value = true;
            // if (!fromDate.value){
            //     fromDate.value = new Date().toISOString().split('T')[0];
            // }
            // if (!toDate.value){
            //     toDate.value = new Date().toISOString().split('T')[0];
            // }

            adminApi.get(`/v1/dashboard/saleReport?page=${page}&&from_date=${fromDate.value}&to_date=${toDate.value}&search=${search.value}`)
            .then((res) => {
                let l = res.data.data;
                salesPaginate.value = l.sales;
                sales.value = l.sales.data;
                total.value = l.total;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            });
        };
        let saleReportByProduct = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/saleReportByProduct?page=${page}&&product_id=${product_id.value}&search=${search.value}`)
            .then((res) => {
                let l = res.data.data;
                saleProductsPaginate.value = l.saleProducts;
                saleProducts.value = l.saleProducts.data;
                totalQuantity.value = l.totalQuantity;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            });
        };
        let saleReportByCategory = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/saleReportByCategory?page=${page}&&category_id=${category_id.value}&search=${search.value}`)
            .then((res) => {
                let l = res.data.data;
                saleCategoriesPaginate.value = l.saleCategories;
                saleCategories.value = l.saleCategories.data;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            });
        };
        let saleReportByReturn = (page = 1) => {
            loading.value = true;
            // if (!dateFrom.value){
            //     fromDate.value = new Date().toISOString().split('T')[0];
            // }
            // if (!dateTo.value){
            //     toDate.value = new Date().toISOString().split('T')[0];
            // }

            adminApi.get(`/v1/dashboard/saleReportByReturn?page=${page}&&date_from=${dateFrom.value}&date_to=${dateTo.value}&search=${search.value}`)
            .then((res) => {
                let l = res.data.data;
                saleReturnsPaginate.value = l.saleReturns;
                saleReturns.value = l.saleReturns.data;
                totalReturnQuantity.value = l.totalReturnQuantity;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            });
        };


        //
        let getProduct= () => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/saleReport/create`)
            .then((res) => {
                let l = res.data.data;
                products.value = l.products;
                categories.value = l.categories;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            })
        };
        //

        onMounted(() => {
            getProduct();
            getIncome();
            saleReportByProduct();
            saleReportByCategory();
            saleReportByReturn();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getIncome();
                saleReportByProduct();
                saleReportByCategory();
                saleReportByReturn();
            }
        });

        let close=(id)=>{
            document.getElementById('close-'+id).click();
        }

        let dateFormat = (item) => {
            return new Date(item).toDateString();
        }

        return {salesPaginate,saleProductsPaginate,saleCategoriesPaginate,saleReturnsPaginate,search,permission,fromDate,toDate,sales,total,loading,dateFormat,getProduct,products,product_id,close, getIncome,saleReportByProduct,saleReportByCategory,saleReportByReturn,saleProducts,totalQuantity,categories,category_id,saleCategories,saleReturns,totalReturnQuantity,dateFrom,dateTo};

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
    width: 100%;
}

.custom {
    border: 1px solid #D7D7D7;
    padding: 2px;
    border-radius: 5px;
}

.btn {
    color: #fff;
}

.hover:hover{
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}

.amount{
    background-color: #fcb00c;
    color: #000;
    padding: 10px;
}

.amount h5{
    font-weight: 700;
    text-align: center;
}

.submit-margin{
    margin-top: 38px !important;
}

.date-input{
    width: 175px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}

.head-table{
    background: #000;
}
.head-table h3{
    color: #ffc107;
    text-align: center;
}
.total-head{
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    background-color: #fcb00c !important;
    border-radius: 10px;
}
.custom-modal .close span {
    top: 0 !important;
}
.modal-dialog {
    max-width: 1000px !important;
}
.head-data-table{
    display: flex;
    width: 100%;
    justify-content: space-around;
}
.head-button{
    margin-top: 5px;
    width: 200px;
    margin-bottom: 5px;
}
/*======== print ===========*/

.head-data{
    margin: 0px 0 15px 0;
}
.image-div{
    display: flex;
    flex-direction: row-reverse;
}
.image-div img {
    width: 35%;
}
.invoice-head{
    display: flex;
    align-items: center;
}
</style>
