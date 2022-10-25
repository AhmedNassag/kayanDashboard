<template>
  <div class="best-seller-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="bestSellerProductFormModal"
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
import { required } from "@vuelidate/validators";
import bestSellerClient from "../../../http-clients/best-seller-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const best_seller_store = inject("best_seller_store");
    const form = reactive({
      id: null,
      product_id: null,
    });
    const rules = {
      product_id: { required },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedBestSellerProduct) {
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
      bestSellerClient
        .store(getForm())
        .then((response) => {
          context.emit("loading", false);
          response.data.product = getProduct();
          context.emit("created", response.data);
          $("#bestSellerProductFormModal #close-button").click();
          alertMessage("global.AddedSuccessfully");
        })
        .catch((error) => {
          context.emit("loading", false);
        });
    }
    function update() {
      context.emit("loading", true);
      bestSellerClient
        .update(getForm())
        .then((response) => {
          context.emit("loading", false);
          response.data.product = getProduct();
          context.emit("updated", response.data);
          $("#bestSellerProductFormModal #close-button").click();
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
        id: props.selectedBestSellerProduct ? props.selectedBestSellerProduct.id : null,
        product_id: form.product_id,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.product_id = props.selectedBestSellerProduct
        ? props.selectedBestSellerProduct.product_id
        : null;
    }
    //Watchers
    watch(
      () => {
        best_seller_store.onFormShow;
      },
      (value) => {
        console.log(props.selectedBestSellerProduct)
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
  props: ["selectedBestSellerProduct", "products"],
};
</script>

<style scoped lang="scss">
.best-seller-form {
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
