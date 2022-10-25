<template>
  <div class="unit-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="supplierFormModal"
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
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Name") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.name.$model"
                      :class="{
                        'is-invalid': v$.name.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.name.$errors" :key="error">
                        {{ $t("global.Name") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Address") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.address.$model"
                      :class="{
                        'is-invalid': v$.address.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.address.$errors" :key="error">
                        {{ $t("global.Address") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Phone") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.phone.$model"
                      :class="{
                        'is-invalid': v$.phone.$error || phoneExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.phone.$errors" :key="error">
                        {{ $t("global.Phone") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.phone.$invalid && phoneExist">
                        {{
                          $t("global.Exist", {
                            field: $t("global.Phone"),
                          })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.CommericalRegister")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="commerical_register"
                      :class="{
                        'is-invalid': commericalRegisterExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      {{
                        $t("global.Exist", {
                          field: $t("global.CommericalRegister"),
                        })
                      }}
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.TaxCard") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="tax_card"
                      :class="{
                        'is-invalid': taxCardExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      {{ $t("global.Exist", { field: $t("global.TaxCard") }) }}
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.ResponsibleName")
                    }}</label>
                    <input type="text" class="form-control" v-model="responsible_name" />
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="inputState">{{ $t("global.Type") }}</label>
                    <select
                      :class="{
                        'is-invalid': v$.type.$error,
                      }"
                      v-model="v$.type.$model"
                      class="form-control"
                    >
                      <option value="STORE">{{ $t("global.Store") }}</option>
                      <option value="COMPANY">{{ $t("global.Company") }}</option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.type.$errors" :key="error">
                        {{ $t("global.Type") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.ResponsiblePhone")
                    }}</label>
                    <input
                      :class="{
                        'is-invalid': v$.responsible_phone.$error,
                      }"
                      type="text"
                      class="form-control"
                      v-model="v$.responsible_phone.$model"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.responsible_phone.$errors" :key="error">
                        {{ $t("global.ResponsiblePhone") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-check">
                    <input
                      v-model="isOurSupplier"
                      class="form-check-input"
                      type="checkbox"
                      id="defaultCheck1"
                    />
                    <label class="form-check-label" for="defaultCheck1">
                      {{ $t("global.IsKayanSupplier") }}
                    </label>
                  </div>
                  <div class="text-danger">
                    <div v-if="isOurSupplierExist">
                      {{ $t("global.KayanSupplierChoosedBefore") }}
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
import supplierClient from "../../../http-clients/supplier-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
import phone from "../../../validators/phone-validator";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const supplier_store = inject("supplier_store");
    const data = reactive({
      phoneExist: false,
      taxCardExist: false,
      commericalRegisterExist: false,
      isOurSupplierExist: false,
    });
    const form = reactive({
      id: null,
      name: "",
      address: "",
      phone: "",
      commerical_register: "",
      tax_card: "",
      responsible_name: "",
      responsible_phone: "",
      isOurSupplier: false,
      type: "STORE",
    });
    const rules = {
      name: { required },
      address: { required },
      phone: {
        required,
        phone(value) {
          return phone(value);
        },
      },
      responsible_phone: {
        phone(value) {
          return phone(value);
        },
      },
      type: { required },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedSupplier) {
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
      data.phoneExist = false;
      data.commericalRegisterExist = false;
      data.taxCardExist = false;
      supplierClient
        .store(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("created", response.data);
          alertMessage("global.AddedSuccessfully");
          $("#close-button").click();
        })
        .catch((error) => {
          data.phoneExist = error.response.data.errors.phone ? true : false;
          data.taxCardExist = error.response.data.errors.tax_card ? true : false;
          data.commericalRegisterExist = error.response.data.errors.commerical_register
            ? true
            : false;
          data.isOurSupplierExist = error.response.data.errors.is_our_supplier
            ? true
            : false;
          context.emit("loading", false);
        });
    }
    function update() {
      context.emit("loading", true);
      data.phoneExist = false;
      data.commericalRegisterExist = false;
      data.taxCardExist = false;
      supplierClient
        .update(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("updated", response.data);
          $("#close-button").click();
          alertMessage("global.EditSuccessfully");
        })
        .catch((error) => {
          data.phoneExist = error.response.data.errors.phone ? true : false;
          data.taxCardExist = error.response.data.errors.tax_card ? true : false;
          data.commericalRegisterExist = error.response.data.errors.commerical_register
            ? true
            : false;
          data.isOurSupplierExist = error.response.data.errors.is_our_supplier
            ? true
            : false;
          context.emit("loading", false);
        });
    }
    function getForm() {
      return {
        id: props.selectedSupplier ? props.selectedSupplier.id : null,
        name: form.name,
        address: form.address,
        phone: form.phone,
        commerical_register: form.commerical_register,
        employee_id: form.employee_id,
        tax_card: form.tax_card,
        responsible_name: form.responsible_name,
        responsible_phone: form.responsible_phone,
        type: form.type,
        is_our_supplier: form.isOurSupplier,
      };
    }

    function setForm() {
      v$.value.$reset();
      form.name = props.selectedSupplier ? props.selectedSupplier.name : "";
      form.address = props.selectedSupplier ? props.selectedSupplier.address : "";
      form.phone = props.selectedSupplier ? props.selectedSupplier.phone : "";
      form.commerical_register = props.selectedSupplier
        ? props.selectedSupplier.commerical_register
        : "";
      form.tax_card = props.selectedSupplier ? props.selectedSupplier.tax_card : "";
      form.responsible_name = props.selectedSupplier
        ? props.selectedSupplier.responsible_name
        : "";
      form.responsible_phone = props.selectedSupplier
        ? props.selectedSupplier.responsible_phone
        : "";
      form.type = props.selectedSupplier ? props.selectedSupplier.type : "STORE";
      form.isOurSupplier = Boolean(
        props.selectedSupplier ? props.selectedSupplier.is_our_supplier : false
      );
      data.phoneExist = false;
      data.commericalRegisterExist = false;
      data.taxCardExist = false;
      data.isOurSupplierExist = false;
    }
    //Watchers
    watch(
      () => {
        supplier_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(data),
      ...toRefs(form),
      v$,
      locale,
      save,
    };
  },
  props: ["selectedSupplier"],
};
</script>

<style scoped lang="scss">
.unit-form {
  .select {
    border-radius: 0.25rem;
    height: 150px;
    overflow: auto;
  }
  .form-control {
    padding: 10px !important;
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
