<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.totalPurchaseReport') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.totalPurchaseReport') }}</li>
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
                                        <form @submit.prevent="getIncome" class="needs-validation">
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label >{{$t('global.FromDate')}}</label>
                                                    <input type="date" class="form-control date-input" v-model="fromDate">
                                                </div>
                                                <div class="col-md-3">
                                                    <label >{{$t('global.ToDate')}}</label>
                                                    <input type="date" class="form-control date-input" v-model="toDate">
                                                </div>
                                                <div class="col-md-3 mt-4">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Search')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2 row justify-content-end"></div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">{{ $t('global.Supplier') }}</th>
                                            <th class="text-center">{{ $t('global.Store') }}</th>
                                            <th class="text-center">{{ $t('global.Notes') }}</th>
                                            <th class="text-center">{{ $t('global.TotalPrice') }}</th>
                                            <th class="text-center">{{ $t('global.Date') }}</th>
                                            <th class="text-center">{{ $t('global.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="purchases.length">
                                        <tr v-for="(item,index) in purchases"  :key="item.id">
                                            <td class="text-center">{{ 1 + index }}</td>
                                            <td class="text-center">{{ item.supplier.name }}</td>
                                            <td class="text-center">{{ item.store.name }}</td>
                                            <td class="text-center">{{ item.note }}</td>
                                            <td class="text-center">{{ item.total_price }}</td>
                                            <td class="text-center">{{  dateFormat(item.created_at) }}</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0);"
                                                   class="btn btn-sm btn-info me-2" data-bs-toggle="modal"
                                                   :data-bs-target="'#edit-category'+item.id">
                                                    <i class="fas fa-book-open"></i> {{$t('global.Show')}}
                                                </a>
                                            </td>
                                            <!-- Edit Modal -->
                                            <div class="modal fade custom-modal" :id="'edit-category'+item.id">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" id="print">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                {{ $t('global.InvoiceDetails') }}</h4>
                                                            <button :id="'close-'+item.id" type="button" class="close print-button" data-bs-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <div class="modal-body row" >
                                                            <div class="card bg-white projects-card">
                                                                <div class="card-body pt-0">
                                                                    <div class="tab-content pt-0">
                                                                        <div role="tabpanel" :id="'tab-4-'+item.id" class="tab-pane fade active show">
                                                                            <loader v-if="loading"/>
                                                                            <div class="row justify-content-between">
                                                                                <div class="col-5">
                                                                                    <button @click="printData(item.id)" class="btn btn-secondary print-button head-button">
                                                                                        {{$t('global.Print')}}
                                                                                        <i class="fa fa-print"></i>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="col-5 row justify-content-end">
                                                                                    <router-link @click="close(item.id)" v-if="permission.includes('PurchaseInvoice edit') && item.is_received == 0"
                                                                                        :to="{name: 'editPurchaseInvoice', params: {lang: locale || 'ar',id:item.id}}"
                                                                                        class="btn btn-sm btn-success me-2 head-button">
                                                                                        <i class="far fa-edit"></i> {{$t('global.Edit')}}
                                                                                    </router-link>
                                                                                </div>
                                                                            </div>
                                                                            <div class="table-responsive" :id="'printData-'+item.id">
                                                                                <div class="head-data row">
                                                                                    <div class="col-md-6 invoice-head">
                                                                                        <h5>{{$t('global.InvoiceNumber')}} : {{item.id}}</h5>
                                                                                    </div>
                                                                                    <div class="col-md-6 image-div">
                                                                                        <img src="/web/img/logo.png" alt="Logo">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <p>{{$t('global.DateOrder')}} : {{dateFormat(item.created_at)}}</p>
                                                                                        <p>{{$t('global.Supplier')}} : {{ item.supplier.name_supplier }}</p>
                                                                                        <p>{{$t('global.TotalPriceBeforeDiscount')}} : {{ item.price }} ??.??</p>
                                                                                        <p>{{$t('global.discountPercentage')}} : {{ item.discount_percentage }} %</p>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <p>{{$t('global.discountValue')}} : {{ item.discount_value }} ??.??</p>
                                                                                        <p>{{$t('global.otherDiscounts')}} : {{ item.other_discounts }} ??.??</p>
                                                                                        <p>{{$t('global.transferPrice')}} : {{ item.transfer_price }} ??.??</p>
                                                                                        <p>{{$t('global.TotalPriceAfterDiscount')}} : {{ item.total_price }} ??.??</p>
                                                                                    </div>
                                                                                </div>
                                                                                <table class="table table-center table-hover mb-0 datatable">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>#</th>
                                                                                            <th>{{ $t('global.Products') }}</th>
                                                                                            <th>{{ $t('global.Quantity') }}</th>
                                                                                            <th>{{ $t('global.Price') }}</th>
                                                                                            <th>{{ $t('global.expiryDate') }}</th>
                                                                                            <th>{{ $t('global.TotalPrice') }}</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <div v-if="item.purchase_products">
                                                                                            <tr v-for="(it,index) in item.purchase_products" :key="it.id">
                                                                                                <td>{{ totalPurchase + 1 }}</td>
                                                                                                <td>{{ it.product.name }}</td>
                                                                                                <td>{{ it.quantity }} ( {{it.product.main_measurement_unit.name}} )</td>
                                                                                                <td>{{ it.price_after_discount }}</td>
                                                                                                <td>{{ it.expiry_date }}</td>
                                                                                                <td>{{ it.quantity * it.price_after_discount }}</td>
                                                                                            </tr>
                                                                                        </div>
                                                                                        <div v-else>
                                                                                            <tr>
                                                                                                <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                                                                            </tr>
                                                                                        </div>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Edit Modal -->
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- start Pagination -->
            <Pagination :limit="2" :data="purchasesPaginate" @pagination-change-page="getIncome">
                <template #prev-nav>
                    <span>&lt; {{$t('global.Previous')}}</span>
                </template>
                <template #next-nav>
                    <span>{{$t('global.Next')}} &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
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
        let purchases = ref([]);
        let fromDate = ref('');
        let toDate = ref('');
        let purchase_id = ref('');
        let loading = ref(false);
        const search = ref('');
        let purchasesPaginate = ref({});

        let getIncome = (page = 1) => {
            loading.value = true;
            if (!fromDate.value){
                fromDate.value = new Date().toISOString().split('T')[0];
            }
            if (!toDate.value){
                toDate.value = new Date().toISOString().split('T')[0];
            }

            adminApi.get(`/v1/dashboard/purchaseInvoice?page=${page}&from_date=${fromDate.value}&to_date=${toDate.value}`)
            .then((res) => {
                let l = res.data.data;
                purchasesPaginate.value = l.purchases;
                purchases.value = l.purchases.data;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
        }

        onMounted(() => {
            getIncome();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getIncome();
            }
        });


        let close=(id)=>{
            document.getElementById('close-'+id).click();
        }

        let dateFormat = (item) => {
            return new Date(item).toDateString();
        }

        let printData = (id) => {
            let printContents = document.getElementById('printData-'+id).innerHTML;
            let originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        return {purchasesPaginate,search,permission,purchase_id,fromDate,toDate,purchases, loading,dateFormat,printData, getIncome,close};

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
