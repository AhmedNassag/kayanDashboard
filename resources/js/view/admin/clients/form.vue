<template>
  <div class="unit-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="clientFormModal"
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
                    <label for="exampleInputEmail1">{{ $t("global.Email") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.email.$model"
                      :class="{
                        'is-invalid': v$.email.$error || emailExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.email.$errors" :key="error">
                        {{ $t("global.Email") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.email.$invalid && emailExist">
                        {{ $t("global.Exist", { field: $t("global.Email") }) }}
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
                        {{ $t("global.Exist", { field: $t("global.Phone") }) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.WhatsupPhone")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.whatsup_phone.$model"
                      :class="{
                        'is-invalid': v$.whatsup_phone.$error || whatsupPhoneExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.whatsup_phone.$errors" :key="error">
                        {{ $t("global.WhatsupPhone") + " " + $t(error.$validator) }}
                      </div>
                      <div v-if="!v$.whatsup_phone.$invalid && whatsupPhoneExist">
                        {{
                          $t("global.Exist", {
                            field: $t("global.WhatsupPhone"),
                          })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.StoreName") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.store_name.$model"
                      :class="{
                        'is-invalid': v$.store_name.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.store_name.$errors" :key="error">
                        {{ $t("global.StoreName") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>{{ $t("global.KnowusWay") }}</label>
                    <div class="select-wrapper">
                      <select
                        class="form-control ps-form__input"
                        v-model="know_us_way_id"
                      >
                        <option
                          v-for="knowusWay in knowusWays"
                          :key="knowusWay.id"
                          :value="knowusWay.id"
                        >
                          {{ knowusWay.name }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>{{ $t("global.SellingMethod") }}</label>
                    <div class="select-wrapper">
                      <select
                        class="form-control ps-form__input"
                        v-model="selling_method_id"
                      >
                        <option
                          v-for="sellingMethod in sellingMethods"
                          :key="sellingMethod.id"
                          :value="sellingMethod.id"
                        >
                          {{ sellingMethod.name }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>{{ $t("global.City") }}</label>
                    <div class="select-wrapper">
                      <select
                        @change="onCitySelected"
                        class="form-control ps-form__input"
                        v-model="v$.city_id.$model"
                      >
                        <option v-for="city in cities" :key="city.id" :value="city.id">
                          {{ city.name }}
                        </option>
                      </select>
                    </div>
                    <div class="text-danger">
                      <div v-for="error in v$.city_id.$errors" :key="error">
                        {{ $t("global.City") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>{{ $t("global.Area") }}</label>
                    <div class="select-wrapper">
                      <select
                        class="form-control ps-form__input"
                        v-model="v$.area_id.$model"
                      >
                        <option v-for="area in areas" :key="area.id" :value="area.id">
                          {{ area.name }}
                        </option>
                      </select>
                    </div>
                    <div class="text-danger">
                      <div v-for="error in v$.area_id.$errors" :key="error">
                        {{ $t("global.Area") + " " + $t(error.$validator) }}
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
                    <label for="exampleInputEmail1">{{ $t("global.Location") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.location.$model"
                      :class="{
                        'is-invalid': v$.location.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.location.$errors" :key="error">
                        {{ $t("global.Location") + " " + $t(error.$validator) }}
                      </div>
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
                      v-model="v$.responsible_name.$model"
                      :class="{
                        'is-invalid': v$.responsible_name.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.responsible_name.$errors" :key="error">
                        {{ $t("global.ResponsibleName") + " " + $t(error.$validator) }}
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
                      type="text"
                      class="form-control"
                      v-model="v$.responsible_phone.$model"
                      :class="{
                        'is-invalid': v$.responsible_phone.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.responsible_phone.$errors" :key="error">
                        {{ $t("global.ResponsiblePhone") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 mb-1">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input
                        type="checkbox"
                        class="form-check-input"
                        v-model="same_address_shipping"
                      />{{ $t("global.SameAddressShipping") }}
                    </label>
                  </div>
                </div>
                <template v-if="!same_address_shipping">
                  <div class="col-6">
                    <div class="form-group">
                      <label>{{ $t("global.City") }}</label>
                      <div class="select-wrapper">
                        <select
                          @change="onShippingCitySelected"
                          class="form-control ps-form__input"
                          v-model="v$.shipping_city_id.$model"
                        >
                          <option v-for="city in cities" :key="city.id" :value="city.id">
                            {{ city.name }}
                          </option>
                        </select>
                      </div>
                      <div class="text-danger">
                        <div v-for="error in v$.shipping_city_id.$errors" :key="error">
                          {{ $t("global.City") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label>{{ $t("global.Area") }}</label>
                      <div class="select-wrapper">
                        <select
                          class="form-control ps-form__input"
                          v-model="v$.shipping_area_id.$model"
                        >
                          <option
                            v-for="area in shipping_areas"
                            :key="area.id"
                            :value="area.id"
                          >
                            {{ area.name }}
                          </option>
                        </select>
                      </div>
                      <div class="text-danger">
                        <div v-for="error in v$.shipping_area_id.$errors" :key="error">
                          {{ $t("global.Area") + " " + $t(error.$validator) }}
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
                        v-model="v$.shipping_address.$model"
                        :class="{
                          'is-invalid': v$.shipping_address.$error,
                        }"
                      />
                      <div class="invalid-feedback">
                        <div v-for="error in v$.shipping_address.$errors" :key="error">
                          {{ $t("global.Address") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">{{ $t("global.Location") }}</label>
                      <input
                        type="text"
                        class="form-control"
                        v-model="v$.shipping_location.$model"
                        :class="{
                          'is-invalid': v$.shipping_location.$error,
                        }"
                      />
                      <div class="invalid-feedback">
                        <div v-for="error in v$.shipping_location.$errors" :key="error">
                          {{ $t("global.Location") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                </template>
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
import { required, email } from "@vuelidate/validators";
import clientClient from "../../../http-clients/client-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
import phone from "../../../validators/phone-validator";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const client_store = inject("client_store");
    const data = reactive({
      whatsupPhoneExist: false,
      phoneExist: false,
      emailExist: false,
      areas: [],
      shipping_areas: [],
    });
    const form = reactive({
      id: null,
      user_id: null,
      name: "",
      phone: "",
      email: "",
      store_name: "",
      city_id: null,
      area_id: null,
      address: "",
      location: "",
      whatsup_phone: "",
      responsible_name: "",
      responsible_phone: "",
      same_address_shipping: true,
      shipping_address: "",
      shipping_location: "",
      shipping_area_id: null,
      shipping_city_id: null,
      know_us_way_id: null,
      selling_method_id: null,
    });
    const rules = {
      name: { required },
      phone: {
        required,
        phone(value) {
          return phone(value);
        },
      },
      email: { required, email },
      store_name: { required },
      city_id: { required },
      area_id: { required },
      selling_method_id: {required},
      address: { required },
      location: { required },
      whatsup_phone: {
        required,
        phone(value) {
          return phone(value);
        },
      },
      responsible_name: { required },
      responsible_phone: {
        required,
        phone(value) {
          return phone(value);
        },
      },
      shipping_address: {
        required(value) {
          return !form.same_address_shipping ? value : true;
        },
      },
      shipping_location: {
        required(value) {
          return !form.same_address_shipping ? value : true;
        },
      },
      shipping_area_id: {
        required(value) {
          return !form.same_address_shipping ? value : true;
        },
      },
      shipping_city_id: {
        required(value) {
          return !form.same_address_shipping ? value : true;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedClient) {
        store();
      } else {
        update();
      }
    }
    function onCitySelected() {
      let city = getSelectedCity();
      if (city) {
        data.areas = city.areas;
        if (!city.available) alertMessage(t("global.NotAvailableNow"), "warn");
      }
    }
    function onShippingCitySelected() {
      let city = getSelectedShippingCity();
      if (city) {
        data.shipping_areas = city.areas;
        if (!city.available) alertMessage(t("global.NotAvailableNow"), "warn");
      }
    }
    //Commons
    function getSelectedCity() {
      let city = null;
      props.cities.forEach((_city) => {
        if (form.city_id == _city.id) {
          return (city = _city);
        }
      });
      return city;
    }
    function getSelectedShippingCity() {
      let city = null;
      props.cities.forEach((_city) => {
        if (form.shipping_city_id == _city.id) {
          return (city = _city);
        }
      });
      return city;
    }
    function alertMessage(message, type = "success") {
      notify({
        title: `${t(message)} <i class="fas fa-check-circle"></i>`,
        type: type,
        duration: 5000,
        speed: 2000,
      });
    }
    function store() {
      context.emit("loading", true);
      data.nameExist = false;
      clientClient
        .store(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("created", response.data);
          alertMessage("global.AddedSuccessfully");
          $("#close-button").click();
        })
        .catch((error) => {
          console.log(error.response);
          data.phoneExist = error.response.data.errors.phone ? true : false;
          data.emailExist = error.response.data.errors.email ? true : false;
          data.whatsupPhoneExist = error.response.data.errors.whatsup_phone
            ? true
            : false;
          context.emit("loading", false);
        });
    }
    function update() {
      context.emit("loading", true);
      data.nameExist = false;
      clientClient
        .update(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("updated", response.data);
          $("#close-button").click();
          alertMessage("global.EditSuccessfully");
        })
        .catch((error) => {
          data.phoneExist = error.response.data.errors.phone ? true : false;
          data.emailExist = error.response.data.errors.email ? true : false;
          data.whatsupPhoneExist = error.response.data.errors.whatsup_phone
            ? true
            : false;
          context.emit("loading", false);
        });
    }
    function getForm() {
      return {
        id: props.selectedClient ? props.selectedClient.id : null,
        user_id: props.selectedClient ? props.selectedClient.user.id : null,
        name: form.name,
        phone: form.phone,
        email: form.email,
        store_name: form.store_name,
        city_id: form.city_id,
        area_id: form.area_id,
        address: form.address,
        location: form.location,
        whatsup_phone: form.whatsup_phone,
        responsible_name: form.responsible_name,
        responsible_phone: form.responsible_phone,
        same_address_shipping: form.same_address_shipping,
        shipping_address: form.shipping_address,
        shipping_location: form.shipping_location,
        shipping_area_id: form.shipping_area_id,
        shipping_city_id: form.shipping_city_id,
        know_us_way_id: form.know_us_way_id,
        selling_method_id: form.selling_method_id,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedClient ? props.selectedClient.user.name : "";
      form.phone = props.selectedClient ? props.selectedClient.user.phone : "";
      form.email = props.selectedClient ? props.selectedClient.user.email : "";
      form.store_name = props.selectedClient ? props.selectedClient.store_name : "";
      form.address = props.selectedClient ? props.selectedClient.address : "";
      form.location = props.selectedClient ? props.selectedClient.location : "";
      form.whatsup_phone = props.selectedClient ? props.selectedClient.whatsup_phone : "";
      form.city_id = props.selectedClient ? props.selectedClient.city_id : null;
      onCitySelected();
      form.area_id = props.selectedClient ? props.selectedClient.area_id : null;
      form.responsible_name = props.selectedClient
        ? props.selectedClient.responsible_name
        : "";
      form.responsible_phone = props.selectedClient
        ? props.selectedClient.responsible_phone
        : "";
      form.shipping_address = props.selectedClient
        ? props.selectedClient.shipping_address
        : "";
      form.shipping_location = props.selectedClient
        ? props.selectedClient.shipping_location
        : "";
      form.same_address_shipping = props.selectedClient
        ? Boolean(props.selectedClient.same_address_shipping)
        : true;
      form.shipping_city_id = props.selectedClient
        ? props.selectedClient.shipping_city_id
        : null;
      onShippingCitySelected();
      form.shipping_area_id = props.selectedClient
        ? props.selectedClient.shipping_area_id
        : null;
      form.know_us_way_id = props.selectedClient
        ? props.selectedClient.know_us_way_id
        : null;
        form.selling_method_id = props.selectedClient
        ? props.selectedClient.selling_method_id
        : null;
      data.phoneExist = false;
      data.whatsupPhoneExist = false;
      data.emailExist = false;
    }

    //Watchers
    watch(
      () => {
        client_store.onFormShow;
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
      onCitySelected,
      onShippingCitySelected,
      cities: props.cities,
      knowusWays: props.knowusWays,
      sellingMethods: props.sellingMethods,
    };
  },
  props: ["selectedClient", "cities", "knowusWays","sellingMethods"],
};
</script>

<style scoped lang="scss">
.unit-form {
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
