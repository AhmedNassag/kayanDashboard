<template>
  <div
    class="our-store-container"
    :class="['page-wrapper', $i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <loader v-if="loading" />
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />

    <div class="content container-fluid">
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.OurStore") }}</h3>
            <ul class="breadcrumb mb-3">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.OurStore") }}</li>
            </ul>
            <form @submit.prevent="save">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.FirstLine") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.first_line.$model"
                      :class="{
                        'is-invalid': v$.first_line.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.first_line.$errors" :key="error">
                        {{ $t("global.FirstLine") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.SecondLine") }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="v$.second_line.$model"
                      :class="{
                        'is-invalid': v$.second_line.$error,
                      }"
                    />
                    <div class="invalid-feedback">
                      <div v-for="error in v$.second_line.$errors" :key="error">
                        {{ $t("global.SecondLine") + " " + $t(error.$validator) }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Facebook") }}</label>
                    <input type="text" class="form-control" v-model="facebook" />
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Instgram") }}</label>
                    <input type="text" class="form-control" v-model="instgram" />
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Youtube") }}</label>
                    <input type="text" class="form-control" v-model="youtube" />
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.Twitter") }}</label>
                    <input type="text" class="form-control" v-model="twitter" />
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ $t("global.LinkedIn") }}</label>
                    <input type="text" class="form-control" v-model="linked_in" />
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary mb-4">
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
import ourStoreClient from "../../../http-clients/our-store-client";
import { reactive, toRefs } from "@vue/reactivity";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
import { onMounted } from '@vue/runtime-core';
export default {
  setup(props, context) {
    const data = reactive({
      loading: false,
    });
    const form = reactive({
      first_line: "",
      second_line: "",
      facebook: "",
      instgram: "",
      youtube: "",
      twitter: "",
      linked_in: "",
    });
    const rules = {
      first_line: { required },
      second_line: { required },
    };
    const v$ = useVuelidate(rules, form);
    const { t, locale } = useI18n({});
    onMounted(()=>{setForm()})
    //Methods
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      data.loading = true;
      ourStoreClient.save(getForm()).then((response) => {
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
        first_line: form.first_line,
        second_line: form.second_line,
        facebook: form.facebook,
        instgram: form.instgram,
        youtube: form.youtube,
        twitter: form.twitter,
        linked_in: form.linked_in,
      };
    }
    function setForm() {
      data.loading = true;
      ourStoreClient.getOurStore().then((response) => {
        data.loading = false;
        if (response.data) {
          form.first_line = response.data.first_line;
          form.second_line = response.data.second_line;
          form.facebook = response.data.facebook;
          form.instgram = response.data.instgram;
          form.youtube = response.data.youtube;
          form.twitter = response.data.twitter;
          form.linked_in = response.data.linked_in;
        }
      });
    }
    return { ...toRefs(form), ...toRefs(data), v$, save, locale };
  },
};
</script>
<style lang="scss" scoped></style>
