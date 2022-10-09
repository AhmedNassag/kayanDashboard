<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.Kayan Price") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexKayanPrice'}">{{ $t("global.Kayan Price") }}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t("kayanPrice.CreateKayanPrice") }}</li>
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
                                <router-link :to="{name: 'indexKayanPrice'}" class="btn btn-custom btn-dark">{{ $t("global.back") }}</router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="storeKayanPrice" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Start Company And Supplier Supplier-->
                                            <div class="col-md-6 mb-3">

                                                <!--Start Company Select-->
                                                <!-- <div id="company" class="col-md-12 mb-3" v-if="companyShow == true">
                                                    <label >{{ $t("global.Company") }}</label>
                                                    <Select2 v-model="v$.company_id.$model" :options="companies" :settings="{ width: '100%' }" />
                                                    <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                    <div class="invalid-feedback">
                                                    </div>
                                                    <input id ="myButton1" class="btn btn-secondary btn-ms" type="button" v-on:click="showSupplier()" value="مورد"/>
                                                </div> -->
                                                <!--End Company Select-->

                                                <!--Start Supplier Select-->
                                                <div id="supplier" class="col-md-12 mb-3">
                                                    <label for="validationCustom0">
                                                        {{ $t("global.Supplier") }}
                                                    </label>
                                                    <Select2 v-model="v$.supplier_id.$model" :options="suppliers" :settings="{ width: '100%' }" />
                                                    <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                    <div class="invalid-feedback">
                                                        <!-- <span v-if="v$.supplier_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /> </span> -->
                                                    </div>
                                                    <!-- <input id ="myButton2" class="btn btn-secondary btn-ms" type="button" v-on:click="showCompany()" value="شركة"/> -->
                                                </div>
                                                <!--End Supplier Select-->

                                            </div>
                                            <!--End Company And Supplier Supplier-->

                                            <!--Start Category Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.MainCategory") }}</label>
                                                <!-- <Select2 @change="getSubCategory(v$.category_id.$model)" v-model="v$.category_id.$model" :options="categories" :settings="{ width: '100%' }" /> -->
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
                                                <Select2 v-model="v$.sub_category_id.$model" :options="subCategories" :settings="{ width: '100%' }" />
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_category_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End SubCategory Select-->

                                            <!--Start Product Name Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom00">
                                                    {{ $t("global.Product") }}
                                                </label>
                                                <Select2 v-model.trim="v$.product_id.$model" :options="products" :settings="{ width: '100%' }" />
                                            </div>
                                            <!--End Product Name Select-->

                                            <!--Start Product Price-->
                                            <!-- <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Product Price") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.productPrice.$model"
                                                    :placeholder="$t('global.Product Price')"
                                                    :class="{'is-invalid':v$.productPrice.$error,'is-valid':!v$.productPrice.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.productPrice.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div> -->
                                            <!--End Product Price-->

                                            <!--Start Maximum Limit-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Maximum Limit") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.maximumLimit.$model"
                                                    :placeholder="$t('global.Maximum Limit')"
                                                    :class="{'is-invalid':v$.maximumLimit.$error,'is-valid':!v$.maximumLimit.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.maximumLimit.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Maximum Limit-->

                                            <!--Start ReOrder Limit-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.ReOrder Limit") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.reOrderLimit.$model"
                                                    :placeholder="$t('global.Maximum Limit')"
                                                    :class="{'is-invalid':v$.reOrderLimit.$error,'is-valid':!v$.reOrderLimit.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.reOrderLimit.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End ReOrder Limit-->

                                            <!--Start Pharmacy Price-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Pharmacy Price") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.pharmacyPrice.$model"
                                                    :placeholder="$t('global.Pharmacy Price')"
                                                    :class="{'is-invalid':v$.pharmacyPrice.$error,'is-valid':!v$.pharmacyPrice.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.pharmacyPrice.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Pharmacy Price-->

                                            <!--Start Public Price-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Public Price") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.publicPrice.$model"
                                                    :placeholder="$t('global.Public Price')"
                                                    :class="{'is-invalid':v$.publicPrice.$error,'is-valid':!v$.publicPrice.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.publicPrice.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Public Price-->

                                            <!--Start Collection Price-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Collection Price") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.collectionPrice.$model"
                                                    :placeholder="$t('global.Collection Price')"
                                                    :class="{'is-invalid':v$.collectionPrice.$error,'is-valid':!v$.collectionPrice.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.collectionPrice.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Collection Price-->

                                            <!--Start Partial Price-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Partial Price") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.partialPrice.$model"
                                                    :placeholder="$t('global.Partial Price')"
                                                    :class="{'is-invalid':v$.partialPrice.$error,'is-valid':!v$.partialPrice.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.partialPrice.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Partial Price-->

                                            <!--Start Destribution Price-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Destribution Price") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.destributionPrice.$model"
                                                    :placeholder="$t('global.Destribution Price')"
                                                    :class="{'is-invalid':v$.destributionPrice.$error,'is-valid':!v$.destributionPrice.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.destributionPrice.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Destribution Price-->

                                            <!--Start Product Discount-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Product Discount") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.productDiscount.$model"
                                                    :placeholder="$t('global.Product Discount')"
                                                    :class="{'is-invalid':v$.productDiscount.$error,'is-valid':!v$.productDiscount.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.productDiscount.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Product Discount-->

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
    name: "createKayanPrice",
    data(){
        return {
            errors:{},
            // companyShow: true,
            // supplierShow: false
        }
    },
    setup(){
        let loading = ref(false);
        let products = ref([]);
        let suppliers = ref([]);
        // let companies = ref([]);
        let categories = ref([]);
        let subCategories = ref([]);

        //start design
        let addKayanPrice =  reactive({
            data:{
                nullValue: null,
                product_id: null,
                supplier_id: null,
                category_id: null,
                sub_category_id: null,
                // company_id: null,
                maximumLimit: null,
                reOrderLimit: null,
                pharmacyPrice: null,
                publicPrice : null,
                productDiscount: null,
                collectionPrice: null,
                partialPrice: null,
                destributionPrice: null,
            }
        });

        let getKayanPrice= () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/kayanPrice/create`)
                .then((res) => {
                    let l = res.data.data;
                    products.value = l.products;
                    suppliers.value = l.suppliers;
                    // companies.value = l.companies;
                    categories.value = l.categories;
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

        // let getProduct = (category_id,sub_category_id,supplier_id,company_id) => {
        //     loading.value = true;
        //     adminApi.get(`/v1/dashboard/kayanPrice/getProduct?category_id=${category_id}&sub_category_id=${sub_category_id}&company_id=${company_id}&supplier_id=${supplier_id}`)
        //         .then((res) => {
        //             let l = res.data.data;
        //             products = l.products;
        //         })
        //         .catch((err) => {
        //             console.log(err.response.data);
        //         })
        //         .finally(() => {
        //             loading.value = false;
        //         });
        // };

        const rules = computed(() => {
            return {
                product_id: {
                    required,
                },
                supplier_id: {
                    //required,
                },
                // company_id: {
                //     //required,
                // },
                category_id: {
                    required,
                    integer
                },
                sub_category_id: {
                    required,
                    integer
                },
                maximumLimit: {
                    required,
                },
                reOrderLimit: {
                    required,
                },
                pharmacyPrice: {
                    required,
                },
                publicPrice: {
                    required,
                },
                productDiscount: {
                    required,
                },
                collectionPrice: {
                    required,
                },
                partialPrice: {
                    required,
                },
                destributionPrice: {
                    required,
                },
            }
        });

        const v$ = useVuelidate(rules,addKayanPrice.data);

        onMounted(() => {
            getKayanPrice();
        });

        return {
            loading,
            ...toRefs(addKayanPrice),
            v$,
            products,
            suppliers,
            // companies,
            categories,
            subCategories,
            getSubCategory,
            // getProduct,
        };
    },
    methods: {
        // showCompany()
        // {
        //     this.companyShow = true;
        //     this.supplierShow = false;
        // },
        // showSupplier()
        // {
        //     this.supplierShow = true;
        //     this.companyShow = false;
        // },

        storeKayanPrice(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};
                let formData = new FormData();

                formData.append("product_id", this.data.product_id);
                formData.append("supplier_id", this.data.supplier_id);
                // formData.append('company_id',this.data.company_id);
                formData.append('category_id',this.data.category_id);
                formData.append('sub_category_id',this.data.sub_category_id);

                formData.append('maximumLimit',this.data.maximumLimit);
                formData.append('reOrderLimit',this.data.reOrderLimit);
                formData.append('pharmacyPrice',this.data.pharmacyPrice);
                formData.append('publicPrice',this.data.publicPrice);
                formData.append('productDiscount',this.data.productDiscount);
                formData.append('collectionPrice',this.data.collectionPrice);
                formData.append('partialPrice',this.data.partialPrice);
                formData.append('destributionPrice',this.data.destributionPrice);

                adminApi.post(`/v1/dashboard/kayanPrice`,formData)
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
            this.data.product_id = null;
            // this.data.company_id = null;
            this.data.supplier_id = null;
            this.data.category_id = null;
            this.data.sub_category_id = null;
            this.data.pharmacyPrice = null;
            this.data.publicPrice = null;
            this.data.productDiscount = null;
            this.data.collectionPrice = null;
            this.data.partialPrice = null;
            this.data.destributionPrice = null;
            this.data.maximumLimit = null;
            this.data.reOrderLimit = null;
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
