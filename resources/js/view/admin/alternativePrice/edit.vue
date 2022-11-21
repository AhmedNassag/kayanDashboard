<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.Alternative Price") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexAlternativePrice'}">{{ $t("global.Alternative Price") }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("alternativePrice.EditAlternativePrice") }}</li>
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
                                    :to="{name: 'indexAlternativePrice'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{ $t("global.back") }}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="editAlternativePrice" class="needs-validation">

                                        <div class="form-row row">

                                            <!--Start Company And Supplier Supplier-->
                                            <div class="col-md-6 mb-3">

                                                <!--Start Supplier Select-->
                                                <div id="supplier" class="col-md-12 mb-3">
                                                    <label for="validationCustom0">
                                                        {{ $t("global.Supplier") }}
                                                    </label>
                                                    <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.supplier_id.$model"
                                                    :class="{'is-invalid':v$.supplier_id.$error,'is-valid':!v$.supplier_id.$invalid}"
                                                    disabled
                                                >
                                                    <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id" >
                                                        {{ supplier.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                    </div>
                                                <!--End Supplier Select-->

                                            </div>
                                            <!--End Supplier Select-->

                                            <!--Start Product Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom00">
                                                    {{ $t("global.Alternative") }}
                                                </label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.alternative_id.$model"
                                                    :class="{'is-invalid':v$.alternative_id.$error,'is-valid':!v$.alternative_id.$invalid}"
                                                    disabled
                                                >
                                                    <option v-for="alternative in alternatives" :key="alternative.id" :value="alternative.id" >
                                                        {{ alternative.nameAr }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!--End Product Name Select-->

                                            <!--Start Category Select-->
                                            <!-- <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.MainCategory") }}</label>
                                                <select @change="getSubCategory(v$.category_id.$model)"
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.category_id.$model"
                                                    :class="{'is-invalid':v$.category_id.$error,'is-valid':!v$.category_id.$invalid}"
                                                    disabled
                                                >
                                                    <option v-for="category in categories" :key="category.id" :value="category.id" >
                                                        {{ category.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.category_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div> -->
                                            <!--End Category Select-->

                                            <!--Start SubCategory Select-->
                                            <!-- <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.SubCategory") }}</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.sub_category_id.$model"
                                                    :class="{'is-invalid':v$.sub_category_id.$error,'is-valid':!v$.sub_category_id.$invalid}"
                                                    disabled
                                                >
                                                    <option v-for="subCategory in subCategories" :key="subCategory.id" :value="subCategory.id" >
                                                        {{ subCategory.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_category_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div> -->
                                            <!--End SubCategory Select-->

                                            <!--Start Pharmacy Price-->
                                            <!-- <div class="col-md-6 mb-3">
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
                                            </div> -->
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

                                            <!--Start Client Discount-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Client Discount") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.clientDiscount.$model"
                                                    :placeholder="$t('global.Client Discount')"
                                                    :class="{'is-invalid':v$.clientDiscount.$error,'is-valid':!v$.clientDiscount.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.clientDiscount.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Client Discount-->

                                            <!--Start Kayan Discount-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t("global.Kayan Discount") }}</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.kayanDiscount.$model"
                                                    :placeholder="$t('global.Kayan Discount')"
                                                    :class="{'is-invalid':v$.kayanDiscount.$error,'is-valid':!v$.kayanDiscount.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.kayanDiscount.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Kayan Discount-->

                                            <!--Start Kayan Profit-->
                                            <!--End Kayan Profit-->

                                        </div>

                                        <button class="btn btn-primary" type="submit">{{$t('global.Submit')}}</button>
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
    name: "editAlternativePrice",
    data(){
        return {
            errors:{},
            // companyShow: true,
            // supplierShow: false
        }
    },
    props:["id"],
    setup(props){

        const {id} = toRefs(props)
        // get create Package
        let loading = ref(false);
        let alternatives = ref([]);
        let suppliers = ref([]);
        let categories = ref([]);
        let subCategories = ref([]);

        //start design
        let addAlternativePrice =  reactive({
            data:{
                nullValue: null,
                alternative_id: null,
                supplier_id: null,
                // category_id: null,
                // sub_category_id: null,
                // pharmacyPrice: null,
                publicPrice : null,
                clientDiscount: null,
                kayanDiscount : null,
                // kayanProfit: null,
            }
        });

        let getAlternativePrice = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/alternativePrice/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addAlternativePrice.data.alternative_id = l.alternativePrice.alternative_id;
                    addAlternativePrice.data.supplier_id = l.alternativePrice.supplier_id;
                    // addAlternativePrice.data.category_id = l.alternativePrice.category_id;
                    // addAlternativePrice.data.sub_category_id = l.alternativePrice.sub_category_id;
                    // addPrice.data.pharmacyPrice = l.price.pharmacyPrice;
                    addAlternativePrice.data.publicPrice = l.alternativePrice.publicPrice;
                    addAlternativePrice.data.clientDiscount = l.alternativePrice.clientDiscount;
                    addAlternativePrice.data.kayanDiscount = l.alternativePrice.kayanDiscount;
                    addAlternativePrice.data.kayanProfit = l.alternativePrice.kayanProfit;

                    alternatives.value = l.alternatives;
                    suppliers.value = l.suppliers;
                    categories.value = l.categories;
                    getSubCategory(l.alternative.category_id);
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        };

        onMounted(() => {
            getAlternativePrice();
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
                alternative_id: {
                    // required,
                },
                supplier_id: {
                    //required,
                },
                // category_id: {
                //     required,
                //     integer
                // },
                // sub_category_id: {
                //     required,
                //     integer
                // },
                // pharmacyPrice: {
                //     required,
                // },
                publicPrice: {
                    required,
                },
                clientDiscount: {
                    required,
                },
                kayanDiscount: {
                    required,
                },
                // kayanProfit: {
                //     required,
                // },
            }
        });

        const v$ = useVuelidate(rules,addAlternativePrice.data);

        return {
            loading,
            ...toRefs(addAlternativePrice),
            v$,
            alternatives,
            suppliers,
            categories,
            subCategories,
            getSubCategory,
            id
        };
    },
    methods: {

        editAlternativePrice(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                let formData = new FormData();
                formData.append("alternative_id", this.data.alternative_id);
                formData.append("supplier_id", this.data.supplier_id);
                // formData.append('category_id',this.data.category_id);
                // formData.append('sub_category_id',this.data.sub_category_id);
                // formData.append('pharmacyPrice',this.data.pharmacyPrice);
                formData.append('publicPrice',this.data.publicPrice);
                formData.append('clientDiscount',this.data.clientDiscount);
                formData.append('kayanDiscount',this.data.kayanDiscount);
                formData.append('kayanProfit',this.data.kayanProfit);
                formData.append('_method','PUT');

                adminApi.post(`/v1/dashboard/alternativePrice/${this.id}`,formData)
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
