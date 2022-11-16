<template>
  <div class="area-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="settingsFormModal"
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
                    <label for="exampleInputEmail1">{{ $t("global.EndAt") }}</label>
                    <input
                      type="datetime-local"
                      class="form-control"
                      v-model="v$.end_at.$model"
                      :class="{
                        'is-invalid': v$.end_at.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.end_at.$errors" :key="error">
                        {{
                          $t("global.EndAt") +
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
                    <label for="exampleInputEmail1">{{ $t("global.Limit") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.limit.$model"
                      :class="{
                        'is-invalid': v$.limit.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.limit.$errors" :key="error">
                        {{
                          $t("global.Limit") +
                          " " +
                          $t(error.$validator, {
                            minValue: 2,
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
import { minValue, required } from "@vuelidate/validators";
import { onMounted, reactive, toRefs } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
import adminApi from "../../../api/adminAxios";

export default {
  setup(props, context) {
    const { t, locale } = useI18n({});
    const data = reactive({
      loading: false,
      dealSettings: null,
    });
    const form = reactive({
      end_date: "",
      limit: 2,
    });
    const rules = {
      end_at: {
        required,
        minCurrentDate(value) {
          let currentDate = new Date();
          currentDate.setHours(0, 0, 0, 0);
          return !value || new Date(value) >= currentDate;
        },
      },
      limit: { required, minValue: minValue(2) },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      getDealSettings();
    });
    function insertDealSettingsRequest(formValue) {
        return adminApi.post(`/v1/dashboard/best_offers/settings`, formValue);
    }
    function getDealSettingsRequest() {
        return adminApi.get(`/v1/dashboard/best_offers/settings`);
    }
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      insertDealSettings();
    }
    //Commons
    function getDealSettings() {
      context.emit("loading", true);
      getDealSettingsRequest().then((response) => {
        context.emit("loading", false);
        data.dealSettings = response.data;
        form.end_at = data.dealSettings ? data.dealSettings.end_at : "";
        form.limit = data.dealSettings ? data.dealSettings.limit : 2;
      });
    }
    function insertDealSettings() {
      context.emit("loading", true);
      insertDealSettingsRequest(getForm())
        .then((response) => {
          context.emit("loading", false);
          alertMessage("global.UpdatedSuccessfully");
          $("#settingsFormModal #close-button").click();
        })
        .catch((error) => {
          context.emit("loading", false);
        });
    }
    function alertMessage(message) {
      notify({
        title: `${t(message)} <i class="fas fa-check-circle"></i>`,
        type: "success",
        duration: 5000,
        speed: 2000,
      });
    }
    function getForm() {
      return {
        end_at: form.end_at,
        limit: form.limit,
      };
    }
    return {
      ...toRefs(data),
      ...toRefs(form),
      v$,
      locale,
      save,
    };
  },
  props: ["dealSettings"],
};
</script>

<style scoped lang="scss">
.area-form {
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
