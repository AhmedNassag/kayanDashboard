<template>
  <div class="about-section-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="aboutSectionFormModal"
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
                <div
                  v-if="AboutInformationsIndex?.id != 1"
                  :class="{
                    'col-lg-4': AboutInformationsIndex?.id != 1,
                    'col-lg-0': AboutInformationsIndex?.id == 1,
                  }"
                  class="mb-3"
                >
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
                <div
                  :class="{
                    'col-lg-8': AboutInformationsIndex?.name != 'TOP_SECTION',
                    'col-lg-12': AboutInformationsIndex?.name == 'TOP_SECTION',
                  }"
                >
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
                    <div class="col-lg-12" v-if="AboutInformationsIndex?.id != 1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{
                          $t("global.SubHeader")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="sub_header"

                        />

                      </div>
                    </div>
                    <div class="col-lg-12" v-if="AboutInformationsIndex?.id != 1">
                      <div class="form-group">
                        <label for="exampleInputEmail21">{{
                          $t("global.Text")
                        }}</label>
                        <input
                            id="exampleInputEmail21"
                          type="text"
                          class="form-control"
                          v-model="text"

                        />

                      </div>
                    </div>
                    <div class="col-lg-12" v-if="AboutInformationsIndex?.id != 1">
                      <div class="form-group">
                        <label for="exampleInputEmail26">{{
                          $t("global.first_feature")
                        }}</label>
                        <input
                            id="exampleInputEmail26"
                          type="text"
                          class="form-control"
                          v-model="first_feature"

                        />

                      </div>
                    </div>
                    <div class="col-lg-12" v-if="AboutInformationsIndex?.id != 1">
                      <div class="form-group">
                        <label for="second_feature">{{
                          $t("global.second_feature")
                        }}</label>
                        <input
                            id="second_feature"
                          type="text"
                          class="form-control"
                          v-model="second_feature"

                        />

                      </div>
                    </div>
                    <div class="col-lg-12" v-if="AboutInformationsIndex?.id != 1">
                      <div class="form-group">
                        <label for="third_feature">{{
                          $t("global.third_feature")
                        }}</label>
                        <input
                            id="third_feature"
                          type="text"
                          class="form-control"
                          v-model="third_feature"

                        />

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
import aboutSection from "../../../http-clients/about-informations-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
import useVuelidate from "@vuelidate/core";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const about_section_store = inject("about_section_store");
    const data = reactive({
      uploadedImage: null,
      previewImage: "",
    });
    const form = reactive({
      id: null,
      header: null,
      sub_header: null,
      text: null,
      first_feature: null,
      second_feature: null,
      third_feature: null,
    });
    const rules = {
      header: { required },
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
        props.AboutInformationsIndex && props.AboutInformationsIndex.image
          ? `/upload/${props.AboutInformationsIndex.image}`
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
      aboutSection
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
      formData.append("id", props.AboutInformationsIndex.id);
      formData.append("header", form.header);
      formData.append("sub_header", form.sub_header);
      formData.append("text", form.text);
      formData.append("first_feature", form.first_feature);
      formData.append("second_feature", form.second_feature);
      formData.append("third_feature", form.third_feature);
      if (data.uploadedImage) formData.append("image", data.uploadedImage);
      return formData;
    }

    function setForm() {
      v$.value.$reset();
      data.uploadedImage = null;
      form.id = props.AboutInformationsIndex.id;
      data.previewImage =
        props.AboutInformationsIndex && props.AboutInformationsIndex.image
          ? `/upload/${props.AboutInformationsIndex.image}`
          : null;
      form.header = props.AboutInformationsIndex.header;
      form.sub_header = props.AboutInformationsIndex.sub_header;
      form.text = props.AboutInformationsIndex.text;
      form.first_feature = props.AboutInformationsIndex.first_feature;
      form.second_feature = props.AboutInformationsIndex.second_feature;
      form.third_feature = props.AboutInformationsIndex.third_feature;
    }
    //Watchers
    watch(
      () => {
        about_section_store.onFormShow;
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
  props: ["AboutInformationsIndex"],
};
</script>

<style scoped lang="scss">
.about-section-form {
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
