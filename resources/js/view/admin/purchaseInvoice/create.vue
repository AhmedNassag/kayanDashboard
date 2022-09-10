<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.PurchaseInvoice')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexPurchaseInvoice'}">{{$t('global.PurchaseInvoice')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.Add')}}</li>
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
                                    :to="{name: 'indexPurchaseInvoice'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['stock_id']">{{ errors['stock_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['supplier_id']">{{ errors['supplier_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['discount_percentage']">{{ errors['discount_percentage'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['discount_value']">{{ errors['discount_value'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['other_discounts']">{{ errors['other_discounts'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['transfer_price']">{{ errors['transfer_price'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['note']">{{ errors['note'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['price']">{{ errors['price'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.product_id']">{{ errors['product.0.product_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.quantity']">{{ errors['product.0.quantity'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.price_before_discount']">{{ errors['product.0.price_before_discount'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.price_after_discount']">{{ errors['product.0.price_after_discount'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.production_date']">{{ errors['product.0.production_date'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.expiry_date']">{{ errors['product.0.expiry_date'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.count_unit']">{{ errors['product.0.count_unitv'][0] }}<br /> </div>
                                    <form @submit.prevent="storeJob" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.ChooseStore') }}</label>

                                                <select v-model="data.stock_id" :class="['form-select',{'is-invalid':v$.stock_id.$error,'is-valid':!v$.stock_id.$invalid}]">
                                                    <option v-for="store in stores" :key="store.id" :value="store.id">{{store.name}}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.stock_id.required.$invalid">{{$t('global.StoreIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.ChooseSupplier') }}</label>

                                                <select v-model="data.supplier_id" :class="['form-select',{'is-invalid':v$.supplier_id.$error,'is-valid':!v$.supplier_id.$invalid}]">
                                                    <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">{{supplier.name}}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.supplier_id.required.$invalid">{{$t('global.supplierIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom10">{{ $t('global.TotalPrice') }}</label>
                                                <input type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.price.$model"
                                                       id="validationCustom10"
                                                       :class="{'is-invalid':v$.price.$error,'is-valid':!v$.price.$invalid}"
                                                       :placeholder="$t('global.TotalPrice')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.price.required.$invalid">{{ $t('global.TotalPriceIsRequired') }} <br/></span>
                                                    <span v-if="v$.price.numeric.$invalid">{{$t('global.TotalPriceIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom06">{{ $t('global.discountPercentage') }}</label>
                                                <input :disabled="!data.price" type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.discount_percentage.$model"
                                                       id="validationCustom06"
                                                       :class="{'is-invalid':v$.discount_percentage.$error,'is-valid':!v$.discount_percentage.$invalid}"
                                                       :placeholder="$t('global.discountPercentage')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.discount_percentage.required.$invalid">{{ $t('global.discountPercentageIsRequired') }} <br/></span>
                                                    <span v-if="v$.discount_percentage.numeric.$invalid">{{$t('global.discountPercentageIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom07">{{ $t('global.discountValue') }}</label>
                                                <input :disabled="!data.price" type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.discount_value.$model"
                                                       id="validationCustom07"
                                                       :class="{'is-invalid':v$.discount_value.$error,'is-valid':!v$.discount_value.$invalid}"
                                                       :placeholder="$t('global.discountValue')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.discount_value.required.$invalid">{{ $t('global.discountValueIsRequired') }} <br/></span>
                                                    <span v-if="v$.discount_value.numeric.$invalid">{{$t('global.discountValueIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom08">{{ $t('global.otherDiscounts') }}</label>
                                                <input :disabled="!data.price" type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.other_discounts.$model"
                                                       id="validationCustom08"
                                                       :class="{'is-invalid':v$.other_discounts.$error,'is-valid':!v$.other_discounts.$invalid}"
                                                       :placeholder="$t('global.otherDiscounts')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.other_discounts.required.$invalid">{{ $t('global.otherDiscountsIsRequired') }} <br/></span>
                                                    <span v-if="v$.other_discounts.numeric.$invalid">{{$t('global.otherDiscountsIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom09">{{ $t('global.transferPrice') }}</label>
                                                <input type="number" step=".01"
                                                       class="form-control"
                                                       v-model.trim="v$.transfer_price.$model"
                                                       id="validationCustom09"
                                                       :class="{'is-invalid':v$.transfer_price.$error,'is-valid':!v$.transfer_price.$invalid}"
                                                       :placeholder="$t('global.transferPrice')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.transfer_price.required.$invalid">{{ $t('global.transferPriceIsRequired') }} <br/></span>
                                                    <span v-if="v$.transfer_price.numeric.$invalid">{{$t('global.transferPriceIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{$t('global.Notes')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="v$.note.$model" :class="['form-control text-height',{'is-invalid':v$.note.$error,'is-valid':!v$.note.$invalid}]" :placeholder="$t('global.Notes')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.note.required.$invalid">{{$t('global.DescriptionIsRequired')}}<br /> </span>
                                                    <span v-if="v$.note.minLength.$invalid">{{$t('global.DescriptionIsMustHaveAtLeast')}} {{ v$.note.minLength.$params.max }} {{$t('global.Letters')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-12">
                                                <div class="row account">
                                                    <div class="col-md-12 mb-12 head-account">
                                                        <h3>{{ $t('global.Products') }}</h3>
                                                    </div>
                                                    <div v-for="(it,index) in data.product" :key="it.id" class="col-md-12 mb-12 body-account row">
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.mainCategory') }}</label>

                                                            <select @change="getSubCategory(it.category_id,index)" v-model="it.category_id" :class="['form-select',{'is-invalid':v$.product[index].category_id.$error,'is-valid':!v$.product[index].category_id.$invalid}]">
                                                                <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].category_id.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.subCategory') }}</label>

                                                            <select @change="getProduct(it.category_id,it.sub_category_id,index)" v-model="it.sub_category_id" :class="['form-select',{'is-invalid':v$.product[index].sub_category_id.$error,'is-valid':!v$.product[index].sub_category_id.$invalid}]">
                                                                <option v-for="category in subCategory[index].subCategory" :key="category.id" :value="category.id">{{category.name}}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].sub_category_id.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.Products') }}</label>

                                                            <select @change="getMeasurementUnit(it.product_id,index)" v-model="it.product_id" :class="['form-select',{'is-invalid':v$.product[index].product_id.$error,'is-valid':!v$.product[index].product_id.$invalid}]">
                                                                <option v-for="category in products[index].products" :key="category.id" :value="category.id">{{category.name}}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].product_id.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.RequiredQuantity')}} ( {{data.product[index].mainUnitMeasurement}} )</label>
                                                            <input type="number" class="form-control"
                                                                   v-model.number="v$.product[index].quantity.$model"
                                                                   @input="DebitAmount"
                                                                   :placeholder="$t('global.RequiredQuantity')"
                                                                   :class="{'is-invalid':v$.product[index].quantity.$error,'is-valid':!v$.product[index].quantity.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.mainUnitMeasurement')}}</label>
                                                            <input type="text" disabled class="form-control"
                                                                   v-model="data.product[index].mainUnitMeasurement"
                                                                   @input="DebitAmount"
                                                            >
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.countUnits')}}</label>
                                                            <input type="number" class="form-control"
                                                                   v-model.number="v$.product[index].count_unit.$model"
                                                                   @input="DebitAmount"
                                                                   :placeholder="$t('global.countUnits')"
                                                                   :class="{'is-invalid':v$.product[index].count_unit.$error,'is-valid':!v$.product[index].count_unit.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].count_unit.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].count_unit.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                            </div>
                                                        </div>

                                                        <!-- <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.subUnitMeasurement')}}</label>
                                                            <input type="text" disabled class="form-control"
                                                                   v-model="data.product[index].subUnitMeasurement"
                                                                   @input="DebitAmount"
                                                            >
                                                        </div> -->

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.priceBeforeDiscount')}}</label>
                                                            <input type="number" step="0.1" class="form-control"
                                                                   @change="validateLTE(index)"
                                                                   v-model.number="v$.product[index].price_before_discount.$model"
                                                                   :placeholder="$t('global.priceBeforeDiscount')"
                                                                   :class="{'is-invalid':v$.product[index].price_before_discount.$error,'is-valid':!v$.product[index].price_before_discount.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].price_before_discount.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].price_before_discount.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.priceAfterDiscount')}}</label>
                                                            <input type="number" step="0.1" class="form-control"
                                                                   @input="validateLTE(index)"
                                                                   v-model.number="v$.product[index].price_after_discount.$model"
                                                                   :placeholder="$t('global.priceAfterDiscount')"
                                                                   :class="{'is-invalid':v$.product[index].price_after_discount.$error || !products[index].send,'is-valid':!v$.product[index].price_after_discount.$invalid && products[index].send}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].price_after_discount.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].price_after_discount.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                                <span v-if="!products[index].send">{{$t('global.priceAfterDiscountMastBeLessThanOrEqualPriceBeforeDiscount')}} <br /></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.productionDate')}}</label>
                                                            <input type="date" class="form-control"
                                                                   v-model="v$.product[index].production_date.$model"
                                                                   @change="validateProductionDate(index)"
                                                                   :placeholder="$t('global.productionDate')"
                                                                   :class="{'is-invalid':v$.product[index].production_date.$error || !products[index].sendProductionDate,'is-valid':!v$.product[index].production_date.$invalid && products[index].sendProductionDate}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].production_date.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="!products[index].sendProductionDate">{{$t('global.ProductionDateMastLessThanOrEqualToday')}}<br /> </span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.expiryDate')}}</label>
                                                            <input type="date" class="form-control"
                                                                   @change="validateExpiryDate(index)"
                                                                   v-model="v$.product[index].expiry_date.$model"
                                                                   :placeholder="$t('global.expiryDate')"
                                                                   :class="{'is-invalid':v$.product[index].expiry_date.$error || !products[index].sendExpiryDate,'is-valid':!v$.product[index].expiry_date.$invalid && products[index].sendExpiryDate}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].expiry_date.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="!products[index].sendExpiryDate">{{$t('global.expiryDateMastGreaterThanToday')}}<br /> </span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <button @click.prevent="addDebit" v-if="(data.product.length-1) == index"
                                                                class="btn btn-sm btn-success me-2 mt-5">
                                                                <i class="fas fa-clipboard-list"></i> {{$t('global.AddANewLine')}}
                                                            </button>
                                                            <button v-if="index" @click.prevent="deleteDebit(index)"
                                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2 mt-5">
                                                                <i class="far fa-trash-alt"></i> {{$t('global.Delete')}}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-12 mt-5">
                                                <div class="table-responsive">
                                                    <table class="table table-center table-hover mb-0 datatable">
                                                        <thead class="account">
                                                            <tr class="text-center">
                                                                <th>{{ $t('global.TotalPriceBeforeDiscount') }}</th>
                                                                <th>{{ $t('global.TotalPriceAfterDiscount') }}</th>
                                                                <th>{{ $t('global.TotalProductPrice') }}</th>
                                                                <th>{{ $t('global.TotalQuantityPrice') }}</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="text-center">
                                                                <td>{{data.price ? parseFloat(data.price) + parseFloat(data.transfer_price) : 0}}</td>
                                                                <td>{{data.price ? (parseFloat(data.price) + parseFloat(data.transfer_price)) - (parseFloat(data.discount_value) + parseFloat(data.other_discounts)) : 0}}</td>
                                                                <td>{{totalProductPrice ? totalProductPrice : 0}}</td>
                                                                <td>{{totalProductQuantity ? totalProductQuantity : 0}}</td>
                                                            </tr>
                                                            <tr class="text-center">
                                                                <td>{{ $t('global.Notes') }}</td>
                                                                <td colspan="9" v-if="totalProductPrice < (parseFloat(data.price) + parseFloat(data.transfer_price)) - (parseFloat(data.discount_value) + parseFloat(data.other_discounts))"> {{$t('global.The total price of the products is less than the total price of the purchase invoice')}}</td>
                                                                <td colspan="9" v-else-if="totalProductPrice == (parseFloat(data.price) + parseFloat(data.transfer_price)) - (parseFloat(data.discount_value) + parseFloat(data.other_discounts))">{{$t('global.The total price of the products is equal to the total price of the purchase invoice')}}</td>
                                                                <td colspan="9" v-else>{{$t('global.The total price of the products is greater than the total price of the purchase invoice')}}</td>
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
    setup(){
        const {t} = useI18n({});
        let loading = ref(false);
        let categories = ref([]);
        let suppliers = ref([]);
        let stores = ref([]);
        let productValidation = ref([{
            price_before_discount: {
                required,
                numeric
            },
            price_after_discount: {
                required,
                numeric
            },
            production_date: {
                required
            },
            expiry_date: {
                required
            },
            quantity: {
                required,
                numeric
            },
            count_unit: {
                required,
                numeric
            },
            category_id:{
                required
            },
            sub_category_id:{
                required
            },
            product_id:{
                required
            }
        }]);

        let totalProductPrice = ref(0);
        let totalProductQuantity = ref(0);
        let validateLTE = (index) =>{
            addJob.products[index].send = addJob.data.product[index].price_before_discount >= addJob.data.product[index].price_after_discount;
            DebitAmount();
        }
        let validateProductionDate = (index) =>{
            addJob.products[index].sendProductionDate = addJob.data.product[index].production_date <= new Date().toISOString().split('T')[0];
        }
        let validateExpiryDate = (index) =>{
            addJob.products[index].sendExpiryDate = addJob.data.product[index].expiry_date > new Date().toISOString().split('T')[0];
        }

        let DebitAmount = () => {
            totalProductPrice.value = 0;
            totalProductQuantity.value = 0;
            addJob.data.product.forEach((el) => {
                totalProductPrice.value += parseFloat(el.price_after_discount) * parseInt( el.quantity);
                totalProductQuantity.value += parseInt( el.quantity);
            });
        }

        let getData = () => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/purchaseInvoice/create`)
                .then((res) => {
                    let l = res.data.data;
                    categories.value = l.categories;
                    stores.value = l.stores;
                    suppliers.value = l.suppliers;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getData();
        });

        let getSubCategory = (id,index) => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/category/${id}`)
                .then((res) => {
                    let l = res.data.data;
                    addJob.subCategory[index].subCategory  = l.subCategories;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        let getProduct = (category_id,sub_category_id,index) => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/purchaseInvoiceProduct?category_id=${category_id}&sub_category_id=${sub_category_id}`)
                .then((res) => {
                    let l = res.data.data;
                    addJob.products[index].products = l.products;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        let getMeasurementUnit = (id,index) => {
            let products_data = [];
            let product=null;
            products_data = addJob.products[index].products;
            products_data.forEach(function(_product){
                if(_product.id==id){
                    return product=_product;
                }
            });
            addJob.data.product[index].subUnitMeasurement = product.sub_measurement_unit.name;
            addJob.data.product[index].mainUnitMeasurement = product.main_measurement_unit.name;
            addJob.data.product[index].count_unit = product.count_unit;
        }

        let addJob =  reactive({
            data:{
                product:[
                    {
                        price_before_discount:'',
                        price_after_discount:'',
                        production_date:'',
                        expiry_date:'',
                        quantity:'',
                        count_unit:'',
                        category_id:'',
                        sub_category_id:'',
                        product_id:'',
                        subUnitMeasurement:'',
                        mainUnitMeasurement:'',
                    }
                ],
                note:'',
                stock_id:'',
                supplier_id:'',
                discount_percentage:0,
                discount_value:0,
                other_discounts:0,
                transfer_price:0,
                price:'',
            },
            subCategory:[
                {subCategory:[]}
            ],
            products:[
                {products:[],send:true,sendProductionDate:true,sendExpiryDate:true}
            ]
        });

        const rules = computed(() => {
            return {
                product:[
                    ...productValidation.value
                ],

                note:{
                    required,
                    minLength: minLength(3),
                },
                stock_id:{
                    required
                },
                supplier_id:{
                    required
                },
                discount_percentage:{
                    required,
                    numeric
                },
                discount_value:{
                    required,
                    numeric
                },
                other_discounts:{
                    required,
                    numeric
                },
                transfer_price:{
                    required,
                    numeric
                },
                price:{
                    required,
                    numeric
                }
            }
        });


        const v$ = useVuelidate(rules,addJob.data);

        watch(() => addJob.data.discount_percentage ,(after, before) => {
            addJob.data.discount_value = (after * addJob.data.price) /100;
        });

        watch(() => addJob.data.discount_value ,(after, before) => {
            addJob.data.discount_percentage =   (after*100)/addJob.data.price;
        });

        return {t,validateLTE,validateExpiryDate,validateProductionDate,getProduct,getMeasurementUnit,getSubCategory,categories,suppliers,stores,loading,...toRefs(addJob),v$,totalProductQuantity,totalProductPrice,productValidation,DebitAmount};
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/purchaseInvoice`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.AddedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                        this.resetForm();
                        this.$nextTick(() => { this.v$.$reset() });
                        this.totalProductPrice = 0;
                        this.totalProductQuantity = 0;
                    })
                    .catch((err) => {
                        // console.log(err.response)
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        addDebit(){
            this.data.product.push({
                price_before_discount:null,
                price_after_discount:null,
                production_date:null,
                expiry_date:null,
                quantity:null,
                count_unit:null,
                category_id:'',
                sub_category_id:'',
                product_id:''
            });
            this.productValidation.push({
                price_before_discount: {
                    required,
                    numeric
                },
                price_after_discount: {
                    required,
                    numeric
                },
                production_date: {
                    required
                },
                expiry_date: {
                    required
                },
                quantity: {
                    required,
                    numeric
                },
                count_unit: {
                    required,
                    numeric
                },
                category_id:{
                    required
                },
                sub_category_id:{
                    required
                },
                product_id:{
                    required
                }
            });

            this.subCategory.push({subCategory:[]});
            this.products.push({products:[],send:true,sendProductionDate:true,sendExpiryDate:true});
            this.$nextTick(() => { this.v$.$reset() });
        },
        deleteDebit(index){
            this.data.product.splice(index,1);
            this.productValidation.splice(index,1);
            this.subCategory.splice(index,1);
            this.products.splice(index,1);
            this.$nextTick(() => { this.v$.$reset() });
            this.DebitAmount();
        },
        resetForm(){
            this.data.note = '';
            this.data.stock_id = '';
            this.data.supplier_id = '';
            this.data.discount_percentage = 0;
            this.data.discount_value = 0;
            this.data.other_discounts = 0;
            this.data.transfer_price = 0;
            this.data.price = '';
            this.data.product = [
                {   price_before_discount:null,
                    price_after_discount:null,
                    production_date:null,
                    expiry_date:null,
                    quantity:null,
                    count_unit:null,
                    category_id:'',
                    sub_category_id:'',
                    product_id:''
                }
            ];
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
