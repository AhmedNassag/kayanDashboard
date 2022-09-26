<template>
  <div
    class="client-group-container hello-form"
    :class="['page-wrapper', locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <loader v-if="loading" />
    <div class="row">
      <div class="col-lg-8 mx-auto mt-5">
        <h6>{{ $t("global.Deals") }}</h6>
        <form
          class="border mt-3 p-5"
          @submit.prevent="save"
          enctype="multipart/form-data"
        >
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label for="exampleInputEmail1">{{ $t("global.EndAt") }}</label>
                <input
                  type="datetime-local"
                  class="form-control"
                  v-model="v$.end_at.$model"
                  :class="{
                    'is-invalid': v$.end_at.$error,
                  }"
                />
                <div class="invalid-feedback">
                  <div v-for="error in v$.end_at.$errors" :key="error">
                    {{
                      $t("global.EndAt") +
                      " " +
                      $t(error.$validator, {
                        date: $t("global.EndAt"),
                      })
                    }}
                  </div>
                </div>
              </div>
            </div>
            <!--list-->
            <div class="list" v-for="(element, index) in list" :key="index">
              <div class="element row">
                <div class="col-lg-12 d-flex justify-content-end">
                  <button
                    @click="removeFromList(index)"
                    class="increments border"
                    type="button"
                  >
                    -
                  </button>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="sel1">{{ $t("global.Product") }}</label>
                    <select
                      @change="element.product_id_dirty = true"
                      v-model="element.product_id"
                      :class="{
                        'is-invalid':
                          element.product_id_dirty &&
                          v$.list.$each.$response.$errors[index].product_id.length,
                      }"
                      class="form-control"
                      id="sel1"
                    >
                      <option
                        :value="product.id"
                        v-for="product in products"
                        :key="product.id"
                      >
                        {{ product.product_name.nameAr }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.list.$each.$response.$errors[index].product_id"
                        :key="error"
                      >
                        {{ $t("global.Product") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>{{ $t("global.Discount") }}</label>
                    <input
                      @input="element.discount_dirty = true"
                      type="text"
                      class="form-control"
                      v-model="element.discount"
                      :class="{
                        'is-invalid':
                          element.discount_dirty &&
                          v$.list.$each.$response.$errors[index].discount.length,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.list.$each.$response.$errors[index].discount"
                        :key="error"
                      >
                        {{
                          $t("global.Discount") +
                          " " +
                          $t(error.$validator, {
                            minValue: 0,
                            maxValue: 100,
                          })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 d-flex justify-content-end">
              <button @click="addToList" class="increments border" type="button">
                +
              </button>
            </div>
            <div class="col-12 submit">
              <button type="submit" class="px-0 btn btn-primary">
                {{ $t("global.Submit") }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import { required, helpers, minValue, maxValue } from "@vuelidate/validators";
import dealClient from "../../../http-clients/deal-client";
import { onMounted, reactive, toRefs } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const form = reactive({
      end_date: "",
      list: [],
    });
    const data = reactive({
      loading: false,
      products: [],
    });
    const rules = {
      end_at: {
        required,
        minCurrentDate(value) {
          let currentDate = new Date();
          currentDate.setHours(0, 0, 0, 0);
          return !value || new Date(value) >= currentDate;
        },
      },
      list: {
        $each: helpers.forEach({
          product_id: { required },
          discount: { required, minValue: minValue(0), maxValue: maxValue(100) },
        }),
      },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      getDeal();
      getProducts();
    });
    //Methods
    function addToList() {
      form.list.push(getElement());
    }

    function removeFromList(index) {
      if (form.list.length > 1) {
        form.list.splice(index, 1);
      }
    }

    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        touchlist();
        return;
      }
      data.loading = true;
      insertDeal();
    }
    //Commons
    function getProducts() {
      data.loading = true;
      dealClient.getProducts().then((response) => {
        data.loading = false;
        data.products = response.data;
      });
    }
    function getDeal() {
      data.loading = true;
      dealClient.getDeal().then((response) => {
        data.loading = false;
        form.list = response.data.length > 0 ? response.data : [getElement()];
        form.end_at = response.data.length > 0 ? response.data[0].end_at : "";
      });
    }

    function insertDeal() {
      dealClient
        .insertDeal(getForm())
        .then((response) => {
          data.loading = false;
          alertMessage("global.AddedSuccessfully");
        })
        .catch((error) => {});
    }

    function alertMessage(message) {
      notify({
        title: `${t(message)} <i class="fas fa-check-circle"></i>`,
        type: "success",
        duration: 5000,
        speed: 2000,
      });
    }

    function touchlist() {
      form.list.forEach((element) => {
        element.product_id_dirty = true;
        element.discount_dirty = true;
      });
    }

    function getElement(product_id = null, discount = null) {
      return { product_id: product_id, discount: discount };
    }

    function getForm() {
      return {
        end_at: form.end_at,
        products_with_discounts: form.list.map((element) =>
          getElement(element.product_id, element.discount)
        ),
      };
    }
    return {
      ...toRefs(form),
      ...toRefs(data),
      v$,
      locale,
      addToList,
      removeFromList,
      save,
    };
  },
};
</script>

<style scoped lang="scss">
.hello-form {
  form {
    border-radius: 5px;
  }
  .form-control {
    padding: 10px;
  }
  .form-group {
    margin-bottom: 10px;
    label {
      margin-bottom: 5px;
    }
  }
  .icons {
    i {
      font-size: 20px;
    }
    i:hover {
      cursor: pointer;
    }
  }
  .increments {
    display: inline-block;
    width: 30px;
    text-align: center;
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 0 5px;
  }
  .submit {
    button {
      width: 80px;
    }
    padding-right: 10px !important;
  }
}
</style>
