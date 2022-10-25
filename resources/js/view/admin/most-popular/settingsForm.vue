<template>
  <div class="most-popular-form">
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
                <div class="col-12">
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
import mostPopularClient from "../../../http-clients/most-popular-client";
import { onMounted, reactive, toRefs } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({});

    const data = reactive({
      loading: false,
      mostPopularSettings: null,
    });
    const form = reactive({
      limit: 2,
    });
    const rules = {
      limit: { required, minValue: minValue(2) },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      getMostPopularSettings();
    });
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      insertMostPopularSettings();
    }
    //Commons
    function getMostPopularSettings() {
      context.emit("loading", true);
      mostPopularClient.getMostPopularSettings().then((response) => {
        context.emit("loading", false);
        data.mostPopularSettings = response.data;
        form.limit = data.mostPopularSettings ? data.mostPopularSettings.limit : 2;
      });
    }
    function insertMostPopularSettings() {
      context.emit("loading", true);
      mostPopularClient
        .insertMostPopularSettings(getForm())
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
  props: ["mostPopularSettings"],
};
</script>

<style scoped lang="scss">
.most-popular-form {
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
