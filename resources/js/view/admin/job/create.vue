<template>
  <div
    :class="[
      'page-wrapper',
      this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
    ]"
  >
    <div class="content container-fluid">
      <notifications
        :position="this.$i18n.locale == 'ar' ? 'top left' : 'top right'"
      />
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.Jobs") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'indexJob' }">{{
                  $t("global.Jobs")
                }}</router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("job.CreateJob") }}</li>
            </ul>
          </div>
        </div>
      </div>
      <!-- /Page Header -->
      <!-- Table -->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <loader v-if="loading" />
            <div class="card-body">
              <div class="card-header pt-0 mb-4">
                <router-link
                  :to="{ name: 'indexJob' }"
                  class="btn btn-custom btn-dark"
                >
                  {{ $t("global.back") }}
                </router-link>
              </div>
              <div class="row">
                <div class="col-sm">
                  <div
                    class="alert alert-danger text-center"
                    v-if="errors['name']"
                  >
                    {{ t("global.Exist", { field: t("global.Name") }) }} <br />
                  </div>
                  <form @submit.prevent="storeJob" class="needs-validation">
                    <div class="form-row row">
                      <div class="col-md-6 mb-3">
                        <label for="validationCustom02">{{
                          $t("global.Name")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model.trim="v$.name.$model"
                          id="validationCustom02"
                          :class="{
                            'is-invalid': v$.name.$error,
                            'is-valid': !v$.name.$invalid,
                          }"
                          :placeholder="$t('global.Name')"
                        />
                        <div class="valid-feedback">
                          {{ $t("global.LooksGood") }}
                        </div>
                        <div class="invalid-feedback">
                          <span v-if="v$.name.required.$invalid"
                            >{{ $t("global.NameIsRequired") }} <br
                          /></span>
                          <span v-if="v$.name.minLength.$invalid"
                            >{{ $t("global.NameIsMustHaveAtLeast") }}
                            {{ v$.name.minLength.$params.min }}
                            {{ $t("global.Letters") }} <br
                          /></span>
                          <span v-if="v$.name.maxLength.$invalid"
                            >{{ $t("global.NameIsMustHaveAtMost") }}
                            {{ v$.name.maxLength.$params.max }}
                            {{ $t("global.Letters") }}
                          </span>
                        </div>
                      </div>

                      <div class="col-md-6 mb-3">
                        <input
                          type="checkbox"
                          v-model="data.Allow_adding_to_sales_team"
                          class="ms-1 mt-5"
                        />
                        <label for="validationCustom02">{{
                          $t("global.AllowAddingToSalesTeam")
                        }}</label>
                      </div>
                    </div>

                    <button class="btn btn-primary" type="submit">
                      {{ $t("global.Submit") }}
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /Table -->
    </div>
  </div>
</template>

<script>
import { computed, onMounted, reactive, toRefs, inject, ref } from "vue";
import useVuelidate from "@vuelidate/core";
import {
  required,
  minLength,
  between,
  maxLength,
  alpha,
  integer,
} from "@vuelidate/validators";
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import { useI18n } from "vue-i18n";

export default {
  name: "create",
  data() {
    return {
      errors: {},
    };
  },
  setup() {
    const emitter = inject("emitter");
    const { t } = useI18n({});
    let loading = ref(false);

    let addJob = reactive({
      data: {
        name: "",
        Allow_adding_to_sales_team: 1,
      },
    });

    const rules = computed(() => {
      return {
        name: {
          minLength: minLength(3),
          maxLength: maxLength(40),
          required,
        },
      };
    });

    const v$ = useVuelidate(rules, addJob.data);

    return { t, loading, ...toRefs(addJob), v$ };
  },
  methods: {
    storeJob() {
      this.v$.$validate();
      if (!this.v$.$error) {
        this.loading = true;
        this.errors = {};
        adminApi
          .post(`/v1/dashboard/job`, this.data)
          .then((res) => {
            notify({
              title: `${this.t(
                "global.AddedSuccessfully"
              )} <i class="fas fa-check-circle"></i>`,
              type: "success",
              duration: 5000,
              speed: 2000,
            });
            this.resetForm();
            this.$nextTick(() => {
              this.v$.$reset();
            });
          })
          .catch((err) => {
            this.errors = err.response.data.errors;
          })
          .finally(() => {
            this.loading = false;
          });
      }
    },
    resetForm() {
      this.data.name = "";
      this.data.Allow_adding_to_sales_team = 0;
    },
  },
};
</script>

<style scoped>
.coustom-select {
  height: 100px;
}
.card {
  position: relative;
}
</style>
