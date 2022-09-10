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
            <h3 class="page-title">{{ $t("global.Purchase") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'indexPurchase' }">
                    {{ $t("global.Purchase") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">
                {{ $t("purchase.CreatePurchase") }}
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
                  :to="{ name: 'indexPurchase' }"
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
                    @submit.prevent="storePurchase"
                    class="needs-validation"
                    enctype="multipart/form-data"
                  >
                    <div class="form-row row">


                        <!--Start Entered Quantity-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom01">
                                {{ $t("global.Entered Quanitity") }}
                            </label>
                            <input
                            type="number"
                            class="form-control"
                            v-model.trim="v$.enteredQuanitity.$model"
                            id="validationCustom01"
                            :placeholder="$t('global.Entered Quanitity')"
                            :class="{
                                'is-invalid': v$.enteredQuanitity.$error,
                                'is-valid': !v$.enteredQuanitity.$invalid,
                            }"
                            />
                            <div class="valid-feedback">
                                {{ $t("global.LooksGood") }}
                            </div>
                            <div class="invalid-feedback">
                                <span v-if="v$.enteredQuanitity.required.$invalid">
                                    {{ $t("global.NameIsRequired") }}
                                    <br/>
                                </span>
                            </div>
                        </div>
                        <!--End Entered Quantity-->

                        <!--Start Refused Quantity-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom02">
                                {{ $t("global.Refused Quantity") }}
                            </label>
                            <input
                            type="number"
                            class="form-control"
                            v-model.trim="v$.refusedQuantity.$model"
                            id="validationCustom02"
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

                        <!--Start Product Case-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom03">
                                {{ $t("global.Product Case") }}
                            </label>
                            <select class="form-control" v-model.trim="v$.productCase.$model">
                                <option value="ممتاز">
                                    {{ $t("global.Perfect") }}
                                </option>
                                <option value="جيد جداً">
                                    {{ $t("global.Very Good") }}
                                </option>
                                <option value="جيد">
                                    {{ $t("global.Good") }}
                                </option>
                            </select>
                        </div>
                        <!--End Product Case-->

                        <!--Start Production Date-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom04">
                                {{ $t("global.Production Date") }}
                            </label>
                            <input
                            type="date"
                            class="form-control"
                            v-model.trim="v$.productionDate.$model"
                            id="validationCustom04"
                            :placeholder="$t('global.Production Date')"
                            :class="{
                                'is-invalid': v$.productionDate.$error,
                                'is-valid': !v$.productionDate.$invalid,
                            }"
                            />
                            <div class="valid-feedback">
                                {{ $t("global.LooksGood") }}
                            </div>
                            <div class="invalid-feedback">
                                <span v-if="v$.productionDate.required.$invalid">
                                    {{ $t("global.NameIsRequired") }}
                                    <br/>
                                </span>
                            </div>
                        </div>
                        <!--End Production Date-->

                        <!--Start Expiration Date-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom05">
                                {{ $t("global.Expiration Date") }}
                            </label>
                            <input
                            type="date"
                            class="form-control"
                            v-model.trim="v$.expirationDate.$model"
                            id="validationCustom05"
                            :placeholder="$t('global.Expiration Date')"
                            :class="{
                                'is-invalid': v$.expirationDate.$error,
                                'is-valid': !v$.expirationDate.$invalid,
                            }"
                            />
                            <div class="valid-feedback">
                                {{ $t("global.LooksGood") }}
                            </div>
                            <div class="invalid-feedback">
                                <span v-if="v$.expirationDate.required.$invalid">
                                    {{ $t("global.NameIsRequired") }}
                                    <br/>
                                </span>
                            </div>
                        </div>
                        <!--End Expiration Date-->

                        <!--Start Price-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom06">
                                {{ $t("global.Price") }}
                            </label>
                            <input
                            type="number"
                            class="form-control"
                            v-model.trim="v$.price.$model"
                            id="validationCustom06"
                            :placeholder="$t('global.Price')"
                            :class="{
                                'is-invalid': v$.price.$error,
                                'is-valid': !v$.price.$invalid,
                            }"
                            />
                            <div class="valid-feedback">
                                {{ $t("global.LooksGood") }}
                            </div>
                            <div class="invalid-feedback">
                                <span v-if="v$.price.required.$invalid">
                                    {{ $t("global.NameIsRequired") }}
                                    <br/>
                                </span>
                            </div>
                        </div>
                        <!--End Price-->

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
                                    {{ product.productName_id }}
                                </option>
                            </select>
                        </div>
                        <!--End Product Select-->

                        <!--Start Employee Select-->
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom10">
                                {{ $t("global.Employee") }}
                            </label>
                            <select class="form-control" v-model.trim="v$.employee_id.$model">
                                <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                                    {{ employee.user.name }}
                                </option>
                            </select>
                        </div>
                        <!--End Employee Select-->

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
import { useI18n } from "vue-i18n";

export default {
  name: "createPurchase",
  data() {
    return {
      errors: {},
    };
  },
  setup() {
    const emitter = inject("emitter");
    const { t } = useI18n({});
    let loading = ref(false);
    let categories = ref([]);
    let suppliers = ref([]);
    let products = ref([]);
    let employees = ref([]);


    //start design
    let addPurchase = reactive({
      data: {
        enteredQuanitity: "",
        refusedQuantity: "",
        productCase: "",
        productionDate: "",
        expirationDate:"",
        price:"",
        total:"",
        category_id:"",
        supplier_id:"",
        product_id:"",
        employee_id:"",
        nameExist: false,
      },
    });

    getCategories();
    getSuppliers();
    getProducts();
    getEmployees();


    const rules = computed(() => {
      return {
        enteredQuanitity: {
          required,
        },
        refusedQuantity: {
          required,
        },
        productCase: {
          required,
        },
        productionDate: {
          required,
        },
        expirationDate: {
          required,
        },
        price:{
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
        employee_id:{
            required,
        },
      };
    });

    const v$ = useVuelidate(rules, addPurchase.data);


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

    function getEmployees(){
        adminApi
        .get(`/v1/dashboard/getEmployees`)
        .then((res) => {
            employees.value =res.data.data.employees ;
        })
        .catch((err) => {
            console.log(err.response.data);
        })
        .finally(() => {
            loading.value = false;
        });
    }
    //end common


    return { loading, ...toRefs(addPurchase), v$, categories, suppliers, products, employees };

  },
  methods: {
    storePurchase() {
      this.v$.$validate();

      if (!this.v$.$error) {
        this.loading = true;
        this.errors = {};
        let formData = new FormData();
        formData.append("enteredQuanitity", this.data.enteredQuanitity);
        formData.append("refusedQuantity", this.data.refusedQuantity);
        formData.append("productCase", this.data.productCase);
        formData.append("productionDate", this.data.productionDate);
        formData.append("expirationDate", this.data.expirationDate);
        formData.append("price", this.data.price);
        formData.append("total", this.data.total);
        formData.append("category_id", this.data.category_id);
        formData.append("supplier_id", this.data.supplier_id);
        formData.append("product_id", this.data.product_id);
        formData.append("employee_id", this.data.employee_id);

        adminApi
          .post(`/v1/dashboard/purchase`, formData)
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
      this.data.enteredQuanitity = "";
      this.data.refusedQuantity = "";
      this.data.productCase = "";
      this.data.productionDate = "";
      this.data.expirationDate = "";
      this.data.price = "";
      this.data.total = "";
      this.data.category_id = "";
      this.data.supplier_id = "";
      this.data.product_id = "";
      this.data.employee_id = "";
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
