<template>
  <div class="area-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="areaFormModal"
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
                        {{ $t("global.Exist", { field: $t("global.Name") }) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="sel1">{{ $t("global.Cities") }}</label>
                    <select
                      :class="{
                        'is-invalid': v$.city_id.$error,
                      }"
                      v-model="v$.city_id.$model"
                      class="form-control"
                      id="sel1"
                    >
                      <option :value="city.id" v-for="city in cities" :key="city.id">
                        {{ city.name }}
                      </option>
                    </select>
                    <div class="invalid-feedback">
                      <div v-for="error in v$.city_id.$errors" :key="error">
                        {{ $t("global.City") + " " + $t(error.$validator) }}
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
import areaClient from "../../../http-clients/area-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const area_store = inject("area_store");
    const data = reactive({
      nameExist: false,
    });
    const form = reactive({
      id: null,
      name: "",
      city_id: null,
    });
    const rules = {
      name: { required },
      city_id: { required },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedArea) {
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
      areaClient
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
      areaClient
        .update(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("updated", response.data);
          $("#close-button").click();
          alertMessage("global.EditSuccessfully");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name ? true : false;
          context.emit("loading", false);
        });
    }
    function getForm() {
      return {
        id: props.selectedArea ? props.selectedArea.id : null,
        name: form.name,
        city_id: form.city_id,
        city_name: getCurrentCityName(),
      };
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedArea ? props.selectedArea.name : "";
      form.city_id = getCurrentCityId();
      data.nameExist = false;
    }
    function getCurrentCityName() {
      let city = null;
      props.cities.forEach((_city) => {
        if (_city.id == form.city_id) {
          return (city = _city);
        }
      });
      return city.name;
    }
    function getCurrentCityId() {
      let firstCityId = props.cities.length > 0 ? props.cities[0].id : null;
      return props.selectedArea ? props.selectedArea.city_id : firstCityId;
    }
    //Watchers
    watch(
      () => {
        area_store.onFormShow;
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
      cities: props.cities,
    };
  },
  props: ["selectedArea", "cities"],
};
</script>

<style scoped lang="scss">
.area-form {
  .form-control{
    padding: 10px!important;
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
