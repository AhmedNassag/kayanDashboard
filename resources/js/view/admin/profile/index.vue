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
            <h3 class="page-title">{{ $t("global.Profile") }}</h3>
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
              <div class="row">
                <div class="col-sm">

                  <form @submit.prevent="editEmployee" class="needs-validation">
                    <div class="form-row row">
                      <div class="col-md-6 mb-3">
                        <label for="validationCustom01">{{
                          $t("global.Name")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model.trim="v$.name.$model"
                          id="validationCustom01"
                          :placeholder="$t('global.Name')"
                          :class="{
                            'is-invalid': v$.name.$error || errors.name,
                            'is-valid': !v$.name.$invalid && !errors.name,
                          }"
                        />
                        <div class="valid-feedback" v-if="!errors.name">
                          {{ $t("global.LooksGood") }}
                        </div>
                        <div class="invalid-feedback" v-if="errors.name">{{errors.name}}</div>
                        <div class="invalid-feedback" v-if="!errors.name">
                          <span v-if="v$.name.required.$invalid"
                            >{{ $t("global.NameIsRequired") }}<br />
                          </span>
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
                        <label for="validationCustom02">{{
                          $t("global.Email")
                        }}</label>
                        <input
                          type="email"
                          class="form-control"
                          v-model.trim="v$.email.$model"
                          id="validationCustom04"
                          :class="{
                            'is-invalid': v$.email.$error || errors.email,
                            'is-valid': !v$.email.$invalid && !errors.email,
                          }"
                          :placeholder="$t('global.Email')"
                        />
                        <div class="valid-feedback" v-if="!errors.email">
                          {{ $t("global.LooksGood") }}
                        </div>
                        <div class="invalid-feedback" v-if="errors.email">{{errors.email}}</div>

                        <div class="invalid-feedback" v-if="!errors.email">
                          <span v-if="v$.email.required.$invalid"
                            >{{ $t("global.EmailIsRequired") }} <br
                          /></span>
                          <span v-if="v$.email.email.$invalid"
                            >{{ $t("global.ThisFieldMastBeEmail") }} <br
                          /></span>
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="validationCustom02">{{
                          $t("global.Phone")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model.trim="v$.phone.$model"
                          id="validationCustom03"
                          :class="{
                            'is-invalid': v$.phone.$error || errors.phone,
                            'is-valid': !v$.phone.$invalid && !errors.phone,
                          }"
                          :placeholder="$t('global.Phone')"
                        />
                        <div class="valid-feedback" v-if="!errors.phone">
                          {{ $t("global.LooksGood") }}
                        </div>
                        <div class="invalid-feedback" v-if="errors.phone">{{errors.phone}}</div>

                        <div class="invalid-feedback"  v-if="!errors.phone">
                          <span v-if="v$.phone.required.$invalid"
                            >{{ $t("global.PhoneIsRequired") }} <br
                          /></span>
                          <span v-if="v$.phone.minLength.$invalid"
                            >{{ $t("global.PhoneIsMustHaveAtLeast") }}
                            {{ v$.phone.minLength.$params.min }}
                            {{ $t("global.Letters") }} <br
                          /></span>
                          <span v-if="v$.phone.maxLength.$invalid"
                            >{{ $t("global.PhoneIsMustHaveAtMost") }}
                            {{ v$.phone.maxLength.$params.max }}
                            {{ $t("global.Letters") }}
                          </span>
                        </div>
                      </div>


                      <div class="col-md-6 mb-3">
                        <label for="validationCustom02">{{
                          $t("global.Password")
                        }}</label>
                        <input
                          type="password"
                          v-model.trim="v$.password.$model"
                          id="validationCustom09"
                          :class="[
                            'form-control',
                            {
                              'is-invalid': v$.password.$error || errors.password,
                              'is-valid': !v$.password.$invalid && !errors.password,
                            },
                          ]"
                          :placeholder="$t('global.Password')"
                        />
                        <div class="valid-feedback" v-if="!errors.password">
                          {{ $t("global.LooksGood") }}
                        </div>
                        <div class="invalid-feedback" v-if="errors.password">{{errors.password}}</div>

                        <div class="invalid-feedback" v-if="!errors.password">

                          <span v-if="v$.password.alphaNum.$invalid"
                            >{{ $t("global.MustBeLettersOrNumbers") }} <br
                          /></span>
                          <span v-if="v$.password.minLength.$invalid"
                            >{{ $t("global.PasswordIsMustHaveAtLeast") }}
                            {{ v$.password.minLength.$params.min }}
                            {{ $t("global.Letters") }} <br
                          /></span>
                          <span v-if="v$.password.maxLength.$invalid"
                            >{{ $t("global.PasswordIsMustHaveAtMost") }}
                            {{ v$.password.maxLength.$params.max }}
                            {{ $t("global.Letters") }}
                          </span>
                        </div>
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="validationCustom02">{{
                          $t("global.confirm")
                        }}</label>
                        <input
                          type="password"
                          v-model.trim="v$.confirmtion.$model"
                          id="validationCustom10"
                          :class="[
                            'form-control',
                            {
                              'is-invalid': v$.confirmtion.$error || errors.confirmtion,
                              'is-valid': !v$.confirmtion.$invalid && !errors.confirmtion,
                            },
                          ]"
                          :placeholder="$t('global.confirm')"
                        />
                        <div class="valid-feedback" v-if="!errors.confirmation">
                          {{ $t("global.LooksGood") }}
                        </div>
                        <div class="invalid-feedback" v-if="errors.confirmation">{{errors.confirmation}}</div>

                        <div class="invalid-feedback" v-if="!errors.confirmation">

                          <span v-if="v$.confirmtion.sameAs.$invalid"
                            >{{ $t("global.ConfirmationMustMatchPassword") }}
                            <br
                          /></span>
                        </div>
                      </div>

                      <div class="col-md-12 mb-12 row flex-fill">
                        <div class="btn btn-outline-primary waves-effect">
                          <span>
                            {{ $t("global.ChooseImage") }}
                            <i
                              class="fas fa-cloud-upload-alt ml-3"
                              aria-hidden="true"
                            ></i>
                          </span>
                          <input
                            name="mediaPackage"
                            type="file"
                            @change="preview"
                            id="mediaPackage"
                            accept="image/png,jepg,jpg"
                          />
                        </div>
                        <div class="invalid-feedback" v-if="errors.file">{{errors.file}}</div>

                        <p class="num-of-files">
                          {{
                            numberOfImage
                              ? numberOfImage + $t("global.FilesSelected")
                              : $t("global.NoFilesChosen")
                          }}
                        </p>
                        <div
                          class="container-images"
                          v-show="numberOfImage"
                          id="container-images"
                        ></div>
                        <div class="container-images" v-show="!numberOfImage">
                          <figure>
                            <figcaption>
                              <img
                                :src="`/upload/user/${image}`"
                              />
                            </figcaption>
                          </figure>
                        </div>
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
import { computed, onBeforeMount, reactive, toRefs, inject, ref } from "vue";
import useVuelidate from "@vuelidate/core";
import {useStore} from "vuex";
import {
  required,
  minLength,
  maxLength,
  email,
  alphaNum,
  sameAs,
} from "@vuelidate/validators";
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import { useI18n } from "vue-i18n";

export default {
  name: "editEmployee",
  data() {
    return {
      errors: {},
    };
  },
  setup() {
    const emitter = inject("emitter");
    const store = useStore();

    const user = computed(() => {
            return store.getters['authAdmin/user'];
        });

    const id = user.value.id;
    const { t } = useI18n({});
    let user_id = ref("");
    let loading = ref(false);

    const numberOfImage = ref(0);
    const image = ref("");
    const imageUpload = ref({});
    let preview = (e) => {
      let containerImages = document.querySelector("#container-images");
      if (numberOfImage.value) {
        containerImages.innerHTML = "";
      }

      addEmployee.data.file = {};
      numberOfImage.value = e.target.files.length;

      addEmployee.data.file = e.target.files[0];

      let reader = new FileReader();
      let figure = document.createElement("figure");
      let figcap = document.createElement("figcaption");

      figcap.innerText = addEmployee.data.file.name;
      figure.appendChild(figcap);

      reader.onload = () => {
        let img = document.createElement("img");
        img.setAttribute("src", reader.result);
        figure.insertBefore(img, figcap);
      };

      containerImages.appendChild(figure);
      reader.readAsDataURL(addEmployee.data.file);
    };

    let getMainEmployeeViews = () => {
      loading.value = true;

      adminApi
        .get(`/v1/dashboard/profile/${id}`)
        .then((res) => {
          let l = res.data.data;
          user_id.value = l.user.id;
          addEmployee.data.name = l.user.name;
          addEmployee.data.email = l.user.email;
          addEmployee.data.phone = l.user.phone;
          image.value = l.media;
        })
        .catch((err) => {
          console.log(err.response);
        })
        .finally(() => {
          loading.value = false;
        });
    };

    onBeforeMount(() => {
      getMainEmployeeViews();
    });

    let addEmployee = reactive({
      data: {
        password: "",
        confirmtion: "",
        name: "",
        email: "",
        phone: "",
        file: {},
      },
    });

    const rules = computed(() => {
      return {
        name: {
          minLength: minLength(3),
          maxLength: maxLength(100),
          required,
        },
        email: {
          email,
          required,
        },
        password: {

          minLength: minLength(8),
          maxLength: maxLength(16),
          alphaNum,
        },
        confirmtion: {
          sameAs: sameAs(addEmployee.data.password),
        },
        phone: {
          required,
          minLength: minLength(8),
          maxLength: maxLength(20),
        },

      };
    });

    const v$ = useVuelidate(rules, addEmployee.data);

    return {
      t,
      user,
      user_id,
      preview,
      imageUpload,
      image,
      numberOfImage,
      loading,
      ...toRefs(addEmployee),
      v$,
    };
  },
  methods: {
    editEmployee() {
      this.v$.$validate();

      if (!this.v$.$error) {
        this.loading = true;
        this.errors = {};
        let formData = new FormData();
        formData.append("name", this.data.name);
        formData.append("email", this.data.email);
        formData.append("phone", this.data.phone);
        formData.append("password", this.data.password);
        formData.append("confirmtion", this.data.confirmtion);
        formData.append("file", this.data.file);
        formData.append("_method", "PUT");
        console.log(this.user.id)
        adminApi
          .post(`/v1/dashboard/profile/${this.user.id}`, formData)
          .then((res) => {
            this.$store.dispatch('editUser',res.data.data.user)
            notify({
              title: `${this.t(
                "global.EditSuccessfully"
              )} <i class="fas fa-check-circle"></i>`,
              type: "success",
              duration: 5000,
              speed: 2000,
            });
          })
          .catch((e) => {
            if (e.response.data.errors) {
                for(const el in e.response.data.errors){
                    this.errors[el]=e.response.data.errors[el][0];
                }
            }else {
                notify({
                    title: t('global.Unknown error')+` <i class="fas fa-check-circle"></i>`,
                    type: "error",
                    duration: 5000,
                    speed: 2000
                });
            }
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
.sec-body {
  border: 3px solid #fcb00c;
  border-radius: 20px;
  padding: 10px;
}
.sec-head {
  background-color: #fcb00c;
  color: #000;
  border-radius: 11px;
  padding: 5px;
  text-align: center;
  margin-bottom: 8px;
  margin-top: 10px;
}
.sec-body:hover .sec-head {
  border: 3px solid #fcb00c;
  padding: 2px;
  border-radius: 11px;
  background-color: #ffff;
}
.sec-head h3 {
  font-weight: 700;
}
</style>
