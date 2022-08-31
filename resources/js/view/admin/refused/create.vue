<template>
  <div
    :class="[
      'page-wrapper',
      this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
    ]"
  >
    <div class="content container-fluid">
      <notifications
        :position="this.$i18n.locale == 'ar' ? 'top left' : 'top right'"
      />
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.Refused") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'indexRefused' }">
                    {{ $t("global.Refused") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">
                {{ $t("refused.CreateRefused") }}
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
                <router-link
                  :to="{ name: 'indexRefused' }"
                  class="btn btn-custom btn-dark"
                >
                  {{ $t("global.back") }}
                </router-link>
              </div>
              <div class="row">
                <div class="col-sm">
                  <div class="alert alert-danger text-center" v-if="errors['name']">
                    {{ t("global.Exist", {field:t("global.Name")}) }}<br />
                  </div>
                  <form
                    @submit.prevent="storeRefused"
                    class="needs-validation"
                    enctype="multipart/form-data"
                  >
                    <div class="form-row row">


                        <!--Start Refused Quantity-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom01">
                                {{ $t("global.Refused Quantity") }}
                            </label>
                            <input
                            type="number"
                            class="form-control"
                            v-model.trim="v$.refusedQuantity.$model"
                            id="validationCustom01"
                            :placeholder="$t('global.Refused Quantity')"
                            :class="{
                                'is-invalid': v$.refusedQuantity.$error,
                                'is-valid': !v$.refusedQuantity.$invalid,
                            }"
                            />
                            <div class="valid-feedback">
                                {{ $t("global.LooksGood") }}
                            </div>
                            <div class="invalid-feedback">
                                <span v-if="v$.refusedQuantity.required.$invalid">
                                    {{ $t("global.NameIsRequired") }}
                                    <br/>
                                </span>
                            </div>
                        </div>
                        <!--End Refused Quantity-->

                        <!--Start Refused Reason-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom02">
                                {{ $t("global.Refused Reason") }}
                            </label>
                            <input
                            type="number"
                            class="form-control"
                            v-model.trim="v$.refusedReason.$model"
                            id="validationCustom02"
                            :placeholder="$t('global.Refused Reason')"
                            :class="{
                                'is-invalid': v$.refusedReason.$error,
                                'is-valid': !v$.refusedReason.$invalid,
                            }"
                            />
                            <div class="valid-feedback">
                                {{ $t("global.LooksGood") }}
                            </div>
                            <div class="invalid-feedback">
                                <span v-if="v$.refusedReason.required.$invalid">
                                    {{ $t("global.NameIsRequired") }}
                                    <br/>
                                </span>
                            </div>
                        </div>
                        <!--End Refused Reason-->

                        <!--Start Discount Percentage-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom04">
                                {{ $t("global.Discount Percentage") }}
                            </label>
                            <input
                            type="number"
                            class="form-control"
                            v-model.trim="v$.discountPercentage.$model"
                            id="validationCustom04"
                            :placeholder="$t('global.Discount Percentage')"
                            :class="{
                                'is-invalid': v$.discountPercentage.$error,
                                'is-valid': !v$.discountPercentage.$invalid,
                            }"
                            />
                            <div class="valid-feedback">
                                {{ $t("global.LooksGood") }}
                            </div>
                            <div class="invalid-feedback">
                                <span v-if="v$.discountPercentage.required.$invalid">
                                    {{ $t("global.NameIsRequired") }}
                                    <br/>
                                </span>
                            </div>
                        </div>
                        <!--End Discount Percentage-->

                        <!--Start Discount Value-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom05">
                                {{ $t("global.Discount Value") }}
                            </label>
                            <input
                            type="number"
                            class="form-control"
                            v-model.trim="v$.discountValue.$model"
                            id="validationCustom05"
                            :placeholder="$t('global.Discount Value')"
                            :class="{
                                'is-invalid': v$.discountValue.$error,
                                'is-valid': !v$.discountValue.$invalid,
                            }"
                            />
                            <div class="valid-feedback">
                                {{ $t("global.LooksGood") }}
                            </div>
                            <div class="invalid-feedback">
                                <span v-if="v$.discountValue.required.$invalid">
                                    {{ $t("global.NameIsRequired") }}
                                    <br/>
                                </span>
                            </div>
                        </div>
                        <!--End Discount Value-->

                        <!--Start Another Discount-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom06">
                                {{ $t("global.Another Discount") }}
                            </label>
                            <input
                            type="number"
                            class="form-control"
                            v-model.trim="v$.anotherDiscount.$model"
                            id="validationCustom06"
                            :placeholder="$t('global.Another Discount')"
                            :class="{
                                'is-invalid': v$.anotherDiscount.$error,
                                'is-valid': !v$.anotherDiscount.$invalid,
                            }"
                            />
                            <div class="valid-feedback">
                                {{ $t("global.LooksGood") }}
                            </div>
                            <div class="invalid-feedback">
                                <span v-if="v$.anotherDiscount.required.$invalid">
                                    {{ $t("global.NameIsRequired") }}
                                    <br/>
                                </span>
                            </div>
                        </div>
                        <!--End Another Discount-->

                        <!--Start Total-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom07">
                                {{ $t("global.Total") }}
                            </label>
                            <input
                            type="number"
                            class="form-control"
                            v-model.trim="v$.total.$model"
                            id="validationCustom07"
                            :placeholder="$t('global.Total')"
                            :class="{
                                'is-invalid': v$.total.$error,
                                'is-valid': !v$.total.$invalid,
                            }"
                            />
                            <div class="valid-feedback">
                                {{ $t("global.LooksGood") }}
                            </div>
                            <div class="invalid-feedback">
                                <span v-if="v$.total.required.$invalid">
                                    {{ $t("global.NameIsRequired") }}
                                    <br/>
                                </span>
                            </div>
                        </div>
                        <!--End Total-->

                        <!--Start Category Select-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom08">
                                {{ $t("global.MainCategory") }}
                            </label>
                            <select class="form-control" v-model.trim="v$.category_id.$model">
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>
                        <!--End Category Select-->

                        <!--Start Supplier Select-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom09">
                                {{ $t("global.Supplier") }}
                            </label>
                            <select class="form-control" v-model.trim="v$.supplier_id.$model">
                                <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                                    {{ supplier.name }}
                                </option>
                            </select>
                        </div>
                        <!--End Supplier Select-->

                        <!--Start Product Select-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom10">
                                {{ $t("global.Product") }}
                            </label>
                            <select class="form-control" v-model.trim="v$.product_id.$model">
                                <option v-for="product in products" :key="product.id" :value="product.id">
                                    {{ product.product_name.nameAr }}
                                </option>
                            </select>
                        </div>
                        <!--End Product Select-->

                        <!--Start Stock Select-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom10">
                                {{ $t("global.Stock") }}
                            </label>
                            <select class="form-control" v-model.trim="v$.stock_id.$model">
                                <option v-for="stock in stocks" :key="stock.id" :value="stock.id">
                                    {{ stock.name }}
                                </option>
                            </select>
                        </div>
                        <!--End Stock Select-->

                        <!--Start Note-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom01">
                                {{ $t("global.Notes") }}
                            </label>
                            <textarea
                            type="text"
                            class="form-control"
                            cols="7"
                            rows="7"
                            v-model.trim="v$.note.$model"
                            id="validationCustom02"
                            :placeholder="$t('global.Notes')"
                            :class="{
                                'is-invalid': v$.note.$error,
                                'is-valid': !v$.note.$invalid,
                            }"
                            ></textarea>
                            <div class="valid-feedback">
                                {{ $t("global.LooksGood") }}
                            </div>
                            <div class="invalid-feedback">
                            <span v-if="v$.note.required.$invalid">
                                {{ $t("global.NameIsRequired") }}
                                <br/>
                            </span>
                            <span v-if="v$.note.maxLength.$invalid">
                                {{ $t("global.NameIsMustHaveAtLeast") }}
                                {{ v$.note.minLength.$params.min }}
                                {{ $t("global.Letters") }}
                                <br/>
                            </span>
                            <span v-if="v$.note.minLength.$invalid">
                                {{ $t("global.NameIsMustHaveAtMost") }}
                                {{ v$.note.maxLength.$params.max }}
                                {{ $t("global.Letters") }}
                                <br/>
                            </span>
                            </div>
                        </div>
                        <!--End Note-->

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
//
import { computed, onMounted, reactive, toRefs, inject, ref } from "vue";
//
import useVuelidate from "@vuelidate/core";
import {
  required,
  minLength,
  between,
  maxLength,
  alpha,
  integer,
} from "@vuelidate/validators";
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
//
import { useI18n } from "vue-i18n";
//

export default {
  name: "createRefused",
  data() {
    return {
      errors: {},
    };
  },
  setup() {
    //
    const emitter = inject("emitter");
    const { t } = useI18n({});
    //
    let loading = ref(false);
    let categories = ref([]);
    let suppliers = ref([]);
    let products = ref([]);
    let stocks = ref([]);


    //start design
    let addRefused = reactive({
      data: {
        refusedQuantity: "",
        refusedReason: "",
        note: "",
        discountPercentage: "",
        discountValue:"",
        anotherDiscount:"",
        total:"",
        category_id:"",
        supplier_id:"",
        product_id:"",
        stock_id:"",
        nameExist: false,
      },
    });

    //
    getCategories();
    getSuppliers();
    getProducts();
    getStocks();
    //


    const rules = computed(() => {
      return {
        refusedQuantity: {
          required,
        },
        refusedReason: {
          required,
        },
        note: {
          minLength: minLength(3),
          maxLength: maxLength(255),
          required,
        },
        discountPercentage: {
          required,
        },
        discountValue: {
          required,
        },
        anotherDiscount:{
            required,
        },
        total: {
          required,
        },
        category_id:{
            required,
        },
        supplier_id:{
            required,
        },
        product_id:{
            required,
        },
        stock_id:{
            required,
        },
      };
    });

    const v$ = useVuelidate(rules, addRefused.data);


    //Commons
    function getCategories(){
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

    function getSuppliers(){
        adminApi
        .get(`/v1/dashboard/getSuppliers`)
        .then((res) => {
            suppliers.value =res.data.data.suppliers ;
        })
        .catch((err) => {
            console.log(err.response.data);
        })
        .finally(() => {
            loading.value = false;
        });
    }

    function getProducts(){
        adminApi
        .get(`/v1/dashboard/getProducts`)
        .then((res) => {
            products.value =res.data.data.products ;
        })
        .catch((err) => {
            console.log(err.response.data);
        })
        .finally(() => {
            loading.value = false;
        });
    }

    function getStocks(){
        adminApi
        .get(`/v1/dashboard/getStocks`)
        .then((res) => {
            stocks.value =res.data.data.stocks ;
        })
        .catch((err) => {
            console.log(err.response.data);
        })
        .finally(() => {
            loading.value = false;
        });
    }
    //end common


    return { loading, ...toRefs(addRefused), v$, categories, suppliers, products, stocks };

  },
  methods: {
    storeRefused() {
      this.v$.$validate();

      if (!this.v$.$error) {
        this.loading = true;
        this.errors = {};
        let formData = new FormData();
        formData.append("refusedQuantity", this.data.refusedQuantity);
        formData.append("refusedReason", this.data.refusedReason);
        formData.append("note", this.data.note);
        formData.append("discountPercentage", this.data.discountPercentage);
        formData.append("discountValue", this.data.discountValue);
        formData.append("anotherDiscount", this.data.anotherDiscount);
        formData.append("total", this.data.total);
        formData.append("category_id", this.data.category_id);
        formData.append("supplier_id", this.data.supplier_id);
        formData.append("product_id", this.data.product_id);
        formData.append("stock_id", this.data.stock_id);

        adminApi
          .post(`/v1/dashboard/refused`, formData)
          .then((res) => {
            notify({
              title: `تم الإضافة بنجاح <i class="fas fa-check-circle"></i>`,
              type: "success",
              duration: 5000,
              speed: 2000,
            });

            this.resetForm();
            this.$nextTick(() => {
              this.v$.$reset();
            });
          })
          .catch((err) => {
            this.nameExist = err.response.data.errors;
          })
          .finally(() => {
            this.loading = false;
          });
      }
    },
    resetForm() {
      this.data.refusedQuantity = "";
      this.data.refusedReason = "";
      this.data.note = "";
      this.data.discountPercentage = "";
      this.data.discountValue = "";
      this.data.anotherDiscount = "";
      this.data.total = "";
      this.data.category_id = "";
      this.data.supplier_id = "";
      this.data.product_id = "";
      this.data.stock_id = "";
    },
  },
};
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
</style>
