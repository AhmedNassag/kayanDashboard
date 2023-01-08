<template>
    <div class="unit-form">
      <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
      <div
        class="modal fade"
        id="typesOfComplaintsFormModal"
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
                      <label for="exampleInputEmail1">{{ $t("global.NameAr") }}</label>
                      <input
                        type="text"
                        class="form-control"
                        v-model="v$.name_ar.$model"
                        :class="{
                          'is-invalid': v$.name_ar.$error || nameArExist,
                        }"
                      />
                      <div class="invalid-feedback">
                        <div v-for="error in v$.name_ar.$errors" :key="error">
                          {{ $t("global.Name") + " " + $t(error.$validator) }}
                        </div>
                        <div v-if="!v$.name_ar.$invalid && nameArExist">
                          {{ $t("global.Exist", { field: $t("global.NameAr") }) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label for="exampleInputEmail11">{{ $t("global.NameEn") }}</label>
                      <input
                        type="text"
                        class="form-control"
                        v-model="v$.name_en.$model"
                        :class="{
                          'is-invalid': v$.name_en.$error || nameEnExist,
                        }"
                      />
                      <div class="invalid-feedback">
                        <div v-for="error in v$.name_en.$errors" :key="error">
                          {{ $t("global.Name") + " " + $t(error.$validator) }}
                        </div>
                        <div v-if="!v$.name_en.$invalid && nameEnExist">
                          {{ $t("global.Exist", { field: $t("global.NameEn") }) }}
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
  import typesOfComplaintsClient from "../../../../http-clients/types-of-complaints-client";
  import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
  import { useI18n } from "vue-i18n";
  import { notify } from "@kyvg/vue3-notification";
  export default {
    setup(props, context) {
      const { t, locale } = useI18n({});
      const types_of_Complaints_store = inject("types_of_Complaints_store");
      const data = reactive({
        nameArExist: false,
        nameEnExist: false,
      });
      const form = reactive({
        id: null,
        name_ar: "",
        name_en: "",
      });
      const rules = {
        name_ar: { required },
        name_en: { required },
      };
      const v$ = useVuelidate(rules, form);
      //Methods
      function save() {
        if (v$.value.$invalid) {
          v$.value.$touch();
          return;
        }
        if (!props.selected_type_of_complaints_store) {
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
        data.nameArExist = false;
        data.nameEnExist = false;
        typesOfComplaintsClient
          .store(getForm())
          .then((response) => {
            context.emit("loading", false);
            context.emit("created", response.data);
            alertMessage("global.AddedSuccessfully");
            $("#close-button").click();
          })
          .catch((error) => {
            console.log(error.response);
            data.nameArExist = error.response.data.errors.name_ar ? true : false;
            data.nameEnExist = error.response.data.errors.name_en ? true : false;
            context.emit("loading", false);
          });
      }
      function update() {
        context.emit("loading", true);
        data.nameArExist = false;
        data.nameEnExist = false;
        typesOfComplaintsClient
          .update(getForm())
          .then((response) => {
            context.emit("loading", false);
            context.emit("updated", response.data);
            $("#close-button").click();
            alertMessage("global.EditSuccessfully");
          })
          .catch((error) => {
            console.log(error.response);
            data.nameArExist = error.response.data.errors.name_ar ? true : false;
            data.nameEnExist = error.response.data.errors.name_en ? true : false;
            context.emit("loading", false);
          });
      }
      function getForm() {
        return {
          id: props.selected_type_of_complaints_store ? props.selected_type_of_complaints_store.id : null,
          name_ar: form.name_ar,
          name_en: form.name_en,
        };
      }
      function setForm() {
        v$.value.$reset();
        form.name_ar = props.selected_type_of_complaints_store ? props.selected_type_of_complaints_store.name_ar : "";
        form.name_en = props.selected_type_of_complaints_store ? props.selected_type_of_complaints_store.name_en : "";
        data.nameArExist = false;
        data.nameEnExist = false;
      }
      //Watchers
      watch(
        () => {
          types_of_Complaints_store.onFormShow;
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
    props: ["selected_type_of_complaints_store"],
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
