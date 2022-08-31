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
                    <label for="exampleInputEmail1">{{
                      $t("global.Name")
                    }}</label>
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
                    <label for="exampleInputEmail1">{{
                      $t("global.Address")
                    }}</label>
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
                    <label for="exampleInputEmail1">{{
                      $t("global.Phone")
                    }}</label>
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
                    <label for="exampleInputEmail1">{{
                      $t("global.TaxCard")
                    }}</label>
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
                    <input
                      type="text"
                      class="form-control"
                      v-model="responsible_name"
                    />
                  </div>
                </div>
                <div class="col-12">
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
                      <div
                        v-for="error in v$.responsible_phone.$errors"
                        :key="error"
                      >
                        {{
                          $t("global.ResponsiblePhone") +
                          " " +
                          $t(error.$validator)
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="sel1">{{ $t("global.Employees") }}</label>
                    <select
                      :class="{
                        'is-invalid': v$.employee_id.$error,
                      }"
                      v-model="v$.employee_id.$model"
                      class="custom-select"
                      id="sel1"
                    >
                      <option
                        :value="employee.id"
                        v-for="employee in employees"
                        :key="employee.id"
                      >
                        {{ employee.user.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.employee_id.$errors" :key="error">
                        {{ $t("global.Employee") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="multi-select mb-2">
                    <label>{{ $t("global.Shipping") }}</label>
                    <div
                      :class="{
                        'is-invalid':
                          shippingsTouched && getSelectedShippingsIds().length == 0,
                      }"
                      class="select border p-2"
                    >
                      <div
                        v-for="(shipping, index) in shippings"
                        :key="shipping.id"
                        class="form-check"
                      >
                        <input
                          class="form-check-input"
                          type="checkbox"
                          @change="toggleShippingSelection(shipping)"
                          :id="index"
                          :checked="shipping.selected"
                        />
                        <label class="form-check-label" for="flexCheckChecked">
                          {{ shipping.name }}
                        </label>
                      </div>
                    </div>
                    <div class="invalid-feedback">
                      {{ $t("global.Shipping") + " " + $t("required") }}
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.PaymentType")
                    }}</label>
                    <select
                      v-model="v$.payment_type.$model"
                      :class="{
                        'is-invalid': v$.payment_type.$error,
                      }"
                      class="custom-select"
                      id="sel1"
                    >
                      <option value="BANK_TRANSFER">
                        {{ $t("global.BankTransfer") }}
                      </option>
                      <option value="WALLET">{{ $t("global.Wallet") }}</option>
                      <option value="CASH">{{ $t("global.Cash") }}</option>
                    </select>
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.payment_type.$errors"
                        :key="error"
                      >
                        {{
                          $t("global.PaymentType") + " " + $t(error.$validator)
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if="payment_type == 'BANK_TRANSFER'" class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.AccountNumber")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.account_number.$model"
                      :class="{
                        'is-invalid': v$.account_number.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.account_number.$errors"
                        :key="error"
                      >
                        {{
                          $t("global.AccountNumber") +
                          " " +
                          $t(error.$validator)
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if="payment_type == 'WALLET'" class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.PaymentPhone")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.payment_phone.$model"
                      :class="{
                        'is-invalid': v$.payment_phone.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.payment_phone.$errors"
                        :key="error"
                      >
                        {{
                          $t("global.PaymentPhone") + " " + $t(error.$validator)
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if="payment_type == 'CASH'" class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.PaymentResponsibleName")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.payment_responsible_name.$model"
                      :class="{
                        'is-invalid': v$.payment_responsible_name.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.payment_responsible_name.$errors"
                        :key="error"
                      >
                        {{
                          $t("global.PaymentResponsibleName") +
                          " " +
                          $t(error.$validator)
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if="payment_type == 'CASH'" class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.PaymentResponsiblePhone")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.payment_responsible_phone.$model"
                      :class="{
                        'is-invalid': v$.payment_responsible_phone.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.payment_responsible_phone.$errors"
                        :key="error"
                      >
                        {{
                          $t("global.PaymentResponsiblePhone") +
                          " " +
                          $t(error.$validator)
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if="payment_type == 'CASH'" class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.PaymentResponsibleCardNumber")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.payment_responsible_card_number.$model"
                      :class="{
                        'is-invalid': v$.payment_responsible_card_number.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.payment_responsible_card_number
                          .$errors"
                        :key="error"
                      >
                        {{
                          $t("global.PaymentResponsibleCardNumber") +
                          " " +
                          $t(error.$validator)
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
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
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
      shippingsTouched: false,
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
      payment_type: "BANK_TRANSFER",
      account_number: "",
      payment_phone: "",
      payment_responsible_name: "",
      payment_responsible_phone: "",
      payment_responsible_card_number: "",
      employee_id: null,
    });
    const rules = {
      name: { required },
      address: { required },
      employee_id: { required },
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
      payment_type: { required },
      account_number: {
        required(value) {
          return form.payment_type == "BANK_TRANSFER" ? value : true;
        },
      },
      payment_phone: {
        required(value) {
          return form.payment_type == "WALLET" ? value : true;
        },
        phone(value) {
          return form.payment_type == "WALLET" ? phone(value) : true;
        },
      },
      payment_responsible_name: {
        required(value) {
          return form.payment_type == "CASH" ? value : true;
        },
      },
      payment_responsible_phone: {
        required(value) {
          return form.payment_type == "CASH" ? value : true;
        },
        phone(value) {
          return form.payment_type == "CASH" ? phone(value) : true;
        },
      },
      payment_responsible_card_number: {
        required(value) {
          return form.payment_type == "CASH" ? value : true;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function toggleShippingSelection(shipping) {
      shipping.selected = !shipping.selected;
      data.shippingsTouched = true;
    }
    function getSelectedShippingsIds() {
      return props.shippings
        .filter((shipping) => {
          return shipping.selected;
        })
        .map((shipping) => shipping.id);
    }
    function save() {
      if (v$.value.$invalid || getSelectedShippingsIds().length == 0) {
        v$.value.$touch();
        data.shippingsTouched = true;
        return;
      }
      if (!props.selectedSupplier) {
        store();
      } else {
        update();
      }
    }
    //Commons
    function setSelectedShippings() {
      if (props.selectedSupplier) {
        props.shippings.forEach((shipping) => {
          shipping.selected = props.selectedSupplier.shippings
            .map((_shipping) => (_shipping.id ? _shipping.id : _shipping))
            .includes(shipping.id);
        });
      } else {
        props.shippings.forEach((shipping) => (shipping.selected = false));
      }
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
          data.taxCardExist = error.response.data.errors.tax_card
            ? true
            : false;
          data.commericalRegisterExist = error.response.data.errors
            .commerical_register
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
          data.taxCardExist = error.response.data.errors.tax_card
            ? true
            : false;
          data.commericalRegisterExist = error.response.data.errors
            .commerical_register
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
        payment_type: form.payment_type,
        account_number: form.account_number,
        payment_phone: form.payment_phone,
        payment_responsible_name: form.payment_responsible_name,
        payment_responsible_phone: form.payment_responsible_phone,
        payment_responsible_card_number: form.payment_responsible_card_number,
        shippings_ids: getSelectedShippingsIds(),
      };
    }
    function setForm() {
      v$.value.$reset();
      data.shippingsTouched = false;
      form.name = props.selectedSupplier ? props.selectedSupplier.name : "";
      form.address = props.selectedSupplier
        ? props.selectedSupplier.address
        : "";
      form.phone = props.selectedSupplier ? props.selectedSupplier.phone : "";
      form.commerical_register = props.selectedSupplier
        ? props.selectedSupplier.commerical_register
        : "";
      form.tax_card = props.selectedSupplier
        ? props.selectedSupplier.tax_card
        : "";
      form.responsible_name = props.selectedSupplier
        ? props.selectedSupplier.responsible_name
        : "";
      form.responsible_phone = props.selectedSupplier
        ? props.selectedSupplier.responsible_phone
        : "";
      form.payment_type = props.selectedSupplier
        ? props.selectedSupplier.payment_type
        : "BANK_TRANSFER";
      form.account_number = props.selectedSupplier
        ? props.selectedSupplier.account_number
        : "";
      form.payment_phone = props.selectedSupplier
        ? props.selectedSupplier.payment_phone
        : "";
      form.payment_responsible_name = props.selectedSupplier
        ? props.selectedSupplier.payment_responsible_name
        : "";
      form.payment_responsible_phone = props.selectedSupplier
        ? props.selectedSupplier.payment_responsible_phone
        : "";
      form.payment_responsible_card_number = props.selectedSupplier
        ? props.selectedSupplier.payment_responsible_card_number
        : "";
      let firstEmployeeId =
        props.employees && props.employees.length > 0
          ? props.employees[0].id
          : null;
      form.employee_id = props.selectedSupplier
        ? props.selectedSupplier.employee_id
        : firstEmployeeId;
      setSelectedShippings();
      data.phoneExist = false;
      data.commericalRegisterExist = false;
      data.taxCardExist = false;
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
      toggleShippingSelection,
      getSelectedShippingsIds,
      v$,
      locale,
      save,
      employees: props.employees,
      shippings: props.shippings,
    };
  },
  props: ["selectedSupplier", "employees", "shippings"],
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