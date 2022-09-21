<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.Product") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexProduct'}">{{ $t("global.Product") }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("product.EditProduct") }}</li>
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
                                    :to="{name: 'indexProduct'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{ $t("global.back") }}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="editProduct" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Start Company And Supplier Supplier-->
                                            <div class="col-md-6 mb-3">

                                            <!--Start Company Select-->
                                            <div id="company" class="col-md-12 mb-3" v-if="companyShow == true">
                                                <label >{{ $t("global.Company") }}</label>
                                                <Select2 v-model="v$.company_id.$model" :options="companies" :settings="{ width: '100%' }" />
                                                <!-- <select
                                                    name="type"
                                                    class="form-select"
                                                    v-model="v$.company_id.$model"
                                                    :class="{'is-invalid':v$.company_id.$error,'is-valid':!v$.company_id.$invalid}"
                                                >
                                                    <option :value="data.nullValue">---</option>
                                                    <option v-for="company in companies" :key="company.id" :value="company.id">
                                                        {{ company.name }}
                                                    </option>
                                                </select> -->
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <!-- <span v-if="v$.company_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /> </span> -->
                                                </div>
                                                <input id ="myButton1" class="btn btn-secondary btn-ms" type="button" v-on:click="showSupplier()" value="مورد"/>
                                            </div>
                                            <!--End Company Select-->

                                            <!--Start Supplier Select-->
                                            <div id="supplier" class="col-md-12 mb-3" v-if="supplierShow == true">
                                                <label for="validationCustom0">
                                                    {{ $t("global.Supplier") }}
                                                </label>
                                                <Select2 v-model="v$.supplier_id.$model" :options="suppliers" :settings="{ width: '100%' }" />
                                                <!-- <select
                                                    name="type"
                                                    class="form-select"
                                                    v-model.trim="v$.supplier_id.$model"
                                                    :class="{'is-invalid':v$.supplier_id.$error,'is-valid':!v$.supplier_id.$invalid}"
                                                >
                                                    <option :value="data.nullValue">---</option>
                                                    <option id="supplier-option" v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                                                        {{ supplier.name }}
                                                    </option>
                                                </select> -->
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <!-- <span v-if="v$.supplier_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /> </span> -->
                                                </div>
                                                <input id ="myButton2" class="btn btn-secondary btn-ms" type="button" v-on:click="showCompany()" value="شركة"/>
                                            </div>
                                            <!--End Supplier Select-->

                                            </div>
                                            <!--End Company And Supplier Supplier-->

                                            <!--Start Barcode-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{ $t("global.BarCode") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model.trim="v$.barcode.$model"
                                                    id="validationCustom056"
                                                    :placeholder="$t('global.BarCode')"
                                                    :class="{'is-invalid':v$.barcode.$error,'is-valid':!v$.barcode.$invalid}"
                                                >
                                                <button type="button" class="btn btn-secondary btn-sm" @click="myFunction()">{{ $t("global.Generate Random") }}</button>
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.barcode.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                    <span v-if="v$.barcode.integer.$invalid">{{ $t("global.ThisFieldMustBeANumber") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End BarCode-->

                                            <!--Start Product Name Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom00">
                                                    {{ $t("global.Product Name") }}
                                                </label>
                                                <Select2 v-model="v$.productName_id.$model" :options="productNames" :settings="{ width: '100%' }" />
                                                <!-- <select
                                                    name="type"
                                                    class="form-select"
                                                    v-model.trim="v$.productName_id.$model"
                                                    :class="{'is-invalid':v$.productName_id.$error,'is-valid':!v$.productName_id.$invalid}"
                                                >
                                                    <option v-for="productName in productNames" :key="productName.id" :value="productName.id">
                                                        {{ productName.nameAr }}
                                                    </option>
                                                </select> -->
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.productName_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Product Name Select-->

                                            <!--Start Category Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.MainCategory") }}</label>
                                                <!-- <Select2 @change="getSubCategory(v$.category_id.$model)" v-model="v$.category_id.$model" :options="categories" :settings="{ width: '100%' }" /> -->
                                                <select @change="getSubCategory(v$.category_id.$model)"
                                                    name="type"
                                                    class="form-select"
                                                    v-model="v$.category_id.$model"
                                                    :class="{'is-invalid':v$.category_id.$error,'is-valid':!v$.category_id.$invalid}"
                                                >
                                                    <option v-for="category in categories" :key="category.id" :value="category.id" >
                                                        {{ category.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.category_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Category Select-->

                                            <!--Start SubCategory Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.SubCategory") }}</label>
                                                <Select2 v-model="v$.sub_category_id.$model" :options="subCategories" :settings="{ width: '100%' }" />
                                                <!-- <select
                                                    name="type"
                                                    class="form-select"
                                                    v-model="v$.sub_category_id.$model"
                                                    :class="{'is-invalid':v$.sub_category_id.$error,'is-valid':!v$.sub_category_id.$invalid}"
                                                >
                                                    <option v-for="subCategory in subCategories" :key="subCategory.id" :value="subCategory.id" >
                                                        {{ subCategory.name }}
                                                    </option>
                                                </select> -->
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_category_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End SubCategory Select-->

                                            <!--Start Main Measurement Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.Main Measurement Unit") }}</label>
                                                <Select2 v-model="v$.main_measurement_unit_id.$model" :options="measures" :settings="{ width: '100%' }" />
                                                <!-- <select
                                                    name="type"
                                                    class="form-select"
                                                    v-model="v$.main_measurement_unit_id.$model"
                                                    :class="{'is-invalid':v$.main_measurement_unit_id.$error,'is-valid':!v$.main_measurement_unit_id.$invalid}"
                                                >
                                                    <option v-for="measure in measures" :key="measure.id" :value="measure.id" >
                                                        {{ measure.name }}
                                                    </option>
                                                </select> -->
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.main_measurement_unit_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Main Measurement Select-->

                                            <!--Start Count Unit Select-->
                                            <!-- <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.Count Unit") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.count_unit.$model"
                                                    :placeholder="$t('global.Count Unit')"
                                                    :class="{'is-invalid':v$.count_unit.$error,'is-valid':!v$.count_unit.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.count_unit.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                    <span v-if="v$.count_unit.integer.$invalid">{{ $t("global.ThisFieldMustBeANumber") }}<br /></span>
                                                </div>
                                            </div> -->
                                            <!--End Count Unit Select-->

                                            <!--Start Sub Measurement Select-->
                                            <!-- <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Sub Measurement Unit") }}</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.sub_measurement_unit_id.$model"
                                                    :class="{'is-invalid':v$.sub_measurement_unit_id.$error,'is-valid':!v$.sub_measurement_unit_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="measure in measures" :key="measure.id" :value="measure.id" >
                                                        {{ measure.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_measurement_unit_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div> -->
                                            <!--End Sub Measurement Select-->

                                            <!--Start Tax Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.Tax") }}</label>
                                                <Select2 v-model="v$.tax_id.$model" :options="taxes" :settings="{ width: '100%' }" />
                                                <!-- <select
                                                    name="type"
                                                    class="form-select"
                                                    v-model="v$.tax_id.$model"
                                                    :class="{'is-invalid':v$.tax_id.$error,'is-valid':!v$.tax_id.$invalid}"
                                                >
                                                    <option v-for="tax in taxes" :key="tax.id" :value="tax.id" >
                                                        {{ tax.name }}
                                                    </option>
                                                </select> -->
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.tax_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Tax Select-->

                                            <!--Start PharmacistForm Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.Pharmacist Form") }}</label>
                                                <Select2 v-model="v$.pharmacistForm_id.$model" :options="pharmacistForms" :settings="{ width: '100%' }" />
                                                <!-- <select
                                                    name="type"
                                                    class="form-select"
                                                    v-model="v$.pharmacistForm_id.$model"
                                                    :class="{'is-invalid':v$.pharmacistForm_id.$error,'is-valid':!v$.pharmacistForm_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="pharmacistForm in pharmacistForms" :key="pharmacistForm.id" :value="pharmacistForm.id" >
                                                        {{ pharmacistForm.name }}
                                                    </option>
                                                </select> -->
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.pharmacistForm_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End PharmacistForm Select-->

                                            <!--Start MaxMount-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom055">{{ $t("global.MaxMount") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model.trim="v$.maximum_product.$model"
                                                    id="validationCustom055"
                                                    :placeholder="$t('global.MaxMount')"
                                                    :class="{'is-invalid':v$.maximum_product.$error,'is-valid':!v$.maximum_product.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.maximum_product.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                    <span v-if="v$.maximum_product.integer.$invalid">{{ $t("global.ThisFieldMustBeANumber") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End MaxMount-->

                                            <!--Start Re Order Limit-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Re Order Limit") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.Re_order_limit.$model"
                                                    :placeholder="$t('global.Re Order Limit')"
                                                    :class="{'is-invalid':v$.Re_order_limit.$error,'is-valid':!v$.Re_order_limit.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.Re_order_limit.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                    <span v-if="v$.Re_order_limit.integer.$invalid">{{ $t("global.ThisFieldMustBeANumber") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Re Order Limit-->

                                            <!--Start Effective Material-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Effective Material") }}</label>
                                                <input
                                                    type="text" class="form-control"
                                                    v-model="v$.effectiveMaterial.$model"
                                                    :placeholder="$t('global.Effective Material')"
                                                    :class="{'is-invalid':v$.effectiveMaterial.$error,'is-valid':!v$.effectiveMaterial.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.effectiveMaterial.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Effective Material-->

                                            <!--Start Selling Method Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.Selling Method") }}</label>
                                                <select
                                                    name="type"
                                                    class="form-select"
                                                    multiple
                                                    v-model="v$.selling_methods.$model"
                                                    :class="{'is-invalid':v$.selling_methods.$error,'is-valid':!v$.selling_methods.$invalid}"
                                                >
                                                    <option v-for="sellingMethod in sellingMethods" :key="sellingMethod.id" :value="sellingMethod.id" >
                                                        {{ sellingMethod.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.selling_methods.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--Start Selling Method Select-->

                                            <!--Start Description-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom034">{{ $t("global.Description") }}</label>
                                                <textarea
                                                    type="text"
                                                    class="form-control custom-textarea"
                                                    v-model.trim="v$.description.$model"
                                                    id="validationCustom034"
                                                    :placeholder="$t('global.Description')"
                                                    :class="{'is-invalid':v$.description.$error,'is-valid':!v$.description.$invalid}"
                                                ></textarea>
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                            <!--End Description-->

                                            <!--Start Image-->
                                            <div class="col-md-3 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        {{ $t("global.ChooseImage") }}
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input
                                                        name="mediaPackage"
                                                        type="file"
                                                        @change="preview"
                                                        id="mediaPackage"
                                                        accept="image/png,jepg,jpg"
                                                    >
                                                </div>
                                                <span class="text-danger text-center">{{ $t("global.ImageValidation") }}</span>
                                                <p class="num-of-files">{{numberOfImage ? numberOfImage + ' Files Selected' : 'No Files Chosen' }}</p>
                                                <div class="container-images" id="container-images" v-show="data.image && numberOfImage"></div>
                                                <div class="container-images" v-show="!numberOfImage">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/admin/img/company/img-1.png`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <!--End Image-->

                                            <!--Start Multiple Images-->
                                            <div class="col-md-9 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        {{ $t("global.ChooseImage") }}
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input
                                                        name="mediaPackage[]"
                                                        type="file"
                                                        multiple
                                                        @change="preview2"
                                                        id="mediaPackage1"
                                                        accept="image/png,jepg,jpg"
                                                    >
                                                </div>
                                                <span class="text-danger text-center">{{ $t("global.ImageValidation") }}</span>
                                                <p class="num-of-files">{{numberOfImage1 ? numberOfImage1 + ' Files Selected' : 'No Files Chosen' }}</p>
                                                <div class="container-images" id="container-images1" v-show="data.files && numberOfImage1"></div>
                                                <div class="container-images" v-show="!numberOfImage1">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/admin/img/company/img-1.png`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <!--End Multiple Images-->

                                        </div>

                                        <button class="btn btn-primary" type="submit">تعديل</button>
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
import {computed, onMounted, reactive,toRefs,ref} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, maxLength, integer} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "editProduct",
    data(){
        return {
            errors:{},
            companyShow: true,
            supplierShow: false
        }
    },
    props:["id"],
    setup(props){

        const {id} = toRefs(props)
        // get create Package
        let loading = ref(false);
        let productNames = ref([]);
        let suppliers = ref([]);
        let companies = ref([]);
        let categories = ref([]);
        let subCategories = ref([]);
        let measures = ref([]);
        let taxes = ref([]);
        let pharmacistForms = ref([]);
        let sellingMethods = ref([]);
        let images = ref([]);
        let image = ref('');

        //start design
        let addProduct =  reactive({
            data:{
                nullValue: null,
                productName_id: null,
                pharmacistForm_id: null,
                barcode : null,
                // count_unit : null,
                maximum_product: null,
                Re_order_limit: null,
                description : null,
                effectiveMaterial: null,
                image : {},
                files : [],
                category_id: null,
                sub_category_id: null,
                company_id: null,
                supplier_id: null,
                main_measurement_unit_id: null,
                // sub_measurement_unit_id: null,
                tax_id: null,
                selling_methods: [],
            }
        });

        let getProduct = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/product/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addProduct.data.productName_id = l.product.productName_id;
                    addProduct.data.pharmacistForm_id = l.product.pharmacistForm_id;
                    addProduct.data.barcode = l.product.barcode;
                    // addProduct.data.count_unit = l.product.count_unit;
                    addProduct.data.maximum_product= l.product.maximum_product;
                    addProduct.data.Re_order_limit = l.product.Re_order_limit;
                    addProduct.data.description = l.product.description;
                    addProduct.data.effectiveMaterial = l.product.effectiveMaterial;
                    addProduct.data.category_id = l.product.category_id;
                    addProduct.data.sub_category_id = l.product.sub_category_id;
                    addProduct.data.company_id = l.product.company_id;
                    addProduct.data.supplier_id = l.product.supplier_id;
                    addProduct.data.main_measurement_unit_id = l.product.main_measurement_unit_id;
                    // addProduct.data.sub_measurement_unit_id = l.product.sub_measurement_unit_id;
                    addProduct.data.tax_id = l.product.tax_id;
                    image.value = l.product.image;
                    images.value = l.product.media;
                    productNames.value = l.productNames;
                    companies.value = l.companies;
                    suppliers.value = l.suppliers;
                    categories.value = l.categories;
                    measures.value = l.measures;
                    taxes.value = l.taxes;
                    pharmacistForms.value = l.pharmacistForms
                    sellingMethods.value = l.sellingMethods;
                    l.sellingMethodChange.forEach((e) => {
                        addProduct.data.selling_methods.push(e.id);
                    });
                    console.log( addProduct.data.selling_methods);
                    getSubCategory(l.product.category_id);
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        };

        onMounted(() => {
            getProduct();
        });

        let getSubCategory= (id) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/category/${id}`)
                .then((res) => {
                    let l = res.data.data;
                    subCategories.value = l.subCategories;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        };

        const rules = computed(() => {
            return {
                productName_id: {
                    required,
                },
                pharmacistForm_id: {
                    required,
                },
                supplier_id: {
                    // required,
                },
                barcode: {
                    required,
                    integer
                },
                // count_unit: {
                //     required,
                //     integer
                // },
                maximum_product: {
                    required,
                    integer
                },
                Re_order_limit: {
                    required,
                    integer
                },
                effectiveMaterial: {
                    required
                },
                description: {},
                image: {
                    //required
                },
                files: {
                    //required
                },
                category_id: {
                    required,
                    integer
                },
                sub_category_id: {
                    required,
                    integer
                },
                company_id: {
                    // required,
                },
                main_measurement_unit_id: {
                    required,
                    integer
                },
                // sub_measurement_unit_id: {
                //     required,
                //     integer
                // },
                tax_id: {
                    required,
                    integer
                },
                selling_methods: {
                    required
                }
            }
        });

        const v$ = useVuelidate(rules,addProduct.data);

        let preview = (e) => {

            let containerImages = document.querySelector('#container-images');
            if(numberOfImage.value){
                containerImages.innerHTML = '';
            }
            addProduct.data.image = {};

            numberOfImage.value = e.target.files.length;

            addProduct.data.image = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement('figure');
            let figcap = document.createElement('figcaption');

            figcap.innerText = addProduct.data.image.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement('img');
                img.setAttribute('src',reader.result);
                figure.insertBefore(img,figcap);
            }

            containerImages.appendChild(figure);
            reader.readAsDataURL(addProduct.data.image);

        };

        let preview2 = (e) => {

            let containerImages = document.querySelector('#container-images1');
            if(numberOfImage1.value){
                containerImages.innerHTML = '';
            }
            addProduct.data.files = [];

            numberOfImage1.value = e.target.files.length;

            for(let file of e.target.files){

                addProduct.data.files.push(file);
                let reader = new FileReader();
                let figure = document.createElement('figure');
                let figcap = document.createElement('figcaption');

                figcap.innerText = file.name;
                figure.appendChild(figcap);

                reader.onload = () => {
                    let img = document.createElement('img');
                    img.setAttribute('src',reader.result);
                    figure.insertBefore(img,figcap);
                }

                containerImages.appendChild(figure);
                reader.readAsDataURL(file);
            }

        };

        const numberOfImage = ref(0);
        const numberOfImage1 = ref(0);

        let deleteOne = (idMedia,index) =>{
            loading.value = true;

            adminApi.post(`/v1/dashboard/deleteOne/${id.value}`,{idMedia:idMedia})
                .then((res) => {
                    images.value.splice(index,1);
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        return { loading,
            ...toRefs(addProduct),
            v$,
            preview,
            preview2,
            numberOfImage,
            numberOfImage1,
            companies,
            categories,
            measures,
            taxes,
            sellingMethods,
            productNames,
            suppliers,
            pharmacistForms,
            image,
            images,
            deleteOne,
            subCategories,
            getSubCategory,
            id

        };
    },
    methods: {
        myFunction() {
            this.data.barcode = Math.random()*100;
        },
        showCompany()
        {
            this.companyShow = true;
            this.supplierShow = false;
        },
        showSupplier()
        {
            this.supplierShow = true;
            this.companyShow = false;
        },

        editProduct(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                let formData = new FormData();
                formData.append("productName_id", this.data.productName_id);
                formData.append("pharmacistForm_id", this.data.pharmacistForm_id);
                formData.append("supplier_id", this.data.supplier_id);
                formData.append('barcode',this.data.barcode);
                // formData.append('count_unit',this.data.count_unit);
                formData.append('maximum_product',this.data.maximum_product);
                formData.append('Re_order_limit',this.data.Re_order_limit);
                formData.append('effectiveMaterial',this.data.effectiveMaterial);
                formData.append('description',this.data.description);
                formData.append('category_id',this.data.category_id);
                formData.append('sub_category_id',this.data.sub_category_id);
                formData.append('company_id',this.data.company_id);
                // formData.append('sub_measurement_unit_id',this.data.sub_measurement_unit_id);
                formData.append('main_measurement_unit_id',this.data.main_measurement_unit_id);
                formData.append('tax_id',this.data.tax_id);
                formData.append('image',this.data.image);
                formData.append('selling_methods',this.data.selling_methods);
                formData.append('_method','PUT');

                for( var i = 0; i < this.numberOfImage1; i++ ){
                    let file = this.data.files[i];
                    formData.append('files[' + i + ']', file);
                }

                adminApi.post(`/v1/dashboard/product/${this.id}`,formData)
                .then((res) => {

                    notify({
                        title: `تم التعديل بنجاح <i class="fas fa-check-circle"></i>`,
                        type: "success",
                        duration: 5000,
                        speed: 2000
                    });

                })
                .catch((err) => {
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

.waves-effect {
    position: relative;
    overflow: hidden;
    cursor: pointer;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    width: 200px;
    height: 50px;
    text-align: center;
    line-height: 34px;
    margin: auto;
}

input[type="file"] {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 0;
    margin: 0;
    cursor: pointer;
    filter: alpha(opacity=0);
    opacity: 0;
}

.num-of-files{
    text-align: center;
    margin: 20px 0 30px;
}

.container-images {
    width: 90%;
    position: relative;
    margin: auto;
    display: flex;
    justify-content: space-evenly;
    gap: 20px;
    flex-wrap: wrap;
    padding: 10px;
    border-radius: 20px;
    background-color: #f7f7f7;
}

.delete {
    position: absolute;
    top: 6px;
    left: 23px;
    font-size: 16px;
    padding: 0px 5px;
    text-align: center;
    border: 1px solid;
    border-radius: 50%;
}
</style>
