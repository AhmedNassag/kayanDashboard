<template>
  <div class="unit-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="offerFormModal"
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
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.Discount")
                    }}</label>
                    <input
                      type="number"
                      class="form-control"
                      v-model="v$.discount.$model"
                      :class="{
                        'is-invalid': v$.discount.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.discount.$errors" :key="error">
                        {{
                          $t("global.Discount") +
                          " " +
                          $t(error.$validator, {
                            minValue: minDiscount,
                            maxValue: maxDiscount,
                          })
                        }}
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
                        v-model="ratio"
                      />
                      <label class="custom-control-label" for="workflow">{{
                        $t("global.Percentage")
                      }}</label>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.StartDate")
                    }}</label>
                    <input
                      type="date"
                      class="form-control"
                      v-model="v$.start_date.$model"
                      :class="{
                        'is-invalid': v$.start_date.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.start_date.$errors" :key="error">
                        {{
                          $t("global.StartDate") +
                          " " +
                          $t(error.$validator, {
                            date: $t("global.CurrentDate"),
                          })
                        }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.EndDate")
                    }}</label>
                    <input
                      type="date"
                      class="form-control"
                      v-model="v$.end_date.$model"
                      :class="{
                        'is-invalid': v$.end_date.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.end_date.$errors" :key="error">
                        {{
                          $t("global.EndDate") +
                          " " +
                          $t(error.$validator, {
                            date: $t("global.StartDate"),
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
import { minValue, required } from "@vuelidate/validators";
import offerClient from "../../../http-clients/offer-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const offer_store = inject("offer_store");
    const data = reactive({
      nameExist: false,
      minDiscount: 0,
      maxDiscount: 100,
    });
    const form = reactive({
      id: null,
      name: "",
      discount: 0,
      start_date: "",
      end_date: "",
      ratio: false,
    });
    const rules = {
      name: { required },
      start_date: {
        required,
        minCurrentDate(value) {
          let currentDate = new Date();
          currentDate.setHours(0, 0, 0, 0);
          return !value || new Date(value) >= currentDate;
        },
      },
      end_date: {
        required,
        afterDate(value) {
          return (
            !value ||
            !form.start_date ||
            new Date(value) > new Date(form.start_date)
          );
        },
      },
      discount: {
        required,
        minValue: minValue(data.minDiscount),
        maxDiscountRatio(value) {
          return !value || form.ratio ? value <= 100 : true;
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
      if (!props.selectedOffer) {
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
      offerClient
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
      offerClient
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
        id: props.selectedOffer ? props.selectedOffer.id : null,
        name: form.name,
        discount: form.discount,
        start_date: form.start_date,
        end_date: form.end_date,
        ratio: form.ratio,
      };
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedOffer ? props.selectedOffer.name : "";
      form.discount = props.selectedOffer ? props.selectedOffer.discount : 0;
      form.start_date = props.selectedOffer
        ? props.selectedOffer.start_date
        : "";
      form.end_date = props.selectedOffer ? props.selectedOffer.end_date : "";
      form.ratio =
        props.selectedOffer && props.selectedOffer.ratio ? true : false;
      data.nameExist = false;
    }
    //Watchers
    watch(
      () => {
        offer_store.onFormShow;
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
  props: ["selectedOffer"],
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