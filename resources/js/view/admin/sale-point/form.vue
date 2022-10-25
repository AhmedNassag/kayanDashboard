<template>
  <div class="point-sale-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="salePointFormModal"
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
                    <label for="exampleInputEmail1">{{ $t("global.Name") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.name.$model"
                      :class="{
                        'is-invalid': v$.name.$error || nameExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.name.$errors" :key="error">
                        {{ $t("global.Name") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.name.$invalid && nameExist">
                        {{
                          $t("global.Exist", {
                            field: $t("global.Name"),
                          })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Counter") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.counter.$model"
                      :class="{
                        'is-invalid': v$.counter.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.counter.$errors" :key="error">
                        {{
                          $t("global.Counter") +
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
                    <label for="exampleInputEmail1">{{ $t("global.Price") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.price.$model"
                      :class="{
                        'is-invalid': v$.price.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.price.$errors" :key="error">
                        {{
                          $t("global.Price") +
                          " " +
                          $t(error.$validator, {
                            minValue: 0,
                          })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="sel1">{{ $t("global.RelatedWith") }}</label>
                    <select
                      @change="onRelatedWithChanged"
                      v-model="related_with"
                      class="form-control"
                      id="sel1"
                    >
                      <option
                        :value="relatedWithType.value"
                        v-for="(relatedWithType, index) in relatedWithTypes"
                        :key="index"
                      >
                        {{ $t(relatedWithType.label) }}
                      </option>
                    </select>
                  </div>
                </div>
                <div
                  v-if="
                    related_with == RELATION_TYPES.PRODUCT ||
                    related_with == RELATION_TYPES.CATEGORY
                  "
                  class="col-12"
                >
                  <div class="form-group">
                    <label for="sel1">{{ $t("global.MainCategories") }}</label>
                    <select
                      @change="onMainCategoryChange()"
                      v-model="main_category_id"
                      class="form-control"
                      id="sel1"
                    >
                      <option
                        :value="category.id"
                        v-for="category in mainCategories"
                        :key="category.id"
                      >
                        {{ category.name }}
                      </option>
                    </select>
                  </div>
                </div>
                <div
                  v-if="
                    related_with == RELATION_TYPES.PRODUCT ||
                    related_with == RELATION_TYPES.CATEGORY
                  "
                  class="col-12"
                >
                  <div class="form-group">
                    <label for="sel1">{{ $t("global.SubCategories") }}</label>
                    <select
                      @change="onSubCategoryChange"
                      :class="{
                        'is-invalid': v$.sub_category_id.$error,
                      }"
                      v-model="v$.sub_category_id.$model"
                      class="form-control"
                      id="sel1"
                    >
                      <option
                        :value="category.id"
                        v-for="category in subCategories"
                        :key="category.id"
                      >
                        {{ category.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.sub_category_id.$errors" :key="error">
                        {{ $t("global.SubCategory") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if="related_with == RELATION_TYPES.PRODUCT" class="col-12">
                  <div class="form-group">
                    <label for="sel1">{{ $t("global.Product") }}</label>
                    <select
                      :class="{
                        'is-invalid': v$.product_id.$error,
                      }"
                      v-model="v$.product_id.$model"
                      class="form-control"
                      id="sel1"
                    >
                      <option
                        :value="product.id"
                        v-for="product in products"
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
                <div v-if="related_with == RELATION_TYPES.CLIENT_GROUP" class="col-12">
                  <div class="multi-select mb-2">
                    <label>{{ $t("global.ClientGroups") }}</label>
                    <div
                      :class="{
                        'is-invalid':
                          clientGroupsTouched &&
                          getSelectedClientGroupsIds().length == 0 &&
                          related_with == RELATION_TYPES.CLIENT_GROUP,
                      }"
                      class="select border p-2"
                    >
                      <div
                        v-for="(clientGroup, index) in clientGroups"
                        :key="clientGroup.id"
                        class="form-check"
                      >
                        <input
                          class="form-check-input"
                          type="checkbox"
                          @change="toggleClientGroupSelection(clientGroup)"
                          :id="index"
                          :checked="clientGroup.selected"
                        />
                        <label class="form-check-label" for="flexCheckChecked">
                          {{ clientGroup.name }}
                        </label>
                      </div>
                    </div>
                    <div class="invalid-feedback">
                      {{ $t("global.ClientGroups") + " " + $t("required") }}
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
const RELATION_TYPES = {
  PRODUCT: "PRODUCT",
  CATEGORY: "CATEGORY",
  CLIENT_GROUP: "CLIENT_GROUP",
};
import useVuelidate from "@vuelidate/core";
import { minValue, required } from "@vuelidate/validators";
import salePointClient from "../../../http-clients/sale-point-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const sale_point_store = inject("sale_point_store");
    const data = reactive({
      nameExist: false,
      clientGroupsTouched: false,
      products: [],
      subCategories: [],
      relatedWithTypes: [
        { label: "global.Product", value: RELATION_TYPES.PRODUCT },
        { label: "global.Category", value: RELATION_TYPES.CATEGORY },
        { label: "global.ClientGroups", value: RELATION_TYPES.CLIENT_GROUP },
      ],
    });
    const form = reactive({
      id: null,
      name: "",
      counter: 0,
      price: 0,
      main_category_id: null,
      sub_category_id: null,
      product_id: null,
      related_with: RELATION_TYPES.PRODUCT,
    });
    const rules = {
      name: { required },
      counter: { required, minValue: minValue(0) },
      price: { required, minValue: minValue(0) },
      sub_category_id: {
        required(value) {
          return form.related_with == RELATION_TYPES.CATEGORY ? value : true;
        },
      },
      product_id: {
        required(value) {
          return form.related_with == RELATION_TYPES.PRODUCT ? value : true;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function onMainCategoryChange(reset = true) {
      if (!reset) {
        form.main_category_id = getMainCategoryId();
      }
      let selectedMainCategory = getSelectedMainCategory();
      if (selectedMainCategory) {
        data.subCategories = selectedMainCategory.sub_categories;
        form.sub_category_id = getSubCategoryId(reset);
        let selectedSubCategory = getSelectedSubCategory();
        if (selectedSubCategory) {
          data.products = selectedSubCategory.products;
          form.product_id = getProductId(reset);
        }
      }
    }
    function onSubCategoryChange() {
      let selectedSubCategory = getSelectedSubCategory();
      data.products = selectedSubCategory.products;
      form.product_id = data.products.length > 0 ? data.products[0].id : null;
    }
    function toggleClientGroupSelection(clientGroup) {
      clientGroup.selected = !clientGroup.selected;
      data.clientGroupsTouched = true;
    }
    function getSelectedClientGroupsIds() {
      return props.clientGroups
        .filter((clientGroup) => {
          return clientGroup.selected;
        })
        .map((clientGroup) => clientGroup.id);
    }
    function save() {
      if (
        v$.value.$invalid ||
        (getSelectedClientGroupsIds().length == 0 &&
          form.related_with == RELATION_TYPES.CLIENT_GROUP)
      ) {
        v$.value.$touch();
        data.clientGroupsTouched = true;
        return;
      }
      if (!props.selectedSalePoint) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function getMainCategoryId() {
      let firstMainCategoryId =
        props.mainCategories.length > 0 ? props.mainCategories[0].id : null;
      return props.selectedSalePoint && props.selectedSalePoint.category_id
        ? props.selectedSalePoint.category_id
        : firstMainCategoryId;
    }
    function getSubCategoryId(reset) {
      let firstSubCategroyId =
        data.subCategories.length > 0 ? data.subCategories[0].id : null;
      return props.selectedSalePoint && props.selectedSalePoint.sub_category_id && !reset
        ? props.selectedSalePoint.sub_category_id
        : firstSubCategroyId;
    }
    function getProductId(reset) {
      let firstProductId = data.products.length > 0 ? data.products[0].id : null;
      return props.selectedSalePoint && props.selectedSalePoint.product_id && !reset
        ? props.selectedSalePoint.product_id
        : firstProductId;
    }
    function getSelectedMainCategory() {
      let selectedMainCategory = null;
      props.mainCategories.forEach((element) => {
        if (element.id == form.main_category_id) {
          selectedMainCategory = element;
          return;
        }
      });
      return selectedMainCategory;
    }
    function getSelectedSubCategory() {
      let selectedSubCategory = null;
      data.subCategories.forEach((element) => {
        if (element.id == form.sub_category_id) {
          selectedSubCategory = element;
          return;
        }
      });
      return selectedSubCategory;
    }
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
      data.nameExist = false;
      data.commericalRegisterExist = false;
      data.taxCardExist = false;
      salePointClient
        .store(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("created", response.data);
          alertMessage("global.AddedSuccessfully");
          $("#close-button").click();
        })
        .catch((error) => {
          console.log(error.response);
          data.nameExist = error.response.data.errors.name ? true : false;
          context.emit("loading", false);
        });
    }
    function update() {
      context.emit("loading", true);
      data.nameExist = false;
      data.commericalRegisterExist = false;
      data.taxCardExist = false;
      salePointClient
        .update(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("updated", response.data);
          $("#close-button").click();
          alertMessage("global.EditSuccessfully");
        })
        .catch((error) => {
          console.log(error.response);
          data.nameExist = error.response.data.errors.name ? true : false;
          context.emit("loading", false);
        });
    }
    function getForm() {
      return {
        id: props.selectedSalePoint ? props.selectedSalePoint.id : null,
        name: form.name,
        counter: form.counter,
        price: form.price,
        product_id: form.product_id,
        category_id: form.main_category_id,
        sub_category_id: form.sub_category_id,
        clients_groups_ids: getSelectedClientGroupsIds(),
        related_with: form.related_with,
      };
    }
    function setForm() {
      v$.value.$reset();
      data.clientGroupsTouched = false;
      form.name = props.selectedSalePoint ? props.selectedSalePoint.name : "";
      form.counter = props.selectedSalePoint ? props.selectedSalePoint.counter : "";
      form.price = props.selectedSalePoint ? props.selectedSalePoint.price : "";
      form.related_with = props.selectedSalePoint
        ? props.selectedSalePoint.related_with
        : RELATION_TYPES.PRODUCT;
      onMainCategoryChange(false);
      onRelatedWithChanged();
      data.nameExist = false;
    }
    function onRelatedWithChanged() {
      setSelectedClientGroups();
    }
    function setSelectedClientGroups() {
      if (props.selectedSalePoint && form.related_with == RELATION_TYPES.CLIENT_GROUP) {
        props.clientGroups.forEach((clientGroup) => {
          ء;
          clientGroup.selected = props.selectedSalePoint.clients_groups
            .map((_clientGroup) => (_clientGroup.id ? _clientGroup.id : _clientGroup))
            .includes(clientGroup.id);
        });
      } else {
        props.clientGroups.forEach((clientGroup) => (clientGroup.selected = false));
      }
    }
    //Watchers
    watch(
      () => {
        sale_point_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(data),
      ...toRefs(form),
      clientGroups: props.clientGroups,
      toggleClientGroupSelection,
      getSelectedClientGroupsIds,
      onMainCategoryChange,
      onSubCategoryChange,
      onRelatedWithChanged,
      v$,
      locale,
      save,
      mainCategories: props.mainCategories,
      RELATION_TYPES,
    };
  },
  props: ["selectedSalePoint", "mainCategories", "clientGroups"],
};
</script>

<style scoped lang="scss">
.point-sale-form {
  .select {
    border-radius: 0.25rem;
    height: 150px;
    overflow: auto;
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
  .modal-footer {
    button {
      width: 80px;
    }
  }
}
</style>
