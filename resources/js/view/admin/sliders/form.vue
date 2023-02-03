<template>
    <div class="slider-form">
        <notifications :position="locale == 'ar' ? 'top left' : 'top right'" />
        <div class="modal fade" id="sliderFormModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form @submit.prevent="save" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="btn-close" aria-label="Close" data-dismiss="modal"></button>
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
                                            <label @click="deleteImage" v-if="uploadedImage"
                                                class="icon text-secondary px-2">
                                                <i class="fa fa-window-close" aria-hidden="true"></i>
                                            </label>
                                            <input @change="uploadImage"
                                                accept="image/apng, image/avif, image/gif, image/jpeg, image/png, image/svg+xml, image/webp"
                                                type="file" id="image" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="head">{{ $t("global.Color") }}</label>
                                                <br />
                                                <input type="color" v-model="v$.color.$model" />
                                                <div class="invalid-feedback">
                                                    <div v-for="error in v$.color.$errors" :key="error">
                                                        {{ $t("global.Model") + " " + $t(error.$validator) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">{{ $t("global.Title") }}</label>
                                                <input type="text" class="form-control" v-model="v$.title.$model"
                                                    :class="{
                                                        'is-invalid': v$.title.$error,
                                                    }" />
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
                                                <input type="text" class="form-control" v-model="v$.url.$model" :class="{
                                                    'is-invalid': v$.url.$error,
                                                }" />
                                                <div class="invalid-feedback">
                                                    <div v-for="error in v$.url.$errors" :key="error">
                                                        {{ $t("global.Url") + " " + $t(error.$validator) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="!external" class="form-group">
                                                <label for="sel1">{{ $t("global.Products") }}</label>

                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <span v-if="product_id">
                                                            <img :src="selected_product_image ? '/upload/product/' + selected_product_image : '/admin/img/Logo Dashboard.png'"
                                                                alt="product-image" style="
                                                                width: 50px;
                                                                height: 50px;
                                                                border-radius: 50%;
                                                            " />
                                                            {{ selected_product_name }}</span>
                                                        <span v-else>{{
                                                            $t("global.Product")
                                                        }}</span>
                                                    </button>
                                                    <div :class="[
                                                        'dropdown-menu bg-white',
                                                        this.$i18n.locale == 'en' ? 'drop_ltr' : '',
                                                                            ]" style="
                                                            height: 400px;
                                                            overflow-y: scroll;
                                                            z-index: 999999;
                                                        " aria-labelledby="dropdownMenuButton">
                                                        <input type="text" :placeholder="$t('global.Search')"
                                                            v-model="product_search"
                                                            :class="['form-control  w-100']"
                                                            onchange="event.stopPropagation()" />
                                                        <!-- <button class="btn btn-danger mx-4" v-if="product_id"
                                                            @click="assignRepresentativeToOrder(item.id, 0, 'cancel')">{{
                                                                $t('global.Cancel')
                                                            }}</button> -->
                                                        <loader v-if="loading2" />

                                                        <div v-for="product in products" :key="product.id" :class="[
                                                            'dropdown-item px-2 d-flex justify-content-between',
                                                            product.id == product_id
                                                                ? 'bg-secondary'
                                                                : '',
                                                        ]" @click="select_product(product.id,product.nameAr,product.image)">
                                                            <img :src="product.image ? '/upload/product/' + product.image : '/admin/img/Logo Dashboard.png'"
                                                                alt="product-image" style="width: 50px; height: 50px" />
                                                            <span style="
                                                                overflow: hidden;
                                                                height: 34px;
                                                                font-size: 24px;
                                                                word-break: break-word;
                                                            ">{{ product.nameAr }}</span>
                                                        </div>

                                                        <h5 v-if="
                                                            Object.keys(products ?? []).length ==
                                                            0
                                                        " class="text-center">
                                                            {{ $t("global.No Data Found") }}
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox mr-sm-2">
                                                    <input id="workflow" name="workflow" class="custom-control-input"
                                                        type="checkbox" v-model="external" />
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
                            <button id="close-button" type="button" class="btn btn-secondary" data-dismiss="modal">
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
import sliderClient from "../../../http-clients/slider-client";
import { inject, reactive, toRefs, watch , ref, onMounted} from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
import adminApi from '../../../api/adminAxios';
export default {
    setup(props, context) {
        const product_search = ref('')
        const products = ref({})
        const debounce = ref({})
        const loading2 = ref(false)
        const selected_product_image = ref('')
        const selected_product_name = ref('')
        const { t, locale } = useI18n({ useScope: "global" });
        const slider_store = inject("slider_store");
        const data = reactive({
            uploadedImage: null,
            previewImage: "",
        });
        const form = reactive({
            title: "",
            url: "",
            external: true,
            product_id: null,
            color: "#fff",
        });
        const rules = {
            title: { required },
            color: { required },
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

        onMounted(() => {
            getProducts()
        })
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
            data.previewImage = props.selectedSlider
                ? `/upload/${props.selectedSlider.image}`
                : "";
        }

        function save() {
            if (v$.value.$invalid) {
                v$.value.$touch();
                return;
            }
            if (!props.selectedSlider) {
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
            props.allProducts.forEach(function (_product) {
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
            sliderClient
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
            sliderClient
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
            if (props.selectedSlider) {
                formData.append("id", props.selectedSlider.id);
            }
            formData.append("title", form.title);
            formData.append("color", form.color);
            if (form.url) formData.append("url", form.url);
            if (form.product_id) formData.append("product_id", form.product_id);
            formData.append("external", form.external);
            let product = getProduct();
            formData.append("product_name_ar", product ? product.nameAr : "");
            formData.append("product_name_en", product ? product.nameEn : "");
            if (data.uploadedImage) {
                formData.append("image", data.uploadedImage);
            }
            return formData;
        }
        function setForm() {
            v$.value.$reset();
            form.product_id = getProductId();
            form.title = props.selectedSlider ? props.selectedSlider.title : "";
            form.color = props.selectedSlider ? props.selectedSlider.color : "#fff";
            form.url = props.selectedSlider ? props.selectedSlider.url : "";
            form.external =
                props.selectedSlider && props.selectedSlider.external ? true : false;
            data.previewImage = props.selectedSlider
                ? `/upload/${props.selectedSlider.image}`
                : "";
            data.uploadedImage = null;
        }
        function getProductId() {
            let firstProductId = props.allProducts.length > 0 ? props.allProducts[0].id : null;
            return props.selectedSlider ? props.selectedSlider.product_id : firstProductId;
        }
        watch(product_search, () => {
            clearTimeout(debounce.value);
            debounce.value = setTimeout(() => {
                getProducts();
            }, 500);
        });
        const getProducts = async () => {
            loading2.value = true;
            adminApi
            .get(
            `/v1/dashboard/getAlternativesProducts?altr_search=${product_search.value}`
            )
            .then((res) => {
                products.value = res.data.alternatives;
            })
            .finally(() => {
            loading2.value = false;
            });

        }

        const select_product  = (productId,name,image) => {
            selected_product_image.value = image
            selected_product_name.value = name
            form.product_id = productId
        }
        //Watchers
        watch(
            () => {
                slider_store.onFormShow;
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
            select_product,
            selected_product_image,
            selected_product_name,
            product_search,
            locale,
            getProducts,
            products,
            loading2,
        };
    },
    props: ["selectedSlider",'allProducts'],
};
</script>

<style scoped lang="scss">
.slider-form {
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
