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
              <h3 class="page-title">{{ $t("global.Complaints") }}</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item">
                  <router-link :to="{ name: 'indexComplaint' }">{{
                    $t("global.complaints")
                  }}</router-link>
                </li>
                <li class="breadcrumb-item active">
                  {{ $t("complaint.EditComplaint") }}
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
                    :to="{ name: 'indexComplaint' }"
                    class="btn btn-custom btn-dark"
                  >
                    {{ $t("global.back") }}
                  </router-link>
                </div>
                <div class="row">
                  <div class="col-sm">
                    <div
                      class="alert alert-danger text-center"
                      v-if="errors['content']"
                    >
                      {{ t("global.Exist", { field: t("global.Content") }) }}
                      <br />
                    </div>
                    <form
                      @submit.prevent="editComplaint"
                      class="needs-validation"
                    >
                      <div class="form-row row">
                        <!--Start Type Select-->
                        <div class="col-md-7 mb-3">
                          <label for="validationCustom0">
                            {{ $t("global.Type") }}
                          </label>
                          <select
                            class="form-select"
                            v-model.trim="v$.type.$model"
                          >
                              <option
                                :value="item.id"
                                v-for="item in types"
                                :key="item.id"
                              >
                                {{ item.name }}
                              </option>
                          </select>
                        </div>
                        <!--End Type Select-->
                        <div class="col-md-7 mb-3">
                            <label for="validationCustom022">
                                {{ $t("global.Name") }}
                            </label>
                           <input type="text" class="form-control"  v-model.trim="v$.name.$model" id="validationCustom022">
                        </div>
                        <div class="col-md-7 mb-3">
                            <label for="validationCustom025">
                                {{ $t("global.Phone") }}
                            </label>
                           <input type="text" class="form-control"  v-model.trim="v$.phone.$model" id="validationCustom025">
                        </div>


                        <!--Start Content-->
                        <div class="col-md-7 mb-3">
                          <label for="validationCustom01">
                            {{ $t("global.Content") }}
                          </label>
                          <textarea
                            type="text"
                            class="form-control custom-textarea"
                            cols="10"
                            rows="10"
                            v-model.trim="v$.content.$model"
                            id="validationCustom034"
                            :placeholder="$t('global.Content')"
                            :class="{
                              'is-invalid': v$.content.$error,
                              'is-valid': !v$.content.$invalid,
                            }"
                          ></textarea>
                          <div class="valid-feedback">
                            {{ $t("global.LooksGood") }}
                          </div>
                          <div class="invalid-feedback">
                            <span v-if="v$.content.required.$invalid">
                              {{ $t("global.NameIsRequired") }}
                              <br />
                            </span>
                            <span v-if="v$.content.maxLength.$invalid">
                              {{ $t("global.NameIsMustHaveAtLeast") }}
                              {{ v$.content.minLength.$params.min }}
                              {{ $t("global.Letters") }}
                              <br />
                            </span>
                            <span v-if="v$.content.minLength.$invalid">
                              {{ $t("global.NameIsMustHaveAtMost") }}
                              {{ v$.content.maxLength.$params.max }}
                              {{ $t("global.Letters") }}
                              <br />
                            </span>
                          </div>
                        </div>
                        <!--End Content-->
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
  import { required, minLength, maxLength, integer } from "@vuelidate/validators";
  import adminApi from "../../../api/adminAxios";
  import { notify } from "@kyvg/vue3-notification";
  import { useI18n } from "vue-i18n";

  export default {
    name: "editComplaint",
    data() {
      return {
        errors: {},
      };
    },
    props: ["id"],
    setup(props) {
      const emitter = inject("emitter");
      const { id } = toRefs(props);
      const { t } = useI18n({});
      const types =ref({});

      // get create Package
      let loading = ref(false);

      let getComplaint = () => {
        loading.value = true;

        adminApi
          .get(`/v1/dashboard/complaint/${id.value}/edit`)
          .then((res) => {
            let l = res.data.data;
            addComplaint.data.type = l.complaint.type;
            addComplaint.data.content = l.complaint.content;
            addComplaint.data.name = l.complaint.name;
            addComplaint.data.phone = l.complaint.phone;
            addComplaint.data.platform = l.complaint.platform;
          })
          .catch((err) => {
            this.errors = err.response.data.errors;
            console.log(err.response);
            // Swal.fire({
            //     icon: 'error',
            //     title: 'يوجد خطأ...',
            //     text: 'يوجد خطأ ما..!!',
            // });
          })
          .finally(() => {
            loading.value = false;
          });
      };

      onMounted(() => {
        adminApi.get('/v1/dashboard/all_types_of_complaints').then((response) => {
              types.value = response.data.types
          })
          getComplaint();
      });

      //start design
      let addComplaint = reactive({
        data: {
          platform: "",
          type: null,
          content: "",
          name: "",
          phone: "",
        },
      });

      const rules = computed(() => {
        return {
          type: {
            // required
          },
          name: {
            // required
          },
          phone: {
            // required
          },
          content: {
            minLength: minLength(3),
            maxLength: maxLength(70),
            required,
          },
        };
      });

      const v$ = useVuelidate(rules, addComplaint.data);

      return { id, loading, ...toRefs(addComplaint), v$ ,types};
    },
    methods: {
      editComplaint() {
        this.v$.$validate();

        if (!this.v$.$error) {
          this.loading = true;
          this.errors = {};

          let formData = new FormData();
          if(this.data.type)
          formData.append("type", this.data.type);
          formData.append("content", this.data.content);
          formData.append("phone", this.data.phone);
          formData.append("name", this.data.name);
          formData.append("platform", this.data.platform);
          formData.append("_method", "PUT");

          adminApi
            .post(`/v1/dashboard/complaint/${this.id}`, formData)
            .then((res) => {
              notify({
                title: `تم التعديل بنجاح <i class="fas fa-check-circle"></i>`,
                type: "success",
                duration: 5000,
                speed: 2000,
              });
            })
            .catch((err) => {
              this.errors = err.response.data.errors;
              // Swal.fire({
              //     icon: "error",
              //     title: `${t("global.ThereIsAnErrorInTheSystem")}`,
              //     text: `${t("global.YouCanNotDelete")}`,
              // });
            })
            .finally(() => {
              this.loading = false;
            });
        }
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
