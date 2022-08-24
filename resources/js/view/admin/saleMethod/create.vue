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
            <h3 class="page-title">{{ $t("global.Sale Method") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'indexSaleMethod' }">
                    {{ $t("global.Sale Method") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">
                {{ $t("saleMethod.CreateSaleMethod") }}
              </li>
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
                  :to="{ name: 'indexSaleMethod' }"
                  class="btn btn-custom btn-dark"
                >
                  {{ $t("global.back") }}
                </router-link>
              </div>
              <div class="row">
                <div class="col-sm">
                  <div class="alert alert-danger text-center" v-if="errors['name']">
                    {{ t("global.Exist", {field:t("global.Name")}) }}<br />
                  </div>
                  <form
                    @submit.prevent="storeSaleMethod"
                    class="needs-validation"
                  >
                    <div class="form-row row">
                      <div class="col-md-6 mb-3">
                        <label for="validationCustom01">
                            {{ $t("global.Name") }}
                        </label>
                        <input
                          type="text"
                          class="form-control"
                          v-model.trim="v$.name.$model"
                          id="validationCustom01"
                          :placeholder="$t('global.Name')"
                          :class="{
                            'is-invalid': v$.name.$error || data.nameExist,
                            'is-valid': !v$.name.$invalid,
                          }"
                        />
                        <div class="valid-feedback">
                            {{ $t("global.LooksGood") }}
                        </div>
                        <div class="invalid-feedback">
                          <span v-if="v$.name.required.$invalid">
                            {{ $t("global.NameIsRequired") }}
                            <br/>
                          </span>
                          <span v-if="v$.name.maxLength.$invalid">
                            {{ $t("global.NameIsMustHaveAtLeast") }}
                            {{ v$.name.minLength.$params.min }}
                            {{ $t("global.Letters") }}
                            <br/>
                          </span>
                          <span v-if="v$.name.minLength.$invalid">
                            {{ $t("global.NameIsMustHaveAtMost") }}
                            {{ v$.name.maxLength.$params.max }}
                            {{ $t("global.Letters") }}
                            <br/>
                          </span>
                          <span v-if="!v$.name.$invalid && data.nameExist">
                           {{ $t("global.NameIsExist") }}
                          </span>
                        </div>
                      </div>

                    </div>

                    <button class="btn btn-primary" type="submit">{{ $t("global.Submit") }}</button>
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
//
import { computed, onMounted, reactive, toRefs, inject, ref } from "vue";
//
//import { computed, onMounted, reactive, toRefs, ref } from "vue";
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
//
import { useI18n } from "vue-i18n";
//

export default {
  name: "createSaleMethod",
  data() {
    return {
      errors: {},
    };
  },
  setup() {
    //
    const emitter = inject("emitter");
    const { t } = useI18n({});
    //
    let loading = ref(false);

    //start design
    let addSaleMethod = reactive({
      data: {
        name: "",
        nameExist: false,
      },
    });

    const rules = computed(() => {
      return {
        name: {
          minLength: minLength(3),
          maxLength: maxLength(70),
          required,
        },
      };
    });

    const v$ = useVuelidate(rules, addSaleMethod.data);

    return { loading, ...toRefs(addSaleMethod), v$ };

  },
  methods: {
    storeSaleMethod() {
      this.v$.$validate();

      if (!this.v$.$error) {
        this.loading = true;
        this.errors = {};
        let formData = new FormData();
        formData.append("name", this.data.name);

        adminApi
          .post(`/v1/dashboard/saleMethod`, formData)
          .then((res) => {
            notify({
              title: `تم الإضافة بنجاح <i class="fas fa-check-circle"></i>`,
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
            this.nameExist = err.response.data.errors;
          })
          .finally(() => {
            this.loading = false;
          });
      }
    },
    resetForm() {
      this.data.name = "";
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

.waves-effect {
  position: relative;
  overflow: hidden;
  cursor: pointer;
  user-select: none;
  -webkit-tap-highlight-color: transparent;
  width: 200px;
  height: 50px;
  text-align: center;
  line-height: 34px;
  margin: auto;
}

input[type="file"] {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  padding: 0;
  margin: 0;
  cursor: pointer;
  filter: alpha(opacity=0);
  opacity: 0;
}

.num-of-files {
  text-align: center;
  margin: 20px 0 30px;
}

.container-images {
  width: 90%;
  position: relative;
  margin: auto;
  display: flex;
  justify-content: space-evenly;
  gap: 20px;
  flex-wrap: wrap;
  padding: 10px;
  border-radius: 20px;
  background-color: #f7f7f7;
}
</style>
