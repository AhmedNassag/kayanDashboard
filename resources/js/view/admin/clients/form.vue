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
                <div class="col-12">
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
                      $t("global.Email")
                    }}</label>
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
                        {{ $t("global.Exist", { field: $t("global.Phone") }) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.Password")
                    }}</label>
                    <input
                      id="validationCustom01"
                      :type="hidden ? 'password' : 'text'"
                      class="form-control"
                      v-model="v$.password.$model"
                      :class="{
                        'is-invalid': v$.password.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.password.$errors" :key="error">
                        {{ $t("global.Password") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.confirm")
                    }}</label>
                    <input
                      id="validationCustom09"
                      :type="hidden ? 'password' : 'text'"
                      class="form-control"
                      v-model="v$.confirmation.$model"
                      :class="{
                        'is-invalid': v$.confirmation.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.confirmation.$errors"
                        :key="error"
                      >
                        {{ $t("global.confirm") + " " + $t(error.$validator) }}
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
                        @click="hidden = !hidden"
                      />{{ $t("global.ShowPassword") }}
                    </label>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.StoreName")
                    }}</label>
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
                        {{
                          $t("global.StoreName") + " " + $t(error.$validator)
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.Country")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.country.$model"
                      :class="{
                        'is-invalid': v$.country.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.country.$errors" :key="error">
                        {{ $t("global.Country") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.City")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.city.$model"
                      :class="{
                        'is-invalid': v$.city.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.city.$errors" :key="error">
                        {{ $t("global.City") + " " + $t(error.$validator) }}
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
                      $t("global.Location")
                    }}</label>
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
                      $t("global.Area")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.area.$model"
                      :class="{
                        'is-invalid': v$.area.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.area.$errors" :key="error">
                        {{ $t("global.Area") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.WhatsupPhone")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.whatsup_phone.$model"
                      :class="{
                        'is-invalid':
                          v$.whatsup_phone.$error || whatsupPhoneExist,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div
                        v-for="error in v$.whatsup_phone.$errors"
                        :key="error"
                      >
                        {{
                          $t("global.WhatsupPhone") + " " + $t(error.$validator)
                        }}
                      </div>
                      <div
                        v-if="!v$.whatsup_phone.$invalid && whatsupPhoneExist"
                      >
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
                      <div
                        v-for="error in v$.responsible_name.$errors"
                        :key="error"
                      >
                        {{
                          $t("global.ResponsibleName") +
                          " " +
                          $t(error.$validator)
                        }}
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
                      <label for="exampleInputEmail1">{{
                        $t("global.Country")
                      }}</label>
                      <input
                        type="text"
                        class="form-control"
                        v-model="v$.shipping_country.$model"
                        :class="{
                          'is-invalid': v$.shipping_country.$error,
                        }"
                      />
                      <div class="invalid-feedback">
                        <div
                          v-for="error in v$.shipping_country.$errors"
                          :key="error"
                        >
                          {{
                            $t("global.Country") + " " + $t(error.$validator)
                          }}
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
                        v-model="v$.shipping_address.$model"
                        :class="{
                          'is-invalid': v$.shipping_address.$error,
                        }"
                      />
                      <div class="invalid-feedback">
                        <div
                          v-for="error in v$.shipping_address.$errors"
                          :key="error"
                        >
                          {{
                            $t("global.Address") + " " + $t(error.$validator)
                          }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">{{
                        $t("global.Location")
                      }}</label>
                      <input
                        type="text"
                        class="form-control"
                        v-model="v$.shipping_location.$model"
                        :class="{
                          'is-invalid': v$.shipping_location.$error,
                        }"
                      />
                      <div class="invalid-feedback">
                        <div
                          v-for="error in v$.shipping_location.$errors"
                          :key="error"
                        >
                          {{
                            $t("global.Location") + " " + $t(error.$validator)
                          }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">{{
                        $t("global.Area")
                      }}</label>
                      <input
                        type="text"
                        class="form-control"
                        v-model="v$.shipping_area.$model"
                        :class="{
                          'is-invalid': v$.shipping_area.$error,
                        }"
                      />
                      <div class="invalid-feedback">
                        <div
                          v-for="error in v$.shipping_area.$errors"
                          :key="error"
                        >
                          {{ $t("global.Area") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="exampleInputEmail1">{{
                        $t("global.City")
                      }}</label>
                      <input
                        type="text"
                        class="form-control"
                        v-model="v$.shipping_city.$model"
                        :class="{
                          'is-invalid': v$.shipping_city.$error,
                        }"
                      />
                      <div class="invalid-feedback">
                        <div
                          v-for="error in v$.shipping_city.$errors"
                          :key="error"
                        >
                          {{ $t("global.City") + " " + $t(error.$validator) }}
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
import { required, email } from "@vuelidate/validators";
import clientClient from "../../../http-clients/client-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
import phone from "../../../validators/phone-validator";
import strong from "../../../validators/strong-password-validator";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const client_store = inject("client_store");
    const data = reactive({
      whatsupPhoneExist: false,
      phoneExist: false,
      emailExist: false,
      hidden: true,
    });
    const form = reactive({
      id: null,
      user_id: null,
      name: "",
      phone: "",
      email: "",
      password: "",
      confirmation: "",
      store_name: "",
      country: "",
      city: "",
      address: "",
      location: "",
      area: "",
      whatsup_phone: "",
      responsible_name: "",
      responsible_phone: "",
      same_address_shipping: true,
      shipping_country: "",
      shipping_address: "",
      shipping_location: "",
      shipping_area: "",
      shipping_city: "",
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
      country: { required },
      city: { required },
      address: { required },
      location: { required },
      area: { required },
      password: { required, strong },
      confirmation: {
        required,
        sameAsPassword(value) {
          return !value || form.password == form.confirmation;
        },
      },
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
      shipping_country: {
        required(value) {
          return !form.same_address_shipping ? value : true;
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
      shipping_area: {
        required(value) {
          return !form.same_address_shipping ? value : true;
        },
      },
      shipping_city: {
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
        password: form.password,
        store_name: form.store_name,
        country: form.country,
        city: form.city,
        address: form.address,
        location: form.location,
        area: form.area,
        whatsup_phone: form.whatsup_phone,
        responsible_name: form.responsible_name,
        responsible_phone: form.responsible_phone,
        same_address_shipping: form.same_address_shipping,
        shipping_country: form.shipping_country,
        shipping_address: form.shipping_address,
        shipping_location: form.shipping_location,
        shipping_area: form.shipping_area,
        shipping_city: form.shipping_city,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedClient ? props.selectedClient.user.name : "";
      form.password = "";
      form.confirmation = "";
      form.phone = props.selectedClient ? props.selectedClient.user.phone : "";
      form.email = props.selectedClient ? props.selectedClient.user.email : "";
      form.store_name = props.selectedClient
        ? props.selectedClient.store_name
        : "";
      form.country = props.selectedClient ? props.selectedClient.country : "";
      form.city = props.selectedClient ? props.selectedClient.city : "";
      form.address = props.selectedClient ? props.selectedClient.address : "";
      form.location = props.selectedClient ? props.selectedClient.location : "";
      form.area = props.selectedClient ? props.selectedClient.area : "";
      form.whatsup_phone = props.selectedClient
        ? props.selectedClient.whatsup_phone
        : "";
      form.responsible_name = props.selectedClient
        ? props.selectedClient.responsible_name
        : "";
      form.responsible_phone = props.selectedClient
        ? props.selectedClient.responsible_phone
        : "";
      form.shipping_country = props.selectedClient
        ? props.selectedClient.shipping_country
        : "";
      form.shipping_address = props.selectedClient
        ? props.selectedClient.shipping_address
        : "";
      form.shipping_location = props.selectedClient
        ? props.selectedClient.shipping_location
        : "";
      form.shipping_area = props.selectedClient
        ? props.selectedClient.shipping_area
        : "";
      form.shipping_city = props.selectedClient
        ? props.selectedClient.shipping_city
        : "";
      form.same_address_shipping = props.selectedClient
        ? Boolean(props.selectedClient.same_address_shipping)
        : true;
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
    };
  },
  props: ["selectedClient"],
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