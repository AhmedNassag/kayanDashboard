<template>
  <div class="simple-advertise-form">
    <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
    <div
      class="modal fade"
      id="simpleAdvertiseFormModal"
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
                        <label for="exampleInputEmail1">{{ $t("global.Title") }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.title.$model"
                          :class="{
                            'is-invalid': v$.title.$error,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.title.$errors" :key="error">
                            {{ $t("global.Title") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div v-if="external" class="form-group">
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
                      <div v-if="!external" class="form-group">
                        <label for="sel1">{{ $t("global.Products") }}</label>
                        <select
                          v-model="v$.product_id.$model"
                          class="custom-select"
                          id="sel1"
                        >
                          <option
                            :value="product.id"
                            v-for="product in products"
                            :key="product.id"
                          >
                            {{ product.product_name.nameAr }}
                          </option>
                        </select>
                      </div>
                      <div class="form-group">
                        <div class="custom-control custom-checkbox mr-sm-2">
                          <input
                            id="workflow"
                            name="workflow"
                            class="custom-control-input"
                            type="checkbox"
                            v-model="external"
                          />
                          <label class="custom-control-label" for="workflow">{{
                            $t("global.External")
                          }}</label>
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
import simpleAdvertiseClient from "../../../http-clients/simple-advertise-client";
import { inject, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const simple_advertise_store = inject("simple_advertise_store");
    const data = reactive({
      uploadedImage: null,
      previewImage: "",
    });
    const form = reactive({
      title: "",
      url: "",
      external: true,
      product_id: null,
    });
    const rules = {
      title: { required },
      product_id: {
        required(value) {
          return !form.external ? value : true;
        },
      },
      url: {
        required(value) {
          return form.external ? value : true;
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
      data.previewImage = props.selectedSimpleAdvertise
        ? `/upload/${props.selectedSimpleAdvertise.image}`
        : "";
    }

    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedSimpleAdvertise) {
        if (!data.uploadedImage) {
          alertMessage(
            `${t("global.Image")} ${t("required")} <i class="fas fa-close"></i>`,
            "error"
          );
          return;
        }
        store();
      } else {
        update();
      }
    }
    //Commons
    function getProduct() {
      let product = null;
      props.products.forEach(function (_product) {
        if (_product.id == form.product_id) {
          return (product = _product);
        }
      });
      return product;
    }
    function alertMessage(message, type) {
      notify({
        title: message,
        type: type,
        duration: 5000,
        speed: 2000,
      });
    }
    function store() {
      context.emit("loading", true);
      simpleAdvertiseClient
        .store(getForm())
        .then((response) => {
          context.emit("loading", false);
          context.emit("created", response.data);
          $("#close-button").click();
          alertMessage(
            `${t("global.CreatedSuccessfully")} <i class="fas fa-check-circle"></i>`,
            "success"
          );
        })
        .catch((error) => {
          context.emit("loading", false);
        });
    }
    function update() {
      context.emit("loading", true);
      simpleAdvertiseClient
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
          console.log(error.response);
          context.emit("loading", false);
        });
    }
    function getForm() {
      let formData = new FormData();
      if (props.selectedSimpleAdvertise) {
        formData.append("id", props.selectedSimpleAdvertise.id);
      }
      formData.append("title", form.title);
      formData.append("url", form.url);
      formData.append("product_id", form.product_id);
      formData.append("external", form.external);
      let product = getProduct();
      formData.append("product_name_ar", product ? product.product_name.nameAr : "");
      formData.append("product_name_en", product ? product.product_name.nameEn : "");
      if (data.uploadedImage) {
        formData.append("image", data.uploadedImage);
      }
      return formData;
    }
    function setForm() {
      v$.value.$reset();
      form.product_id = getProductId();
      form.title = props.selectedSimpleAdvertise
        ? props.selectedSimpleAdvertise.title
        : "";
      form.url = props.selectedSimpleAdvertise ? props.selectedSimpleAdvertise.url : "";
      form.external =
        props.selectedSimpleAdvertise && props.selectedSimpleAdvertise.external
          ? true
          : false;
      data.previewImage = props.selectedSimpleAdvertise
        ? `/upload/${props.selectedSimpleAdvertise.image}`
        : "";
      data.uploadedImage = null;
    }
    function getProductId() {
      let firstProductId = props.products.length > 0 ? props.products[0].id : null;
      return props.selectedSimpleAdvertise
        ? props.selectedSimpleAdvertise.product_id
        : firstProductId;
    }
    //Watchers
    watch(
      () => {
        simple_advertise_store.onFormShow;
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
      products: props.products,
    };
  },
  props: ["selectedSimpleAdvertise", "products"],
};
</script>

<style scoped lang="scss">
.simple-advertise-form {
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
