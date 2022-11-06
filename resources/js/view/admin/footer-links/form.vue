<template>
  <div class="footer-link-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="sliderFormModal"
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
                        <label for="exampleInputEmail1">{{ $t("global.Content") }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.content.$model"
                          :class="{
                            'is-invalid': v$.content.$error,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.content.$errors" :key="error">
                            {{ $t("global.Content") + " " + $t(error.$validator) }}
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
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import footerClient from "../../../http-clients/footer-link-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const link_footer_store = inject("link_footer_store");
    const data = reactive({
      uploadedImage: null,
      previewImage: "",
    });
    const form = reactive({
      content: "",
    });
    const rules = {
      content: { required },
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
      data.previewImage = props.selectedFooterLink
        ? `/upload/footer-links/${props.selectedFooterLink.image}`
        : "";
    }

    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!data.uploadedImage) {
        alertMessage(
          `${t("global.Image")} ${t("required")} <i class="fas fa-close"></i>`,
          "error"
        );
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
      footerClient
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
      if (props.selectedFooterLink) {
        formData.append("id", props.selectedFooterLink.id);
      }
      formData.append("content", form.content);
      formData.append("image", data.uploadedImage);
      return formData;
    }

    function setForm() {
      v$.value.$reset();
      form.image = data.previewImage;
      form.content = props.selectedFooterLink.content;
    }
    //Watchers
    watch(
      () => {
        link_footer_store.onFormShow;
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
  props: ["selectedFooterLink", "products"],
};
</script>

<style scoped lang="scss">
.footer-link-form {
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
