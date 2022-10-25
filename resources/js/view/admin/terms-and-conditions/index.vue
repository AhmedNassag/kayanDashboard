<template>
  <div
    class="term-and-condition-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <loader v-if="loading" />
    <notifications :position="this.$i18n.locale == 'ar' ? 'top left' : 'top right'" />
    <div class="container">
      <form @submit.prevent="save">
        <div class="form-group">
          <label for="exampleInputEmail1">{{ $t("sidebar.TermsAndConditions") }}</label>
          <textarea
            type="text"
            class="form-control"
            v-model="v$.context.$model"
            rows="5"
            :class="{
              'is-invalid': v$.context.$error,
            }"
          >
          </textarea>
          <div class="invalid-feedback">
            <div v-for="error in v$.context.$errors" :key="error">
              {{ $t("global.Context") + " " + $t(error.$validator) }}
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">
          {{ $t("global.Submit") }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { reactive, toRefs } from "@vue/reactivity";
import { required } from "@vuelidate/validators";
import { onMounted } from "@vue/runtime-core";
import termAndConditionClient from "../../../http-clients/term-and-condition-client";
import { notify } from "@kyvg/vue3-notification";
import useVuelidate from "@vuelidate/core";
import { useI18n } from "vue-i18n";
export default {
  setup() {
    const { t } = useI18n({});
    const form = reactive({
      context: "",
    });
    const data = reactive({
      loading: false,
    });
    const rules = {
      context: { required },
    };
    onMounted(() => {
      termAndConditionClient.getTermsAndConditions().then((response) => {
        form.context = response.data ? response.data.context : "";
      });
    });
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      data.loading = true;
      termAndConditionClient
        .insertTermsAndConditions({
          context: form.context,
        })
        .then((response) => {
          data.loading = false;
          alertMessage(
            `${t("global.CreatedSuccessfully")} <i class="fas fa-check-circle"></i>`,
            "success"
          );
        });
    }
    //Commons
    function alertMessage(message, type) {
      notify({
        title: message,
        type: type,
        duration: 5000,
        speed: 2000,
      });
    }
    const v$ = useVuelidate(rules, form);
    return { v$, ...toRefs(form), ...toRefs(data), save };
  },
};
</script>

<style lang="scss" scoped>
.term-and-condition-container {
  margin-top: 50px;
}
</style>
