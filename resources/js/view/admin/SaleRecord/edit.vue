<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.saleRecords')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexSaleRecord'}">{{$t('global.saleRecords')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.Edit')}}</li>
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
                            <div class="card-header pt-0 mb-4">
                                <router-link
                                    :to="{name: 'indexSaleRecord'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['stock_id']">{{ errors['stock_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['client_id']">{{ errors['client_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['sale_id']">{{ errors['sale_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['notes_received']">{{ errors['notes_received'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['notes_return']">{{ errors['notes_return'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.product_id']">{{ errors['product.0.product_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.product_status_id']">{{ errors['product.0.product_status_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.quantity_received']">{{ errors['product.0.quantity_received'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.return_quantity']">{{ errors['product.0.return_quantity'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.note']">{{ errors['product.0.note'][0] }}<br /> </div>

                                    <form @submit.prevent="storeJob" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-3 mb-3">
                                                <label>{{$t('global.Store')}}</label>
                                                <input type="text" disabled class="form-control"
                                                       v-model="storeName"
                                                >
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label>{{$t('global.Client')}}</label>
                                                <input type="text" disabled class="form-control" v-model="clientName">
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label>{{$t('global.NotesInvoice')}}</label>
                                                <input type="text" disabled class="form-control" v-model="notesInvoice">
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label>{{$t('global.TotalQuantityPrice')}}</label>
                                                <input type="text" disabled class="form-control" v-model="quantityInvoice">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{$t('global.NotesReceived')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="data.notes_received" :class="['form-control text-height']" :placeholder="$t('global.NotesReceived')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                            </div>

                                            <div class="col-md-6 mb-3" v-if="data.return">
                                                <label>{{$t('global.NotesReturn')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="data.notes_return" :class="['form-control text-height']" :placeholder="$t('global.NotesReturn')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                            </div>

                                            <div class="col-md-12 mb-12">
                                                <div class="row account">
                                                    <div class="col-md-12 mb-12 head-account">
                                                        <h3>{{ $t('global.Products') }}</h3>
                                                    </div>
                                                    <div v-for="(it,index) in data.product" :key="it.id" class="col-md-12 mb-12 body-account row">

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.ProductName')}}</label>
                                                            <input type="text" disabled class="form-control" v-model="data.product[index].productName">
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.RequiredQuantity')}} ( {{data.product[index].mainUnitMeasurement}} )</label>
                                                            <input type="text" disabled class="form-control" v-model="data.product[index].RequiredQuantity">
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.mainUnitMeasurement')}}</label>
                                                            <input type="text" disabled class="form-control"
                                                                   v-model="data.product[index].mainUnitMeasurement"
                                                            >
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.productStatus') }}</label>
                                                            <select  v-model="data.product[index].product_status_id " :class="['form-select',{'is-invalid':v$.product[index].product_status_id.$error,'is-valid':!v$.product[index].product_status_id.$invalid}]">
                                                                <option v-for="status in productStatuses" :key="status.id" :value="status.id">{{status.name}}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].product_status_id.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.quantityReceived')}}</label>
                                                            <input type="number" class="form-control"
                                                                   @input="quantityReceived(index)"
                                                                   v-model.number="v$.product[index].quantity_received.$model"
                                                                   :placeholder="$t('global.quantityReceived')"
                                                                   :class="{'is-invalid':v$.product[index].quantity_received.$error,'is-valid':!v$.product[index].quantity_received.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].quantity_received.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].quantity_received.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.returnQuantity')}}</label>
                                                            <input type="number" class="form-control"
                                                                   @input="returnQuantity(index)"
                                                                   v-model.number="v$.product[index].return_quantity.$model"
                                                                   :placeholder="$t('global.returnQuantity')"
                                                                   :class="{'is-invalid':v$.product[index].return_quantity.$error,'is-valid':!v$.product[index].return_quantity.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].return_quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].return_quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3" v-if="data.product[index].return_quantity">
                                                            <label>{{$t('global.ReasonForReturn')}}</label>
                                                            <textarea rows="4" cols="5" v-model.trim="data.product[index].note" :class="['form-control text-height']"></textarea>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-5">
                                                <div class="table-responsive">
                                                    <table class="table table-center table-hover mb-0 datatable">
                                                        <thead class="account">
                                                            <tr class="text-center">
                                                                <th>{{ $t('global.QuantityOfProductsReceived') }}</th>
                                                                <th>{{ $t('global.QuantityOfReturnedProducts') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="text-center">
                                                                <td>{{data.received}}</td>
                                                                <td>{{data.return}}</td>
                                                            </tr>
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-primary mt-2" type="submit">{{$t('global.Submit')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
        </div>
    </div>
</template>

<script>
import {computed, onMounted, reactive,toRefs,ref,watch} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "edit",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const {id} = toRefs(props);
        const {t} = useI18n({});
        let loading = ref(false);
        let productValidation = ref([]);
        let productStatuses = ref([]);
        let storeName =ref('');
        let clientName =ref('');
        let notesInvoice =ref('');
        let quantityInvoice =ref('');

        let getData = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/saleRecord/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    storeName.value = l.sale.store.name;
                    clientName.value = l.sale.client.user.name;
                    notesInvoice.value = l.sale.note;
                    quantityInvoice.value = l.sale.quantity;
                    productStatuses.value = l.productStatuses;
                    addJob.data.stock_id = l.sale.stock_id;
                    addJob.data.client_id = l.sale.client_id;
                    addJob.data.sale_id = id;
                    addJob.data.received = 0;
                    addJob.data.return = 0;
                    addJob.data.notes_received = l.sale.sale_record.note ?? '';
                    addJob.data.notes_return = l.sale.sale_returns.note ?? '';

                    l.sale.sale_products.forEach((el,index)=>{
                        let quantity_received = 0;
                        let return_quantity = 0;
                        let noteProduct = '';
                        let product_status_id=''

                        l.sale.sale_record.store_products.forEach((elm)=>{
                            if (elm.product_id == el.product_id){
                                quantity_received = elm.quantity
                                product_status_id = elm.product_status_id
                            }
                        });
                        l.sale.sale_returns.return_products.forEach((elmen)=>{
                            if (elmen.product_id == el.product_id){
                                return_quantity = elmen.quantity
                                noteProduct = elmen.note
                            }
                        });

                        addJob.data.product.push({
                            productName: el.product.name,
                            RequiredQuantity: el.quantity,
                            mainUnitMeasurement: el.product.main_measurement_unit.name,
                            // subUnitMeasurement: el.product.sub_measurement_unit.name,
                            // count_unit: el.count_unit,
                            // production_date: el.production_date,
                            // expiry_date: el.expiry_date,
                            product_id: el.product_id,
                            quantity_received: quantity_received,
                            return_quantity: return_quantity,
                            note: noteProduct,
                            product_status_id: product_status_id,
                            sale_product_id:el.id,

                        });

                        addJob.data.received += quantity_received;
                        addJob.data.return += return_quantity;

                        productValidation.value.push({
                            quantity_received: {
                                required,
                                numeric
                            },
                            return_quantity: {
                                required,
                                numeric
                            },
                            product_status_id: {
                                required
                            },
                        });
                    });
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getData();
        });

        let addJob =  reactive({
            data:{
                product:[],
                notes_received:'',
                notes_return:'',
                stock_id:'',
                client_id:'',
                sale_id:'',
                received : 0,
                return : 0,
            },
            products:[]
        });

        const rules = computed(() => {
            return {
                product:[
                    ...productValidation.value
                ]
            }
        });

        let quantityReceived = (index) =>{
            addJob.data.product[index].return_quantity = addJob.data.product[index].RequiredQuantity - addJob.data.product[index].quantity_received;
            totalQantity();
            return  addJob.data.product[index].return_quantity
        }

        let returnQuantity = (index) =>{
            addJob.data.product[index].quantity_received =   addJob.data.product[index].RequiredQuantity - addJob.data.product[index].return_quantity;
            totalQantity();
            return addJob.data.product[index].quantity_received
        }

        let totalQantity = () => {
            addJob.data.received = 0;
            addJob.data.return = 0;
            addJob.data.product.forEach((el) => {
                addJob.data.received += el.quantity_received;
                addJob.data.return += el.return_quantity;
            });
        }

        const v$ = useVuelidate(rules,addJob.data);

        return {t,notesInvoice,productStatuses,quantityReceived,returnQuantity,quantityInvoice,clientName,storeName,loading,...toRefs(addJob),v$,productValidation};
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/saleRecord/${this.id}`,this.data)
                .then((res) => {

                    notify({
                        title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
                        type: "success",
                        duration: 5000,
                        speed: 2000
                    });
                })
                .catch((err) => {
                    console.log(err.response.data);
                    // this.errors = err.response.data.errors;
                })
                .finally(() => {
                    this.loading = false;
                });

            }
        }
    }
}
</script>

<style scoped>
.coustom-select {
    height: 100px;
}
.card{
    position: relative;
}
.account{
    background-color: #fcb00c;
    color: #000000 !important;
    border-radius: 5px;
}
.head-account{
    display: flex;
    justify-content: center;
}
.head-account h3{
    color: #000000 !important;
    font-weight: bold;
}
.body-account{
    border-top: 3px solid #000000;
    margin: 0 !important;
}
.text-height{
    height: 46px !important;
}
.error-amount{
    display: flex;
    justify-content: center;
    color: red;
    margin: 10px;
}
</style>
