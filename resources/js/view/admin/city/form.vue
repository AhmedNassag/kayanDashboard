<template>
  <div class="city-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="cityFormModal"
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
                  <div class="form-group">
                    <div class="custom-control custom-checkbox mr-sm-2">
                      <input
                        id="workflow"
                        name="workflow"
                        class="custom-control-input"
                        type="checkbox"
                        v-model="available"
                      />
                      <label class="custom-control-label" for="workflow">{{
                        $t("global.Available")
                      }}</label>
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
import cityClient from "../../../http-clients/city-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const city_store = inject("city_store");
    const data = reactive({
      nameExist: false,
    });
    const form = reactive({
      id: null,
      name: "",
      available: true,
    });
    const rules = {
      name: { required },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedCity) {
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
      cityClient
        .store(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("created", response.data);
          alertMessage("global.AddedSuccessfully");
          $("#close-button").click();
        })
        .catch((error) => {
          console.log(error.response)
          data.nameExist = error.response.data.errors.name ? true : false;
          context.emit("loading", false);
        });
    }
    function update() {
      context.emit("loading", true);
      data.nameExist = false;
      cityClient
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
        id: props.selectedCity ? props.selectedCity.id : null,
        name: form.name,
        available: form.available,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedCity ? props.selectedCity.name : "";
      form.available = props.selectedCity ? Boolean(props.selectedCity.available): true;
      data.nameExist = false;
    }
    //Watchers
    watch(
      () => {
        city_store.onFormShow;
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
  props: ["selectedCity"],
};
</script>

<style scoped lang="scss">
.city-form {
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
