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
            v-model="v$.terms.$model"
            rows="5"
            :class="{
              'is-invalid': v$.terms.$error,
            }"
          >
          </textarea>
          <div class="invalid-feedback">
            <div v-for="error in v$.terms.$errors" :key="error">
              {{ $t("global.TermsAndConditions ") + " " + $t(error.$validator) }}
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail2">{{ $t("global.Return Policy") }}</label>
          <textarea
            type="text"
            class="form-control"
            v-model="v$.policy.$model"
            rows="5"
            :class="{
              'is-invalid': v$.policy.$error,
            }"
          >
          </textarea>
          <div class="invalid-feedback">
            <div v-for="error in v$.policy.$errors" :key="error">
              {{ $t("global.Return Policy") + " " + $t(error.$validator) }}
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail3">{{ $t("global.Delivery information") }}</label>
          <textarea
            type="text"
            class="form-control"
            v-model="v$.delivery.$model"
            rows="5"
            :class="{
              'is-invalid': v$.delivery.$error,
            }"
          >
          </textarea>
          <div class="invalid-feedback">
            <div v-for="error in v$.delivery.$errors" :key="error">
              {{ $t("global.Delivery information") + " " + $t(error.$validator) }}
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail3">{{ $t("global.Privacy Policy") }}</label>
          <textarea
            type="text"
            class="form-control"
            v-model="v$.privacy.$model"
            rows="5"
            :class="{
              'is-invalid': v$.privacy.$error,
            }"
          >
          </textarea>
          <div class="invalid-feedback">
            <div v-for="error in v$.privacy.$errors" :key="error">
              {{ $t("global.Privacy Policy") + " " + $t(error.$validator) }}
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
      terms: "",
      policy: "",
      delivery: "",
      privacy: "",
    });
    const data = reactive({
      loading: false,
    });
    const rules = {
      terms: { required },
      policy: { required },
      privacy: { required },
      delivery: { required },
    };
    onMounted(() => {
      termAndConditionClient.getTermsAndConditions().then((response) => {
        form.terms = response.data ? response.data.terms : "";
        form.policy = response.data ? response.data.policy : "";
        form.delivery = response.data ? response.data.delivery : "";
        form.privacy = response.data ? response.data.privacy : "";
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
          terms: form.terms,
          policy: form.policy,
          delivery: form.delivery,
          privacy: form.privacy,
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
