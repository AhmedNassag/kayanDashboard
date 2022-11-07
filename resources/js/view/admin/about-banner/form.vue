<template>
  <div class="about-banner-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="aboutBannerFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <button
                type="button"
                class="btn-close"
                aria-label="Close"
                data-dismiss="modal"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-4 mb-3">
                  <div class="image">
                    <img v-if="previewImage" class="border-bottom" :src="previewImage" />
                    <img v-else class="border-bottom" src="/images/empty.jpg" />
                    <div class="image-upload">
                      <label class="icon" for="image">
                        <i class="fa fa-camera"></i>
                      </label>
                      <label
                        @click="deleteImage"
                        v-if="uploadedImage"
                        class="icon text-secondary px-2"
                      >
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                      </label>
                      <input
                        @change="uploadImage"
                        accept="image/apng, image/avif, image/gif, image/jpeg, image/png, image/svg+xml, image/webp"
                        type="file"
                        id="image"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-lg-8">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{ $t("global.Header") }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.header.$model"
                          :class="{
                            'is-invalid': v$.header.$error,
                          }"
                          rows="5"
                        />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.header.$errors" :key="error">
                            {{ $t("global.Header") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div
                      v-if="selectedAboutBanner?.name == 'THIRD_BANNER'"
                      class="col-lg-12"
                    >
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{
                          $t("global.SubHeader")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.sub_header.$model"
                          :class="{
                            'is-invalid': v$.sub_header.$error,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.sub_header.$errors" :key="error">
                            {{ $t("global.SubHeader") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{
                          $t("global.ButtonLabel")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.button_label.$model"
                          :class="{
                            'is-invalid': v$.button_label.$error,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.button_label.$errors" :key="error">
                            {{ $t("global.ButtonLabel") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{ $t("global.Url") }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.url.$model"
                          :class="{
                            'is-invalid': v$.url.$error,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.url.$errors" :key="error">
                            {{ $t("global.Url") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div
                      v-if="
                        selectedAboutBanner?.name == 'FIRST_BANNER' ||
                        selectedAboutBanner?.name == 'THIRD_BANNER'
                      "
                      class="col-lg-12"
                    >
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{
                          $t("global.FirstText")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.first_text.$model"
                          :class="{
                            'is-invalid': v$.first_text.$error,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.first_text.$errors" :key="error">
                            {{ $t("global.FirstText") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div
                      v-if="
                        selectedAboutBanner?.name == 'FIRST_BANNER' ||
                        selectedAboutBanner?.name == 'THIRD_BANNER'
                      "
                      class="col-lg-12"
                    >
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{
                          $t("global.SecondText")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.second_text.$model"
                          :class="{
                            'is-invalid': v$.second_text.$error,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.second_text.$errors" :key="error">
                            {{ $t("global.SecondText") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div
                      v-if="selectedAboutBanner?.name == 'SECOND_BANNER'"
                      class="col-lg-12"
                    >
                      <div class="form-group">
                        <label for="head">{{ $t("global.Text") }}</label>
                        <br />
                        <input
                          type="text"
                          class="form-control"
                          :class="{
                            'is-invalid': v$.text.$error,
                          }"
                          v-model="v$.text.$model"
                        />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.text.$errors" :key="error">
                            {{ $t("global.Text") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div
                      v-if="
                        selectedAboutBanner?.name == 'FIRST_BANNER' ||
                        selectedAboutBanner?.name == 'SECOND_BANNER'
                      "
                      class="col-lg-12"
                    >
                      <div class="form-group">
                        <label for="head">{{ $t("global.Color") }}</label>
                        <br />
                        <input
                          :class="{
                            'is-invalid': v$.color.$error,
                          }"
                          type="color"
                          v-model="v$.color.$model"
                        />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.color.$errors" :key="error">
                            {{ $t("global.Color") + " " + $t(error.$validator) }}
                          </div>
                        </div>
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
              <button
                id="close-button"
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
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
import { required } from "@vuelidate/validators";
import aboutBannerClient from "../../../http-clients/about-banner-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
import useVuelidate from "@vuelidate/core";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const about_banner_store = inject("about_banner_store");
    const data = reactive({
      uploadedImage: null,
      previewImage: "",
    });
    const form = reactive({
      id: null,
      header: null,
      url: null,
      button_label: null,
      sub_header: null,
      first_text: null,
      second_text: null,
      color: null,
      text: null,
    });
    const rules = {
      header: { required },
      url: { required },
      button_label: { required },
      sub_header: {
        required(value) {
          if (props.selectedAboutBanner.name == "THIRD_BANNER") {
            return value;
          }
          return true;
        },
      },
      first_text: {
        required(value) {
          if (
            props.selectedAboutBanner.name == "FIRST_BANNER" ||
            props.selectedAboutBanner.name == "THIRD_BANNER"
          ) {
            return value;
          }
          return true;
        },
      },
      second_text: {
        required(value) {
          if (
            props.selectedAboutBanner.name == "FIRST_BANNER" ||
            props.selectedAboutBanner.name == "THIRD_BANNER"
          ) {
            return value;
          }
          return true;
        },
      },
      color: {
        required(value) {
          if (
            props.selectedAboutBanner.name == "FIRST_BANNER" ||
            props.selectedAboutBanner.name == "SECOND_BANNER"
          ) {
            return value;
          }
          return true;
        },
      },
      text: {
        required(value) {
          if (props.selectedAboutBanner.name == "SECOND_BANNER") {
            return value;
          }
          return true;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    //Methods
    function uploadImage(e) {
      const image = e.target.files[0];
      data.uploadedImage = image;
      const reader = new FileReader();
      reader.readAsDataURL(image);
      reader.onload = (e) => {
        data.previewImage = e.target.result;
      };
    }

    function deleteImage() {
      data.uploadedImage = null;
      data.previewImage =
        props.selectedAboutBanner && props.selectedAboutBanner.image
          ? `/upload/${props.selectedAboutBanner.image}`
          : "";
    }

    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      update();
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
    function update() {
      context.emit("loading", true);
      aboutBannerClient
        .update(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("updated", response.data);
          $("#close-button").click();
          alertMessage(
            `${t("global.UpdatedSuccessfully")} <i class="fas fa-check-circle"></i>`,
            "success"
          );
        })
        .catch((error) => {
          context.emit("loading", false);
        });
    }

    function getForm() {
      let formData = new FormData();
      formData.append("id", props.selectedAboutBanner.id);
      formData.append("header", form.header);
      formData.append("url", form.url);
      formData.append("button_label", form.button_label);
      if (data.uploadedImage) formData.append("image", data.uploadedImage);
      if (form.sub_header) formData.append("sub_header", form.sub_header);
      if (form.first_text) formData.append("first_text", form.first_text);
      if (form.second_text) formData.append("second_text", form.second_text);
      if (form.color) formData.append("color", form.color);
      if (form.text) formData.append("text", form.text);
      return formData;
    }

    function setForm() {
      v$.value.$reset();
      data.uploadedImage = null;
      form.id = props.selectedAboutBanner.id;
      data.previewImage =
        props.selectedAboutBanner && props.selectedAboutBanner.image
          ? `/upload/${props.selectedAboutBanner.image}`
          : null;
      form.header = props.selectedAboutBanner.header;
      form.url = props.selectedAboutBanner.url;
      form.button_label = props.selectedAboutBanner.button_label;
      form.sub_header = props.selectedAboutBanner.sub_header;
      form.first_text = props.selectedAboutBanner.first_text;
      form.second_text = props.selectedAboutBanner.second_text;
      form.second_text = props.selectedAboutBanner.second_text;
      form.color = props.selectedAboutBanner.color;
      form.text = props.selectedAboutBanner.text;
    }
    //Watchers
    watch(
      () => {
        about_banner_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(data),
      ...toRefs(form),
      v$,
      uploadImage,
      deleteImage,
      save,
      locale,
    };
  },
  props: ["selectedAboutBanner"],
};
</script>

<style scoped lang="scss">
.about-banner-form {
  .form-control {
    padding: 10px;
  }
  .form-group {
    margin-bottom: 10px;
    label {
      margin-bottom: 5px;
    }
  }
  .icons {
    i {
      font-size: 20px;
    }
    i:hover {
      cursor: pointer;
    }
  }
  .image {
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
      rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    padding-bottom: 5px;
    img {
      width: 100%;
      height: 150px;
    }
    .image-upload {
      i {
        margin-top: 10px;
        color: #888888;
      }
      .icon {
        &:hover {
          cursor: pointer !important;
        }
        i {
          font-size: 14px;
          position: relative;
        }
      }
      text-align: center;
      input[type="file"] {
        display: none;
      }
    }
  }
  .modal-footer {
    button {
      width: 80px;
    }
  }
}
</style>
