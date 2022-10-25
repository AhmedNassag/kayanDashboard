<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.examinationRecords')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexExaminationRecord'}">{{$t('global.examinationRecords')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.ReceiptProduct')}}</li>
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
                                    :to="{name: 'indexExaminationRecord'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['stock_id']">{{ errors['stock_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['supplier_id']">{{ errors['supplier_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['purchase_id']">{{ errors['purchase_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['notes_received']">{{ errors['notes_received'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['notes_return']">{{ errors['notes_return'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.product_id']">{{ errors['product.0.product_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.product_status_id']">{{ errors['product.0.product_status_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.quantity_received']">{{ errors['product.0.quantity_received'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.return_quantity']">{{ errors['product.0.return_quantity'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.count_unit']">{{ errors['product.0.count_unit'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.expiry_date']">{{ errors['product.0.expiry_date'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.production_date']">{{ errors['product.0.production_date'][0] }}<br /> </div>
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
                                                <label>{{$t('global.Supplier')}}</label>
                                                <input type="text" disabled class="form-control"
                                                       v-model="supplierName"
                                                >
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label>{{$t('global.NotesInvoice')}}</label>
                                                <input type="text" disabled class="form-control"
                                                       v-model="notesInvoice"
                                                >
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label>{{$t('global.TotalQuantityPrice')}}</label>
                                                <input type="text" disabled class="form-control"
                                                       v-model="quantityInvoice"
                                                >
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
                                                            <input type="text" disabled class="form-control"
                                                                   v-model="data.product[index].productName"
                                                            >
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.RequiredQuantity')}} ( {{data.product[index].mainUnitMeasurement}} )</label>
                                                            <input type="text" disabled class="form-control"
                                                                   v-model="data.product[index].RequiredQuantity"
                                                            >
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.productionDate')}}</label>
                                                            <input type="date" class="form-control" disabled
                                                                   v-model="data.product[index].production_date"
                                                            >
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.expiryDate')}}</label>
                                                            <input type="date" class="form-control" disabled
                                                                   v-model="data.product[index].expiry_date"
                                                            >
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.mainUnitMeasurement')}}</label>
                                                            <input type="text" disabled class="form-control"
                                                                   v-model="data.product[index].mainUnitMeasurement"
                                                            >
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.countUnits')}}</label>
                                                            <input type="text" disabled class="form-control"
                                                                   v-model="data.product[index].count_unit"
                                                            >
                                                        </div>

                                                        <!-- <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.subUnitMeasurement')}}</label>
                                                            <input type="text" disabled class="form-control"
                                                                   v-model="data.product[index].subUnitMeasurement"
                                                            >
                                                        </div> -->

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.productStatus') }}</label>


                                                            <!-- <Select2 v-model="data.product[index].product_status_id" :options="productStatuses" :settings="{ width: '100%' }" /> -->
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
                                                        <thead class="account2">
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
import {computed, onMounted, reactive,toRefs,inject,ref,watch} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, between, maxLength, alpha, integer, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "create",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const {t} = useI18n({});
        const {id} = toRefs(props);
        let loading = ref(false);
        let productValidation = ref([]);
        let productStatuses = ref([]);
        let storeName =ref('');
        let supplierName =ref('');
        let notesInvoice =ref('');
        let quantityInvoice =ref('');

        let getData = () => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/examinationRecord/${id.value}`)
                .then((res) => {
                    let l = res.data.data;
                    storeName.value = l.purchase.store.name;
                    supplierName.value = l.purchase.supplier.name;
                    notesInvoice.value = l.purchase.note;
                    quantityInvoice.value = l.purchase.quantity;
                    productStatuses.value = l.productStatuses;
                    addJob.data.stock_id = l.purchase.stock_id;
                    addJob.data.supplier_id = l.purchase.supplier_id;
                    addJob.data.purchase_id = id;
                    addJob.data.received = 0;
                    addJob.data.return = 0;

                    l.purchase.purchase_products.forEach((el, index) => {
                        addJob.data.product.push({
                            productName: el.product.name,
                            RequiredQuantity: el.quantity,
                            subUnitMeasurement: el.product.sub_measurement_unit.name,
                            mainUnitMeasurement: el.product.main_measurement_unit.name,
                            count_unit: el.count_unit,
                            production_date: el.production_date,
                            expiry_date: el.expiry_date,
                            product_id: el.product_id,
                            quantity_received: el.quantity,
                            purchase_product_id:el.id,
                            return_quantity: 0,
                            note: '',
                            product_status_id: '',

                        });

                        addJob.data.received += el.quantity;

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
                    })
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        };

        onMounted(() => {
            getData();
        });

        let addJob =  reactive({
            data:{
                product:[

                ],
                notes_received:'',
                notes_return:'',
                stock_id:'',
                supplier_id:'',
                purchase_id:'',
                received : 0,
                return : 0,
            },
            products:[
            ]
        });

        const rules = computed(() => {
            return {
                product:[
                    ...productValidation.value
                ]
            }
        });


        const v$ = useVuelidate(rules,addJob.data);

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

        return {t,notesInvoice,productStatuses,quantityReceived,returnQuantity,quantityInvoice,supplierName,storeName,loading,...toRefs(addJob),v$,productValidation};
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/examinationRecord`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.AddedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                        this.$router.push({name:'indexExaminationRecord'});
                    })
                    .catch((err) => {
                        // console.log(err.response);
                        this.errors = err.response.data.errors;
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
    background-color: #0E67D0;
    color: #000000 !important;
    border-radius: 5px;
}
.account2{
    background-color: #0E67D0;
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
