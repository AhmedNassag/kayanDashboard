<template>
  <div class="deal-price-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="dealPriceFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="save">
            <div class="modal-header">
              <button
                type="button"
                class="btn-close"
                aria-label="Close"
                id="close-button"
                data-dismiss="modal"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Product") }}</label>
                    <select
                      class="form-control"
                      :class="{
                        'is-invalid': v$.product_id.$error,
                      }"
                      v-model="v$.product_id.$model"
                    >
                      <option
                        v-for="product in products"
                        :value="product.id"
                        :key="product.id"
                      >
                        {{ product.nameAr }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.product_id.$errors" :key="error">
                        {{ $t("global.Product") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.Public Price")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.publicPrice.$model"
                      :class="{
                        'is-invalid': v$.publicPrice.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.publicPrice.$errors" :key="error">
                        {{
                          $t("global.Public Price") +
                          " " +
                          $t(error.$validator, {
                            minValue: 0,
                          })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Discount") }} %</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.clientDiscount.$model"
                      :class="{
                        'is-invalid': v$.clientDiscount.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.clientDiscount.$errors" :key="error">
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
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">
                {{ $t("global.Submit") }}
              </button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                {{ $t("global.Close") }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import { maxValue, minValue, required } from "@vuelidate/validators";
import dealClient from "../../../http-clients/deal-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const deal_store = inject("deal_store");
    const form = reactive({
      id: null,
      product_id: null,
      publicPrice: null,
      clientDiscount: null,
    });
    const rules = {
      product_id: { required },
      publicPrice: { required, minValue: minValue(0) },
      clientDiscount: { required, minValue: minValue(0), maxValue: maxValue(100) },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedDealPrice) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function alertMessage(message) {
      notify({
        title: `${t(message)} <i class="fas fa-check-circle"></i>`,
        type: "success",
        duration: 5000,
        speed: 2000,
      });
    }
    function store() {
      context.emit("loading", true);
      dealClient
        .store(getForm())
        .then((response) => {
          context.emit("loading", false);
          response.data.product = getProduct();
          context.emit("created", response.data);
          $("#dealPriceFormModal #close-button").click();
          alertMessage("global.AddedSuccessfully");
        })
        .catch((error) => {
          context.emit("loading", false);
        });
    }
    function update() {
      context.emit("loading", true);
      dealClient
        .update(getForm())
        .then((response) => {
          context.emit("loading", false);
          response.data.product = getProduct();
          context.emit("updated", response.data);
          $("#dealPriceFormModal #close-button").click();
          alertMessage("global.EditSuccessfully");
        })
        .catch((error) => {
          context.emit("loading", false);
        });
    }
    function getProduct() {
      let product = null;
      props.products.forEach((_product) => {
        if (_product.id == form.product_id) {
          return (product = _product);
        }
      });
      return product;
    }
    function getForm() {
      return {
        id: props.selectedDealPrice ? props.selectedDealPrice.id : null,
        product_id: form.product_id,
        publicPrice: form.publicPrice,
        clientDiscount: form.clientDiscount,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.product_id = props.selectedDealPrice
        ? props.selectedDealPrice.product_id
        : null;
      form.publicPrice = props.selectedDealPrice
        ? props.selectedDealPrice.publicPrice
        : null;
      form.clientDiscount = props.selectedDealPrice
        ? props.selectedDealPrice.clientDiscount
        : null;
    }
    //Watchers
    watch(
      () => {
        deal_store.onFormShow;
      },
      (value) => {
        console.log(props.selectedDealPrice);
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(form),
      v$,
      locale,
      save,
      products: props.products,
    };
  },
  props: ["selectedDealPrice", "products"],
};
</script>

<style scoped lang="scss">
.dealPriceForm {
  .form-control {
    padding: 10px;
  }
  .form-group {
    margin-bottom: 10px;
    label {
      margin-bottom: 5px;
    }
  }
  .modal-footer {
    button {
      width: 80px;
    }
  }
}
</style>
