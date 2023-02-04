<template>
    <div :class="['page-wrapper', 'page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'" />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.Virtual Stock") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{ name: 'indexVirtualStock' }">
                                    {{ $t("global.Virtual Stock") }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">
                                {{ $t("virtualStock.CreateVirtualStock") }}
                            </li>
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
                                <div class="row justify-content-between">
                                    <div class="col-5">
                                        <router-link :to="{ name: 'indexVirtualStock' }"
                                            class="btn btn-custom btn-dark">
                                            {{ $t("global.back") }}
                                        </router-link>

                                    </div>

                                    <div class="col-5 row justify-content-end">
                                        <form id="mainFormVirualStocks">
                                            <div class="form-group">
                                                <table class="table">
                                                    <a href="/Temp.xlsx" download class="btn btn-info"><i
                                                            class="fa fa-download"></i></a>

                                                    <label class="form-group">{{ $t('global.UploadExcelFile') }}
                                                        <input class="form-control" type="file"
                                                            name="select_virtualStocks_file">
                                                    </label>
                                                    <button class="btn btn-success" style="margin:5px" type="submit"
                                                        name="upload" value="تأكيد"
                                                        @click.prevent="saveExcelVirtualStock">{{
                                                            $t('global.Add')
                                                        }}</button>
                                                </table>
                                            </div>
                                        </form>

                                        <!-- <router-link
                                            :to="{name: 'importVirtualStock'}"
                                            class="btn btn-custom btn-success">
                                            {{ $t('global.Import') }}
                                        </router-link> -->

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="message.length > 0">{{
                                        message
                                    }}<br /></div>

                                    <form @submit.prevent="storeVirtualStock" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Start Sale Products-->
                                            <div class="col-md-12 mb-12">
                                                <div class="row account">
                                                    <div class="col-md-12 mb-12 head-account">
                                                        <h3>{{ $t('global.Products') }}</h3>
                                                    </div>
                                                    <div v-for="(it, index) in data.product" :key="it.id"
                                                        class="col-md-12 mb-12 body-account row">

                                                        <!--Start Category-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.mainCategory') }}</label>
                                                            <select @change="getSubCategory(it.category_id, index)"
                                                                v-model="it.category_id"
                                                                :class="['form-select', { 'is-invalid': v$.product[index].category_id.$error, 'is-valid': !v$.product[index].category_id.$invalid }]">
                                                                <option v-for="category in categories"
                                                                    :key="category.id" :value="category.id">
                                                                    {{ category.name }}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{ $t('global.LooksGood') }}
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                <span
                                                                    v-if="v$.product[index].category_id.required.$invalid">{{
                                                                        $t('global.ThisFieldIsRequired')
                                                                    }}<br />
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <!--End Category-->

                                                        <!--Start Sub Categoy-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.subCategory') }}</label>
                                                            <select
                                                                @change="getProduct(it.category_id, it.sub_category_id, index)"
                                                                v-model="it.sub_category_id"
                                                                :class="['form-select', { 'is-invalid': v$.product[index].sub_category_id.$error, 'is-valid': !v$.product[index].sub_category_id.$invalid }]">
                                                                <option
                                                                    v-for="category in subCategory[index].subCategory"
                                                                    :key="category.id" :value="category.id">
                                                                    {{ category.name }}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{ $t('global.LooksGood') }}
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                <span
                                                                    v-if="v$.product[index].sub_category_id.required.$invalid">{{
                                                                        $t('global.ThisFieldIsRequired')
                                                                    }}<br /></span>
                                                            </div>
                                                        </div>
                                                        <!--End Sub Category-->

                                                        <!--Start Product @change="getMeasurementUnit(it.product_id,index)"-->
                                                        <div class="col-md-3 mb-3 ">
                                                            <label>{{ $t('global.Products') }}</label>







                                                            <!-- <Select2 v-model="it.product_id" :options="products[index].products" :settings="{ width: '100%',closeOnSelect:false }"/> -->

                                                            <div class="dropdown">
                                                                <button class="btn btn-secondary dropdown-toggle w-100"
                                                                    type="button" id="dropdownMenuButton"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    <span v-if="it.product_id">
                                                                        <img :src="it.image ? '/upload/product/' + it.image : '/admin/img/Logo Dashboard.png'"
                                                                            alt="product-image" style="
                                                                                width: 50px;
                                                                                height: 50px;
                                                                                border-radius: 50%;
                                                                            " />
                                                                        {{ it.name }}</span>
                                                                    <span v-else>{{
                                                                        $t("global.Product")
                                                                    }}</span>
                                                                </button>
                                                                <div :class="[
                                                                    'dropdown-menu bg-white',
                                                                    this.$i18n.locale == 'en' ? 'drop_ltr' : '',
                                                                ]" style="
                                                                height: 400px;
                                                                    overflow-y: scroll;
                                                                    width: 650px;
                                                                    z-index: 999999;
                                                                    left:544px!important;
                                                                    background:#e0e9e2
                                                                " aria-labelledby="dropdownMenuButton">
                                                                    <input type="text"
                                                                        :placeholder="$t('global.Search')"
                                                                        v-model="altr_search" class="form-control"
                                                                        onchange="event.stopPropagation()" />
                                                                    <loader v-if="loading2" />

                                                                    <div v-for="altr in products[index].products"
                                                                        :key="altr.id" :class="[
                                                                            'dropdown-item px-2 d-flex justify-content-between',
                                                                            altr.id == it.product_id
                                                                                ? 'bg-secondary'
                                                                                : '',
                                                                        ]" @click="it.product_id = altr.id;it.name = altr.nameAr;it.image = altr.image;">
                                                                        <img :src="altr.image ? '/upload/product/' + altr.image : '/admin/img/Logo Dashboard.png'"
                                                                            alt="product-image"
                                                                            style="width: 50px; height: 50px" />
                                                                        <span style="
                                                                            overflow: hidden;
                                                                            height: 34px;
                                                                            font-size: 24px;
                                                                            word-break: break-word;
                                                                        ">{{ altr.nameAr }}</span>
                                                                    </div>

                                                                    <h5 v-if="
                                                                        Object.keys(products[index].products ?? []).length ==
                                                                        0
                                                                    " class="text-center">
                                                                        {{ $t("global.No Data Found") }}
                                                                    </h5>
                                                                </div>
                                                            </div>







                                                            <!-- <select v-model="it.product_id" :class="['form-select',{'is-invalid':v$.product[index].product_id.$error,'is-valid':!v$.product[index].product_id.$invalid}]">
                                                                <option v-for="category in products[index].products" :key="category.id" :value="category.id">{{category.name}}</option>
                                                            </select> -->
                                                            <div class="valid-feedback">{{ $t('global.LooksGood') }}
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                <span
                                                                    v-if="v$.product[index].product_id.required.$invalid">{{
                                                                        $t('global.ThisFieldIsRequired')
                                                                    }}<br />
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <!--End Product-->

                                                        <!--Start Quantity-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.Quantity') }}</label>
                                                            <input type="number" class="form-control"
                                                                v-model.number="v$.product[index].quantity.$model"
                                                                :placeholder="$t('global.Quantity')"
                                                                :class="{ 'is-invalid': v$.product[index].quantity.$error, 'is-valid': !v$.product[index].quantity.$invalid }">
                                                            <div class="valid-feedback">{{ $t('global.LooksGood') }}
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                <span
                                                                    v-if="v$.product[index].quantity.required.$invalid">{{
                                                                        $t('global.ThisFieldIsRequired')
                                                                    }}<br />
                                                                </span>
                                                                <span
                                                                    v-if="v$.product[index].quantity.numeric.$invalid">{{
                                                                        $t('global.ThisFieldIsNumeric')
                                                                    }}
                                                                    <br /></span>
                                                            </div>
                                                        </div>
                                                        <!--End Quantity-->

                                                        <!--Start Public Price-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.Public Price') }}</label>
                                                            <input type="text" class="form-control"
                                                                v-model.number="data.product[index].publicPrice"
                                                                :placeholder="$t('global.Public Price')">
                                                        </div>
                                                        <!--End Public Price-->

                                                        <!--Start Client Discount-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.Pharmacy Discount') }}</label>
                                                            <input type="number" step="0.1" class="form-control"
                                                                v-model.number="v$.product[index].clientDiscount.$model"
                                                                :placeholder="$t('global.Pharmacy Discount')"
                                                                :class="{ 'is-invalid': v$.product[index].clientDiscount.$error, 'is-valid': !v$.product[index].clientDiscount.$invalid }">
                                                            <div class="valid-feedback">{{ $t('global.LooksGood') }}
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                <span
                                                                    v-if="v$.product[index].clientDiscount.required.$invalid">{{
                                                                        $t('global.ThisFieldIsRequired')
                                                                    }}<br />
                                                                </span>
                                                                <span
                                                                    v-if="v$.product[index].clientDiscount.numeric.$invalid">{{
                                                                        $t('global.ThisFieldIsNumeric')
                                                                    }}
                                                                    <br /></span>
                                                            </div>
                                                        </div>
                                                        <!--End Client Discount-->

                                                        <!--Start Kayan Discount -->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.Kayan Discount') }}</label>
                                                            <input type="number" step="0.1" class="form-control"
                                                                v-model.number="v$.product[index].kayanDiscount.$model"
                                                                :placeholder="$t('global.Kayan Discount')"
                                                                :class="{ 'is-invalid': v$.product[index].kayanDiscount.$error, 'is-valid': !v$.product[index].kayanDiscount.$invalid }">
                                                            <div class="valid-feedback">{{ $t('global.LooksGood') }}
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                <span
                                                                    v-if="v$.product[index].kayanDiscount.required.$invalid">{{
                                                                        $t('global.ThisFieldIsRequired')
                                                                    }}<br />
                                                                </span>
                                                                <span
                                                                    v-if="v$.product[index].kayanDiscount.numeric.$invalid">{{
                                                                        $t('global.ThisFieldIsNumeric')
                                                                    }}
                                                                    <br /></span>
                                                            </div>
                                                        </div>
                                                        <!--End Kayan Discount-->

                                                        <div class="col-md-3 mb-3">
                                                            <button @click.prevent="addDebit"
                                                                v-if="(data.product.length - 1) == index"
                                                                class="btn btn-sm btn-success me-2 mt-5">
                                                                <i class="fas fa-clipboard-list"></i>
                                                                {{ $t('global.AddANewLine') }}
                                                            </button>
                                                            <button v-if="index" @click.prevent="deleteDebit(index)"
                                                                data-bs-target="#staticBackdrop"
                                                                class="btn btn-sm btn-danger me-2 mt-5">
                                                                <i class="far fa-trash-alt"></i> {{
                                                                    $t('global.Delete')
                                                                }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!--End Sale Product-->

                                        </div>

                                        <button class="btn btn-primary mt-2" type="submit">{{
                                            $t('global.Submit')
                                        }}</button>
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
import { computed, onMounted, reactive, toRefs, inject, ref, watch } from "vue";
import useVuelidate from '@vuelidate/core';
import { required, minLength, between, maxLength, alpha, integer, numeric } from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import { useI18n } from "vue-i18n";


export default {
    name: "create",
    data() {
        return {
            errors: {},
        }
    },
    props: ["id"],
    setup(props) {
        const { t } = useI18n({});
        const { id } = toRefs(props);
        let loading = ref(false);
        let loading2 = ref(false);
        let message = ref('');
        let altr_search = ref('');
        let debounce = ref({});
        let selected_data = ref({
            category_id: '',
            sub_category_id: '',
            index: '',
        });
        let clients = ref([]);
        let stores = ref([]);
        let categories = ref([]);
        let productValidation = ref([{
            category_id: {
                required
            },
            sub_category_id: {
                required
            },
            product_id: {
                required
            },
            quantity: {
                required,
                numeric
            },
            publicPrice: {
                required,
                numeric
            },
            clientDiscount: {
                required,
                numeric
            },
            kayanDiscount: {
                required,
                numeric
            },
        }]);

        let getData = () => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/saleInvoice/create`)
                .then((res) => {
                    let l = res.data.data;
                    clients.value = l.clients;
                    stores.value = l.stores;
                    categories.value = l.categories;
                })
                .catch((err) => {
                    console.log(err.response.data);
                    this.errors = err.response.data.errors;
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ...',
                        text: `يوجد خطأ..!!`,
                    });
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getData();

        });

        watch(altr_search, () => {
            clearTimeout(debounce.value);
            debounce.value = setTimeout(async () => {
                await getProduct(selected_data.value.category_id, selected_data.value.sub_category_id, selected_data.value.index, altr_search.value)
            }, 500)
        })

        let getSubCategory = (id, index) => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/category/${id}`)
                .then((res) => {
                    let l = res.data.data;
                    addJob.subCategory[index].subCategory = l.subCategories;
                })
                .catch((err) => {
                    console.log(err.response.data);
                    this.errors = err.response.data.errors;
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ...',
                        text: `يوجد خطأ..!!`,
                    });
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        let getProduct = (category_id, sub_category_id, index, search_key = '') => {
            selected_data.value = {
                category_id,
                sub_category_id,
                index,
            }
            loading.value = true;
            loading2.value = true;
            adminApi.get(`/v1/dashboard/purchaseInvoiceProduct2?category_id=${category_id}&sub_category_id=${sub_category_id}&supplier_id=${parseInt(id.value)}&search=${search_key}`)
                .then((res) => {
                    let l = res.data.data;
                    addJob.products[index].products = l.products;
                })
                .catch((err) => {
                    console.log(err.response.data);
                    this.errors = err.response.data.errors;
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ...',
                        text: `يوجد خطأ..!!`,
                    });
                })
                .finally(() => {
                    loading.value = false;
                    loading2.value = false;
                });
        }

        let addJob = reactive({
            data: {
                product: [
                    {
                        supplier_id: parseInt(id.value),
                        category_id: null,
                        sub_category_id: null,
                        product_id: null,
                        quantity: null,
                        publicPrice: null,
                        clientDiscount: null,
                        kayanDiscount: null,
                    }
                ],
            },
            subCategory: [
                { subCategory: [] }
            ],
            products: [
                { products: [], send: true }
            ],
        });

        const rules = computed(() => {
            return {
                product: [
                    ...productValidation.value
                ],
            }
        });

        const v$ = useVuelidate(rules, addJob.data);

        return { t, id, getProduct, getSubCategory,altr_search, loading2, categories, clients, stores, loading, message, ...toRefs(addJob), v$, productValidation };
    },
    methods: {
        //
        saveExcelVirtualStock() {
            this.loading = true
            var $mainFormVirualStocks = $('#mainFormVirualStocks')
            var data2 = new FormData(mainFormVirualStocks)
            data2.append('supplier_id', this.id)
            adminApi.post(`/v1/dashboard/virtualStockExcel`, data2)
                .then((res) => {
                    notify({
                        title: `${this.t('global.AddedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                        type: "success",
                        duration: 5000,
                        speed: 2000
                    });
                    this.resetForm();
                    this.$nextTick(() => { this.v$.$reset() });
                })
                .catch((err) => {
                    this.errors = err.response.data.errors;
                    this.message = err.response.data.message;
                    console.log(err.response.data);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ...',
                        text: `يوجد خطأ..!!`,
                    });
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        //

        storeVirtualStock() {
            this.v$.$validate();

            if (!this.v$.$error) {
                this.loading = true;
                this.errors = {};
                this.message = '';

                adminApi.post(`/v1/dashboard/virtualStock`, this.data)
                    .then((res) => {
                        notify({
                            title: `${this.t('global.AddedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });
                        this.resetForm();
                        this.$nextTick(() => { this.v$.$reset() });
                    })
                    .catch((err) => {
                        this.errors = err.response.data.errors;
                        this.message = err.response.data.message;
                        console.log(err.response.data);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ...',
                            text: `يوجد خطأ..!!`,
                        });
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        },
        addDebit() {
            this.data.product.push({
                supplier_id: parseInt(this.id),
                category_id: null,
                sub_category_id: null,
                product_id: null,
                quantity: null,
                publicPrice: null,
                clientDiscount: null,
                kayanDiscount: null,
            });
            this.productValidation.push({
                category_id: {
                    required
                },
                sub_category_id: {
                    required
                },
                product_id: {
                    required
                },
                quantity: {
                    required,
                    numeric
                },
                publicPrice: {
                    required,
                    numeric
                },
                clientDiscount: {
                    required,
                    numeric
                },
                kayanDiscount: {
                    required,
                    numeric
                },
            });

            this.subCategory.push({ subCategory: [] });
            this.products.push({ products: [], send: true });
            this.$nextTick(() => { this.v$.$reset() });
        },
        deleteDebit(index) {
            this.data.product.splice(index, 1);
            this.productValidation.splice(index, 1);
            this.subCategory.splice(index, 1);
            this.products.splice(index, 1);
            this.$nextTick(() => { this.v$.$reset() });
        },
        resetForm() {
            this.data.product = [{
                supplier_id: id.value,
                category_id: null,
                sub_category_id: null,
                product_id: null,
                quantity: null,
                publicPrice: null,
                clientDiscount: null,
                kayanDiscount: null,
            }];
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

.account {
    background-color: #0E67D0;
    color: #000000 !important;
    border-radius: 5px;
}

.account2 {
    background-color: #00DD2F;
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
