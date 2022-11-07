<template>
  <div
    class="need-help-container"
    :class="['page-wrapper', $i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <loader v-if="loading" />
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />

    <div class="content container-fluid">
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.NeedHelp") }}</h3>
            <ul class="breadcrumb mb-3">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.NeedHelp") }}</li>
            </ul>
            <form @submit.prevent="save">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Header") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.header.$model"
                      :class="{
                        'is-invalid': v$.header.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.header.$errors" :key="error">
                        {{ $t("global.Header") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.StartWorkDeadline")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.start_work_deadline.$model"
                      :class="{
                        'is-invalid': v$.start_work_deadline.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.start_work_deadline.$errors" :key="error">
                        {{ $t("global.StartWorkDeadline") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.EndWorkDeadline")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.end_work_deadline.$model"
                      :class="{
                        'is-invalid': v$.end_work_deadline.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.end_work_deadline.$errors" :key="error">
                        {{ $t("global.EndWorkDeadline") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Email") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.email.$model"
                      :class="{
                        'is-invalid': v$.email.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.email.$errors" :key="error">
                        {{ $t("global.Email") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">
                {{ $t("global.Submit") }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import needHelpClient from "../../../http-clients/need-help-client";
import { reactive, toRefs } from "@vue/reactivity";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
import { onMounted } from "@vue/runtime-core";
export default {
  setup(props, context) {
    const data = reactive({
      loading: false,
    });
    const form = reactive({
      header: "",
      start_work_deadline: "",
      start_work_endline: "",
      email: "",
    });
    const rules = {
      header: { required },
      email: { required, email },
      start_work_deadline: { required },
      end_work_deadline: { required },
    };
    const v$ = useVuelidate(rules, form);
    const { t, locale } = useI18n({});
    onMounted(() => {setForm()});
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      data.loading = true;
      needHelpClient.save(getForm()).then((response) => {
        data.loading = false;
        alertMessage("global.UpdatedSuccessfully");
      });
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
    function getForm() {
      return {
        header: form.header,
        start_work_deadline: form.start_work_deadline,
        end_work_deadline: form.end_work_deadline,
        email: form.email,
      };
    }
    function setForm() {
      data.loading = true;
      needHelpClient.getNeedHelp().then((response) => {
      data.loading = false;
        if (response.data) {
          form.header = response.data.header;
          form.start_work_deadline = response.data.start_work_deadline;
          form.end_work_deadline = response.data.end_work_deadline;
          form.email = response.data.email;
        }
      });
    }
    return { ...toRefs(form), ...toRefs(data), v$, save, locale };
  },
};
</script>
<style lang="scss" scoped></style>
