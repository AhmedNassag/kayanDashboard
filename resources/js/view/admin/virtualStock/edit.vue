<template>
    <div
      :class="[
        'page-wrapper',
        this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
      ]"
    >

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar' ? 'top left' : 'top right'"/>

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.Virtual Stock") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexVirtualStock'}">{{ $t("global.Virtual Stock") }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("virtualStock.EditVirtualStock") }}</li>
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
                                    :to="{name: 'indexVirtualStock'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{ $t("global.back") }}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div
                                        class="alert alert-danger text-center"
                                        v-if="errors['name']"
                                    >
                                        {{ t("global.Exist", {field:t("global.Name")}) }} <br />
                                    </div>
                                    <form @submit.prevent="editVirtualStock" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Start Category Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom07">
                                                    {{ $t("global.Category") }}
                                                </label>
                                                <select @change="getSubCategory(v$.category_id.$model)" class="form-control" v-model.trim="v$.category_id.$model">
                                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                                        {{ category.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!--End Category Select-->

                                            <!--Start SubCategory Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom0">
                                                    {{ $t("global.SubCategory") }}
                                                </label>
                                                <select class="form-control" v-model.trim="v$.sub_category_id.$model">
                                                    <option v-for="subCategory in subCategories" :key="subCategory.id" :value="subCategory.id">
                                                        {{ subCategory.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!--End SubCategory Select-->

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

                                            <!--Start Product Quantity-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">
                                                    {{ $t("global.Product Quantity") }}
                                                </label>
                                                <input
                                                type="number"
                                                class="form-control"
                                                v-model.trim="v$.productQuantity.$model"
                                                id="validationCustom05"
                                                :placeholder="$t('global.Product Quantity')"
                                                :class="{
                                                    'is-invalid': v$.productQuantity.$error,
                                                    'is-valid': !v$.productQuantity.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.productQuantity.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Product Quantity-->

                                            <!--Start Pharmacy Price-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">
                                                    {{ $t("global.Pharmacy Price") }}
                                                </label>
                                                <input
                                                type="number"
                                                class="form-control"
                                                v-model.trim="v$.pharmacyPrice.$model"
                                                id="validationCustom05"
                                                :placeholder="$t('global.Pharmacy Price')"
                                                :class="{
                                                    'is-invalid': v$.pharmacyPrice.$error,
                                                    'is-valid': !v$.pharmacyPrice.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.pharmacyPrice.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Pharmacy Price-->

                                            <!--Start Public Price-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">
                                                    {{ $t("global.Public Price") }}
                                                </label>
                                                <input
                                                type="number"
                                                class="form-control"
                                                v-model.trim="v$.publicPrice.$model"
                                                id="validationCustom05"
                                                :placeholder="$t('global.Public Price')"
                                                :class="{
                                                    'is-invalid': v$.publicPrice.$error,
                                                    'is-valid': !v$.publicPrice.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.publicPrice.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Public Price-->

                                            <!--Start Pharmacy Discount-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">
                                                    {{ $t("global.Pharmacy Discount") }}
                                                </label>
                                                <input
                                                type="number"
                                                class="form-control"
                                                v-model.trim="v$.pharmacyDiscount.$model"
                                                id="validationCustom05"
                                                :placeholder="$t('global.Pharmacy Discount')"
                                                :class="{
                                                    'is-invalid': v$.pharmacyDiscount.$error,
                                                    'is-valid': !v$.pharmacyDiscount.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.pharmacyDiscount.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Pharmacy Discount-->

                                            <!--Start Kayan Discount-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">
                                                    {{ $t("global.Kayan Discount") }}
                                                </label>
                                                <input
                                                type="number"
                                                class="form-control"
                                                v-model.trim="v$.kayanDiscount.$model"
                                                id="validationCustom05"
                                                :placeholder="$t('global.Kayan Discount')"
                                                :class="{
                                                    'is-invalid': v$.kayanDiscount.$error,
                                                    'is-valid': !v$.kayanDiscount.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.kayanDiscount.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Kayan Discount-->

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
import { computed, onMounted, reactive, toRefs, inject, ref } from "vue";
import useVuelidate from "@vuelidate/core";
import {required, minLength, maxLength, integer} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import { useI18n } from "vue-i18n";

export default {
    name: "editVirtualStock",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        //
        const emitter = inject("emitter");
        const { id } = toRefs(props);
        const { t } = useI18n({});
        //

        // get create Package
        let loading = ref(false);
        let productNames = ref([]);
        let categories = ref([]);
        let subCategories = ref([]);

        let getVirtualStock = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/virtualStock/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;

                    addVirtualStock.data.productQuantity = l.virtualStock.productQuantity;
                    addVirtualStock.data.pharmacyPrice = l.virtualStock.pharmacyPrice;
                    addVirtualStock.data.publicPrice = l.virtualStock.publicPrice;
                    addVirtualStock.data.pharmacyDiscount = l.virtualStock.pharmacyDiscount;
                    addVirtualStock.data.kayanDiscount = l.virtualStock.kayanDiscount;
                    addVirtualStock.data.supplier_id = l.virtualStock.supplier_id;
                    addVirtualStock.data.category_id = l.virtualStock.category_id;
                    addVirtualStock.data.sub_category_id = l.virtualStock.sub_category_id;
                    addVirtualStock.data.productName_id  = l.virtualStock.productName_id ;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getVirtualStock();
        });

        //start design
        let addVirtualStock =  reactive({
            data:{
                productQuantity: "",
                pharmacyPrice: "",
                publicPrice: "",
                pharmacyDiscount: "",
                kayanDiscount:"",
                // supplier_id: "",
                category_id:"",
                sub_category_id:"",
                productName_id:"",
            }
        });

        getProductNames();
        getCategories();
        // getSubCategories();
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
                productQuantity: {
                    required,
                },
                pharmacyPrice: {
                    required,
                },
                publicPrice: {
                    required,
                },
                pharmacyDiscount: {
                    required,
                },
                kayanDiscount: {
                    required,
                },
                category_id:{
                    required,
                },
                sub_category_id:{
                    required,
                },
                productName_id:{
                    required,
                },
                // supplier_id:{
                //     required,
                // },

            };
        });

        const v$ = useVuelidate(rules,addVirtualStock.data);

        //Commons
        function getProductNames()
        {
            adminApi
            .get(`/v1/dashboard/getProductNames`)
            .then((res) => {
                productNames.value =res.data.data.productNames ;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
        }

        function getCategories()
        {
            adminApi
            .get(`/v1/dashboard/getCategories`)
            .then((res) => {
                categories.value =res.data.data.categories ;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
        }

        // function getSubCategories()
        // {
        //     adminApi
        //     .get(`/v1/dashboard/getSubCategories`)
        //     .then((res) => {
        //         subCategories.value =res.data.data.subCategories ;
        //     })
        //     .catch((err) => {
        //         console.log(err.response.data);
        //     })
        //     .finally(() => {
        //         loading.value = false;
        //     });
        // }
        //end common

        return {id,loading,...toRefs(addVirtualStock),v$,getSubCategory,categories,subCategories,productNames};
    },
    methods: {
        editVirtualStock(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                let formData = new FormData();
                formData.append("productQuantity", this.data.productQuantity);
                formData.append("pharmacyPrice", this.data.pharmacyPrice);
                formData.append("publicPrice", this.data.publicPrice);
                formData.append("pharmacyDiscount", this.data.pharmacyDiscount);
                formData.append("kayanDiscount", this.data.kayanDiscount);
                // formData.append("supplier_id", this.data.supplier_id);
                formData.append("category_id", this.data.category_id);
                formData.append("sub_category_id", this.data.sub_category_id);
                formData.append("productName_id", this.data.productName_id);
                formData.append('_method','PUT');

                adminApi.post(`/v1/dashboard/virtualStock/${this.id}`,formData)
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
</style>
