<template>
  <div
    class="top-footer-sections-container"
    :class="['page-wrapper', $i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <loader v-if="loading" />
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />

    <div class="content container-fluid">
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.TopFooterSections") }}</h3>
            <ul class="breadcrumb mb-3">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.TopFooterSections") }}</li>
            </ul>
            <form @submit.prevent="save">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.FirstSection")
                    }}</label>
                    <input type="text" class="form-control" v-model="first_section" />
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.SecondSection")
                    }}</label>
                    <input type="text" class="form-control" v-model="second_section" />
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{
                      $t("global.ThirdSection")
                    }}</label>
                    <input type="text" class="form-control" v-model="third_section" />
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
import topFooterSectionClient from "../../../http-clients/top-footer-section-client";
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
      first_section: "",
      second_section: "",
      third_section: "",
    });
    const { t, locale } = useI18n({});
    onMounted(() => {
      setForm();
    });
    //Methods
    function save() {
      data.loading = true;
      topFooterSectionClient.save(getForm()).then((response) => {
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
        first_section: form.first_section,
        second_section: form.second_section,
        third_section: form.third_section,
      };
    }
    function setForm() {
      data.loading = true;
      topFooterSectionClient.getTopFooterSections().then((response) => {
        data.loading = false;
        if (response.data) {
          form.first_section = response.data.first_section;
          form.second_section = response.data.second_section;
          form.third_section = response.data.third_section;
        }
      });
    }
    return { ...toRefs(form), ...toRefs(data), save, locale };
  },
};
</script>
<style lang="scss" scoped></style>
