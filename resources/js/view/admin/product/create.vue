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

                                            <!--Start Company And Supplier Supplier-->
                                            <div class="col-md-6 mb-3">

                                                <!--Start Company Select-->
                                                <div id="company" class="col-md-12 mb-3" v-if="companyShow == true">
                                                    <label >{{ $t("global.Company") }}</label>
                                                    <select
                                                        name="type"
                                                        class="form-control"
                                                        v-model="v$.company_id.$model"
                                                        :class="{'is-invalid':v$.company_id.$error,'is-valid':!v$.company_id.$invalid}"
                                                    >
                                                        <option :value="data.nullValue">---</option>
                                                        <option v-for="company in companies" :key="company.id" :value="company.id">
                                                            {{ company.name }}
                                                        </option>
                                                    </select>
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
                                                    <select
                                                        name="type"
                                                        class="form-control"
                                                        v-model.trim="v$.supplier_id.$model"
                                                        :class="{'is-invalid':v$.supplier_id.$error,'is-valid':!v$.supplier_id.$invalid}"
                                                    >
                                                        <option :value="data.nullValue">---</option>
                                                        <option id="supplier-option" v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                                                            {{ supplier.name }}
                                                        </option>
                                                    </select>
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
                                                <select class="form-control" v-model.trim="v$.productName_id.$model">
                                                    <option v-for="productName in productNames" :key="productName.id" :value="productName.id">
                                                    {{ productName.nameAr }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!--End Product Name Select-->

                                            <!--Start Category Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.MainCategory") }}</label>
                                                <select @change="getSubCategory(v$.category_id.$model)"
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.category_id.$model"
                                                    :class="{'is-invalid':v$.category_id.$error,'is-valid':!v$.category_id.$invalid}"
                                                >
                                                    <option value="">---</option>
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
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.sub_category_id.$model"
                                                    :class="{'is-invalid':v$.sub_category_id.$error,'is-valid':!v$.sub_category_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="subCategory in subCategories" :key="subCategory.id" :value="subCategory.id" >
                                                        {{ subCategory.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_category_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End SubCategory Select-->

                                            <!--Start Main Measurement Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >وحدة القياس الرئيسية</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.main_measurement_unit_id.$model"
                                                    :class="{'is-invalid':v$.main_measurement_unit_id.$error,'is-valid':!v$.main_measurement_unit_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="measure in measures" :key="measure.id" :value="measure.id" >
                                                        {{ measure.name }}
                                                    </option>
                                                </select>
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
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.tax_id.$model"
                                                    :class="{'is-invalid':v$.tax_id.$error,'is-valid':!v$.tax_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="tax in taxes" :key="tax.id" :value="tax.id" >
                                                        {{ tax.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.tax_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Tax Select-->

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
                                            <!--Start MaxMount-->

                                            <!--Start Re Order Limit Select-->
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
                                            <!--End Re Order Limit Select-->

                                            <!--Start Selling Method Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.Selling Method") }}</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
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
            companyShow: true,
            supplierShow: false
        }
    },
    setup(){
        let loading = ref(false);
        let productNames = ref([]);
        let suppliers = ref([]);
        let companies = ref([]);
        let categories = ref([]);
        let subCategories = ref([]);
        let measures = ref([]);
        let taxes = ref([]);
        let sellingMethods = ref([]);

        //start design
        let addProduct =  reactive({
            data:{
                nullValue: null,
                productName_id: null,
                supplier_id: null,
                barcode : null,
                // count_unit : null,
                maximum_product: null,
                Re_order_limit: null,
                description : null,
                image : {},
                files : [],
                category_id: null,
                sub_category_id: null,
                company_id: null,
                main_measurement_unit_id: null,
                // sub_measurement_unit_id: null,
                tax_id: null,
                selling_methods: [],
            }
        });

        let getProduct= () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/product/create`)
                .then((res) => {
                    let l = res.data.data;
                    productNames.value = l.productNames;
                    suppliers.value = l.suppliers;
                    companies.value = l.companies;
                    categories.value = l.categories;
                    measures.value = l.measures;
                    taxes.value = l.taxes;
                    sellingMethods.value = l.sellingMethods;
                })
                .catch((err) => {
                    console.log(err.response);
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
                supplier_id: {
                    //required,
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
                company_id: {
                    //required,
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
            companies,
            categories,
            measures,
            taxes,
            subCategories,
            sellingMethods,
            productNames,
            suppliers,
            getSubCategory,
        };
    },
    methods: {
        myFunction()
        {
            this.data.barcode = Math.round(Math.random()*10000000000);
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

        storeProduct(){
            console.log("Tested Here !!!!")
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};
                let formData = new FormData();
                formData.append("productName_id", this.data.productName_id);
                formData.append("supplier_id", this.data.supplier_id);
                formData.append('barcode',this.data.barcode);
                // formData.append('count_unit',this.data.count_unit);
                formData.append('maximum_product',this.data.maximum_product);
                formData.append('Re_order_limit',this.data.Re_order_limit);
                formData.append('description',this.data.description);
                formData.append('category_id',this.data.category_id);
                formData.append('sub_category_id',this.data.sub_category_id);
                formData.append('company_id',this.data.company_id);
                // formData.append('sub_measurement_unit_id',this.data.sub_measurement_unit_id);
                formData.append('main_measurement_unit_id',this.data.main_measurement_unit_id);
                formData.append('tax_id',this.data.tax_id);
                formData.append('image',this.data.image);
                formData.append('selling_methods',this.data.selling_methods);
                for( var i = 0; i < this.numberOfImage1; i++ ){
                    let file = this.data.files[i];
                    formData.append('files[' + i + ']', file);
                }

                adminApi.post(`/v1/dashboard/product`,formData)
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
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        resetForm(){
            document.querySelector('#container-images').innerHTML = '';
            document.querySelector('#container-images1').innerHTML = '';
            this.numberOfImage = 0;
            this.numberOfImage1 = 0;
            this.data.productName_id = null;
            this.data.supplier_id = null;
            this.data.barcode = null;
            // this.data.count_unit = null;
            this.data.maximum_product= null;
            this.data.Re_order_limit = null;
            this.data.description = null;
            this.data.image= {};
            this.data.files = [];
            this.data.category_id = null;
            this.data.sub_category_id = null;
            this.data.company_id = null;
            this.data.main_measurement_unit_id = null;
            // this.data.sub_measurement_unit_id = null;
            this.data.tax_id = null;
            this.data.selling_methods = [];
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
.custom-textarea {
    height: 150px;
}

</style>
