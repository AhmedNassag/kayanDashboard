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
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexProduct'}">{{ $t("global.Product") }}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t("product.CreateProduct") }}</li>
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
                                <router-link :to="{name: 'indexProduct'}" class="btn btn-custom btn-dark">{{ $t("global.back") }}</router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="storeProduct" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Start NameAr-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.NameAr") }}
                                                </label>
                                                <input type="text" class="form-control" v-model.trim="v$.nameAr.$model"
                                                    id="validationCustom01" :placeholder="$t('global.NameAr')" :class="{
                                                      'is-invalid': v$.nameAr.$error || data.nameExist,
                                                      'is-valid': !v$.nameAr.$invalid,
                                                    }" />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.nameAr.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.nameAr.maxLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtLeast") }}
                                                        {{ v$.nameAr.minLength.$params.min }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.nameAr.minLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtMost") }}
                                                        {{ v$.nameAr.maxLength.$params.max }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="!v$.nameAr.$invalid && data.nameExist">
                                                        {{ $t("global.NameIsExist") }}
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End NameAr-->

                                            <!--Start NameEn-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.NameEn") }}
                                                </label>
                                                <input type="text" class="form-control" v-model.trim="v$.nameEn.$model"
                                                    id="validationCustom01" :placeholder="$t('global.NameEn')" :class="{
                                                      'is-invalid': v$.nameEn.$error || data.nameExist,
                                                      'is-valid': !v$.nameEn.$invalid,
                                                    }" />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.nameEn.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.nameEn.maxLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtLeast") }}
                                                        {{ v$.nameEn.minLength.$params.min }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.nameEn.minLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtMost") }}
                                                        {{ v$.nameEn.maxLength.$params.max }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="!v$.nameEn.$invalid && data.nameExist">
                                                        {{ $t("global.NameIsExist") }}
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End NameEn-->

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
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_category_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End SubCategory Select-->

                                            <!--Start Main Measurement Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >وحدة القياس الرئيسية</label>
                                                <Select2 v-model="v$.main_measurement_unit_id.$model" :options="measures" :settings="{ width: '100%' }" />
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.main_measurement_unit_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Main Measurement Select-->

                                            <!--Start Count Unit Select-->
                                            <!-- <div class="col-md-6 mb-3">
                                                <label >عدد الوحدات داخل الفئة الفرعية </label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.count_unit.$model"
                                                    placeholder="عدد الوحدات داخل الفئة الفرعية"
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
                                                <label >وحدة القياس الفرعية</label>
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
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <!-- <div class="invalid-feedback">
                                                    <span v-if="v$.tax_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div> -->
                                            </div>
                                            <!--End Tax Select-->

                                            <!--Start PharmacistForm Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.Pharmacist Form") }}</label>
                                                <Select2 v-model="v$.pharmacistForm_id.$model" :options="pharmacistForms" :settings="{ width: '100%' }" />
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.pharmacistForm_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End PharmacistForm Select-->

                                            <!--Start MaxMount-->
                                            <!-- <div class="col-md-6 mb-3">
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
                                            </div> -->
                                            <!--Start MaxMount-->

                                            <!--Start Re Order Limit-->
                                            <!-- <div class="col-md-6 mb-3">
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
                                            </div> -->
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
                                                <textarea type="text" class="form-control custom-textarea"
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


                                            <!--Start Alternative Details-->
                                            <div class="col-md-4 m-3">
                                                <button class="btn btn-success" v-on:click="isHidden = !isHidden" v-if="isHidden">{{ $t('global.Add Alternative') }}</button>
                                            </div>
                                            <div class="col-md-12 mb-3 mt-5 alternativeDetail-option" id="alternativeDetail" v-if="!isHidden">
                                                <div class="row account">
                                                    <div class="col-md-12 mb-12 head-account">
                                                        <h3>{{ $t('global.alternatives') }}</h3>
                                                    </div>
                                                    <div v-for="(it,index) in data.alternativeDetail" :key="it.id" class="col-md-12 mb-12 body-account row">
                                                        <!--Start Alternative-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.Alternative') }}</label>
                                                            <Select2 v-model.trim="it.alternative_id" :options="alternatives" :settings="{ width: '100%' }" />
                                                        </div>
                                                        <!--End Alternative-->

                                                        <!--Start Discount-->
                                                        <!-- <div class="col-md-4 mb-4">
                                                            <label>{{$t('global.Discount')}}</label>
                                                            <input type="number" step="0.1" class="form-control" v-model.number="it.discount" :placeholder="$t('global.Discount')">
                                                        </div> -->
                                                        <!--End Discount-->

                                                        <!--Start Pharmacy Price-->
                                                        <!-- <div class="col-md-4 mb-4">
                                                            <label>{{$t('global.Pharmacy Price')}}</label>
                                                            <input type="number" step="0.1" class="form-control" v-model.number="it.pharmacyPrice" :placeholder="$t('global.Pharmacy Price')">
                                                        </div> -->
                                                        <!--End Pharmacy Price-->

                                                        <!--Start Public Price-->
                                                        <!-- <div class="col-md-4 mb-4">
                                                            <label>{{$t('global.Public Price')}}</label>
                                                            <input type="number" step="0.1" class="form-control" v-model.number="it.publicPrice" :placeholder="$t('global.Public Price')">
                                                        </div> -->
                                                        <!--End Public Price-->

                                                        <div class="col-md-3 mb-3">
                                                            <button @click.prevent="addAlternativeDetail" v-if="(data.alternativeDetail.length-1) == index" class="btn btn-sm btn-success me-2 mt-5">
                                                                <i class="fas fa-clipboard-list"></i> {{$t('global.AddANewLine')}}
                                                            </button>
                                                            <button v-if="index" @click.prevent="deleteAlternativeDetail(index)" data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2 mt-5">
                                                                <i class="far fa-trash-alt"></i> {{$t('global.Delete')}}
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-offset-7 mb-3">
                                                <button class="btn btn-danger" v-on:click="isHidden = true"  v-if="!isHidden">{{ $t('global.Cancel Alternative') }}</button>
                                            </div>
                                            <!--End Alternative Details-->

                                        </div>

                                        <button class="btn btn-primary" type="submit">{{ $t("global.Submit") }}</button>
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
import {required,minLength,maxLength,numeric,integer} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "createProduct",
    data(){
        return {
            errors:{},
            isHidden: true,
        }
    },
    setup(){
        let loading = ref(false);
        let categories = ref([]);
        let subCategories = ref([]);
        let measures = ref([]);
        let taxes = ref([]);
        let pharmacistForms = ref([]);
        let sellingMethods = ref([]);
        let alternatives = ref([]);
        let alternativeDetailValidation = ref([{
            alternative_id: {
                // required,
                // numeric
            },
            discount: {
                // required,
                // numeric
            },
            pharmacyPrice: {
                // required,
                // numeric
            },
            publicPrice: {
                // required,
                // numeric
            }
        }]);

        //start design
        let addProduct =  reactive({
            data:{
                alternativeDetail: [
                    {
                        alternative_id: null,
                        discount: null,
                        pharmacyPrice: null,
                        publicPrice: null,
                    }
                ],
                nullValue: null,
                nameExist: false,
                nameAr: null,
                nameEn: null,
                pharmacistForm_id: null,
                barcode : null,
                description : null,
                effectiveMaterial: null,
                image : {},
                files : [],
                category_id: null,
                sub_category_id: null,
                main_measurement_unit_id: null,
                tax_id: null,
                selling_methods: [],
                alternativeDetails: [
                    { alternativeDetails: [], send: true }
                ]
            }
        });

        let getProduct= () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/product/create`)
                .then((res) => {
                    let l = res.data.data;
                    categories.value = l.categories;
                    measures.value = l.measures;
                    taxes.value = l.taxes;
                    pharmacistForms.value = l.pharmacistForms;
                    sellingMethods.value = l.sellingMethods;
                    alternatives.value = l.alternatives;
                })
                .catch((err) => {
                    this.errors = err.response.data.errors;
                    console.log(err.response);
                    Swal.fire({
                        icon: 'error',
                        title: 'يوجد خطأ...',
                        text: 'يوجد خطأ ما..!!',
                    });
                })
                .finally(() => {
                    loading.value = false;
                })
        };

        let getSubCategory= (id) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/category/${id}`)
            .then((res) => {
                let l = res.data.data;
                subCategories.value = l.subCategories;
            })
            .catch((err) => {
                this.errors = err.response.data.errors;
                console.log(err.response);
                Swal.fire({
                    icon: 'error',
                    title: 'يوجد خطأ...',
                    text: 'يوجد خطأ ما..!!',
                });
            })
            .finally(() => {
                loading.value = false;
            })
        };

        const rules = computed(() => {
            return {
                //
                alternativeDetail: [
                    ...alternativeDetailValidation.value
                ],
                nameAr: {
                    minLength: minLength(3),
                    maxLength: maxLength(70),
                    required,
                },
                nameEn: {
                    minLength: minLength(3),
                    maxLength: maxLength(70),
                    required,
                },
                pharmacistForm_id: {
                    required,
                },
                barcode: {
                    required,
                    integer
                },
                effectiveMaterial: {
                    required,
                },
                description: {},
                image: {
                    required
                },
                files: {
                    required
                },
                category_id: {
                    required,
                    integer
                },
                sub_category_id: {
                    required,
                    integer
                },
                main_measurement_unit_id: {
                    required,
                    integer
                },
                tax_id: {
                    // required,
                    // integer
                },
                selling_methods: {
                    required
                },
                //
                alternativeDetail: {
                    // required
                }
                //
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
            if(numberOfImage.value){
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


        onMounted(() => {
            getProduct();
        });

        return {
            loading,
            ...toRefs(addProduct),
            v$,
            preview,
            preview2,
            numberOfImage,
            numberOfImage1,
            categories,
            measures,
            taxes,
            subCategories,
            sellingMethods,
            pharmacistForms,
            getSubCategory,
            alternatives,
            alternativeDetailValidation,
        };
    },
    methods: {
        myFunction()
        {
            this.data.barcode = Math.round(Math.random()*10000000000);
        },

        storeProduct(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};
                let formData = new FormData();
                formData.append("pharmacistForm_id", this.data.pharmacistForm_id);
                formData.append('nameAr',this.data.nameAr);
                formData.append('nameEn',this.data.nameEn);
                formData.append('barcode',this.data.barcode);
                formData.append('effectiveMaterial',this.data.effectiveMaterial);
                formData.append('description',this.data.description);
                formData.append('category_id',this.data.category_id);
                formData.append('sub_category_id',this.data.sub_category_id);
                formData.append('main_measurement_unit_id',this.data.main_measurement_unit_id);
                formData.append('tax_id',this.data.tax_id);
                formData.append('image',this.data.image);
                formData.append('selling_methods',this.data.selling_methods);
                formData.append('alternativeDetail', JSON.stringify(this.data.alternativeDetail));
                for( var i = 0; i < this.numberOfImage1; i++ ){
                    let file = this.data.files[i];
                    formData.append('files[' + i + ']', file);
                }

                adminApi.post(`/v1/dashboard/product`,formData)
                //
                // adminApi.post(`/v1/dashboard/product`, this.data)

                .then((res) => {

                    notify({
                        title: `تم الاضافه بنجاح <i class="fas fa-check-circle"></i>`,
                        type: "success",
                        duration: 5000,
                        speed: 2000
                    });

                    this.resetForm();
                    this.$nextTick(() => { this.v$.$reset() });
                })
                .catch((err) => {
                    this.errors = err.response.data.errors;
                    console.log(err.response);
                    Swal.fire({
                        icon: 'error',
                        title: 'يوجد خطأ...',
                        text: 'يوجد خطأ ما..!!',
                    });
                })
                .finally(() => {
                    this.loading = false;
                });

            }
        },

        addAlternativeDetail() {
            this.data.alternativeDetail.push({
                alternative_id: null,
                discount: null,
                pharmacyPrice: null,
                publicPrice: null,
            });
            this.alternativeDetailValidation.push({
                alternative_id: {
                    // required,
                    // numeric
                },
                discount: {
                    // required,
                    // numeric
                },
                pharmacyPrice: {
                    // required,
                    // numeric
                },
                publicPrice: {
                    // required,
                    // numeric
                }
            });

            this.alternativeDetails.push({ alternativeDetails: [], send: true });
            this.$nextTick(() => { this.v$.$reset() });
        },

        deleteAlternativeDetail(index) {
            this.data.alternativeDetail.splice(index, 1);
            this.alternativeDetailValidation.splice(index, 1);
            this.alternativeDetails.splice(index, 1);
            this.$nextTick(() => { this.v$.$reset() });
        },

        resetForm(){
            document.querySelector('#container-images').innerHTML = '';
            document.querySelector('#container-images1').innerHTML = '';
            this.numberOfImage = 0;
            this.numberOfImage1 = 0;
            this.data.pharmacistForm_id = null;
            this.data.barcode = null;
            this.data.nameAr = null;
            this.data.nameEn = null;
            this.data.description = null;
            this.effectiveMaterial = null;
            this.data.image= {};
            this.data.files = [];
            this.data.category_id = null;
            this.data.sub_category_id = null;
            this.data.main_measurement_unit_id = null;
            this.data.tax_id = null;
            this.data.selling_methods = [];
            this.data.alternativeDetail = [
                {
                    alternative_id: null,
                    discount: null,
                    pharmacyPrice: null,
                    publicPrice: null,
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

.card {
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

.num-of-files {
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

.custom-textarea {
    height: 150px;
}

.account {
    background-color: #0E67D0;
    color: #000000 !important;
    border-radius: 5px;
}

.head-account {
    display: flex;
    justify-content: center;
}

.head-account h3 {
    color: #000000 !important;
    font-weight: bold;
}

.body-account {
    border-top: 3px solid #000000;
    margin: 0 !important;
}

.text-height {
    height: 46px !important;
}

.error-amount {
    display: flex;
    justify-content: center;
    color: red;
    margin: 10px;
}
</style>
