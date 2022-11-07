<template>
    <div :class="[
      'page-wrapper',
      this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
    ]">
        <div class="content container-fluid">
            <notifications :position="this.$i18n.locale == 'ar' ? 'top left' : 'top right'" />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.Ad Owners") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{ name: 'indexAdOwner' }">
                                    {{ $t("global.Ad Owners") }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">
                                {{ $t("adOwner.CreateAdOwner") }}
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
                                <router-link :to="{ name: 'indexAdOwner' }" class="btn btn-custom btn-dark">
                                    {{ $t("global.back") }}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['name']">
                                        {{ t("global.Exist", {field:t("global.Name")}) }}<br />
                                    </div>
                                    <form @submit.prevent="storeAdOwner" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Start Name-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.Name") }}
                                                </label>
                                                <input type="text" class="form-control" v-model.trim="v$.name.$model"
                                                    id="validationCustom01" :placeholder="$t('global.Name')" :class="{
                                                      'is-invalid': v$.name.$error || data.nameExist,
                                                      'is-valid': !v$.name.$invalid,
                                                    }" />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.name.maxLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtLeast") }}
                                                        {{ v$.name.minLength.$params.min }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.name.minLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtMost") }}
                                                        {{ v$.name.maxLength.$params.max }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="!v$.name.$invalid && data.nameExist">
                                                        {{ $t("global.NameIsExist") }}
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Name-->

                                            <!--Start Email-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.Email") }}
                                                </label>
                                                <input type="email" class="form-control" v-model.trim="v$.email.$model"
                                                    id="validationCustom01" :placeholder="$t('global.Email')" :class="{
                                                      'is-invalid': v$.email.$error || data.nameExist,
                                                      'is-valid': !v$.email.$invalid,
                                                    }" />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.email.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="!v$.email.$invalid && data.nameExist">
                                                        {{ $t("global.NameIsExist") }}
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Email-->

                                            <!--Start Password-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.Password") }}
                                                </label>
                                                <input type="password" class="form-control" v-model.trim="v$.password.$model"
                                                    id="validationCustom01" :placeholder="$t('global.Password')" :class="{
                                                      'is-invalid': v$.password.$error,
                                                      'is-valid': !v$.password.$invalid,
                                                    }" />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.password.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.password.minLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtLeast") }}
                                                        {{ v$.password.minLength.$params.min }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Password-->

                                            <!--Start Phone-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">
                                                    {{ $t("global.Phone") }}
                                                </label>
                                                <input
                                                type="number"
                                                class="form-control"
                                                v-model.trim="v$.phone.$model"
                                                id="validationCustom05"
                                                :placeholder="$t('global.Phone')"
                                                :class="{
                                                    'is-invalid': v$.phone.$error,
                                                    'is-valid': !v$.phone.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.phone.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                    <span v-if="v$.phone.minLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtLeast") }}
                                                        {{ v$.phone.minLength.$params.min }}
                                                        {{ $t("global.Letters") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Phone-->

                                            <!--Start CommercialRecord-->
                                            <div class="col-md-12 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        {{ $t("global.CommercialRecord") }}
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input name="mediaPackage" type="file" @change="preview"
                                                        id="mediaPackage" accept="image/png,jepg,jpg" />
                                                </div>
                                                <span class="text-danger text-center">{{ $t("global.ImageValidation") }}</span>
                                                <p class="num-of-files">
                                                    {{ numberOfImage ? numberOfImage + " Files Selected" : "No Files Chosen" }}
                                                </p>
                                                <div class="container-images" id="container-images" v-show="data.file && numberOfImage"></div>
                                                <div class="container-images" v-show="!numberOfImage">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/admin/img/company/img-1.png`" />
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <!--End CommercialRecord-->

                                            <!--Start Tax Card-->
                                            <!-- <div class="col-md-12 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        {{ $t("global.ChooseImage") }}
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input name="mediaPackage2" type="file" @change="preview2"
                                                        id="mediaPackage2" accept="image/png,jepg,jpg" />
                                                </div>
                                                <span class="text-danger text-center">{{ $t("global.ImageValidation") }}</span>
                                                <p class="num-of-files2">
                                                    {{ numberOfImage2 ? numberOfImage2 + " Files Selected" : "No Files Chosen" }}
                                                </p>
                                                <div class="container-images2" id="container-images2" v-show="data.file2 && numberOfImage2"></div>
                                                <div class="container-images2" v-show="!numberOfImage2">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/admin/img/company/img-1.png`" />
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div> -->
                                            <!--End Tax Card-->
                                            <!--Start Multiple Images-->
                                            <!-- <div class="col-md-9 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        {{ $t("global.ChooseImage") }}
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input
                                                        name="mediaPackage[]"
                                                        type="file"
                                                        multiple
                                                        @change="preview2"
                                                        id="mediaPackage1"
                                                        accept="image/png,jepg,jpg"
                                                    >
                                                </div>
                                                <span class="text-danger text-center">{{ $t("global.ImageValidation") }}</span>
                                                <p class="num-of-files">{{numberOfImage1 ? numberOfImage1 + ' Files Selected' : 'No Files Chosen' }}</p>
                                                <div class="container-images" id="container-images1" v-show="data.files && numberOfImage1"></div>
                                                <div class="container-images" v-show="!numberOfImage1">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/admin/img/company/img-1.png`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div> -->
                                            <!--End Multiple Images-->
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
import { computed, onMounted, reactive, toRefs, inject, ref } from "vue";
import useVuelidate from "@vuelidate/core";
import { required, minLength, between, maxLength, alpha,integer } from "@vuelidate/validators";
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import { useI18n } from "vue-i18n";

export default {
    name: "createAdOwner",
    data() {
        return {
            errors: {},
        };
    },
    setup() {
        const emitter = inject("emitter");
        const { t } = useI18n({});
        let loading = ref(false);

        //start design
        let addAdOwner = reactive({
            data: {
                name: "",
                email: "",
                password: "",
                phone: "",
                file: {},
                //
                // files: {},
                //
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
                email: {
                    required,
                },
                password: {
                    required,
                    minLength: minLength(8),
                },
                phone:{
                    minLength: minLength(10),
                    required,
                },
                file: {
                    //required,
                },
                //
                // files: {
                //     //required,
                // },
                //
            };
        });

        const v$ = useVuelidate(rules, addAdOwner.data);

        let preview = (e) => {
            let containerImages = document.querySelector("#container-images");
            if (numberOfImage.value) {
                containerImages.innerHTML = "";
            }
            addAdOwner.data.file = {};

            numberOfImage.value = e.target.files.length;

            addAdOwner.data.file = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement("figure");
            let figcap = document.createElement("figcaption");

            figcap.innerText = addAdOwner.data.file.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement("img");
                img.setAttribute("src", reader.result);
                figure.insertBefore(img, figcap);
            };

            containerImages.appendChild(figure);
            reader.readAsDataURL(addAdOwner.data.file);
        };

        //
        // let preview2 = (e) => {

        //     let containerImages = document.querySelector('#container-images1');
        //     if(numberOfImage1.value)
        //     {
        //         containerImages.innerHTML = '';
        //     }

        //     addProduct.data.files = [];
        //     numberOfImage1.value = e.target.files.length;

        //     for(let file of e.target.files)
        //     {
        //         addProduct.data.files.push(filess);
        //         let reader = new FileReader();
        //         let figure = document.createElement('figure');
        //         let figcap = document.createElement('figcaption');
        //         figcap.innerText = file.name;
        //         figure.appendChild(figcap);
        //         reader.onload = () => {
        //             let img = document.createElement('img');
        //             img.setAttribute('src',reader.result);
        //             figure.insertBefore(img,figcap);
        //         }
        //         containerImages.appendChild(figure);
        //         reader.readAsDataURL(file);
        //     }
        // };
        // const numberOfImage1 = ref(0);
        //

        const numberOfImage = ref(0);


    return { loading, ...toRefs(addAdOwner), v$, preview, numberOfImage/*, preview2, numberOfImage1*/ };

    },
    methods: {
        storeAdOwner() {
            this.v$.$validate();

            if (!this.v$.$error) {
                this.loading = true;
                this.errors = {};
                let formData = new FormData();
                formData.append("name", this.data.name);
                formData.append("email", this.data.email);
                formData.append("password", this.data.password);
                formData.append("phone", this.data.phone);
                formData.append("file", this.data.file);
                //
                for( var i = 0; i < this.numberOfImage1; i++ ){
                    let file = this.data.files[i];
                    formData.append('files[' + i + ']', file);
                }
                //

                adminApi
                    .post(`/v1/dashboard/adOwner`, formData)
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
                        this.errors = err.response.data.errors;
                        console.log(err.response);
                        Swal.fire({
                            icon: 'error',
                            title: 'يوجد خطأ...',
                            text: 'يوجد خطأ ما..!!',
                        });
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        },
        resetForm() {
            document.querySelector('#container-images').innerHTML = '';
            this.numberOfImage = 0;
            this.data.name = "";
            this.data.email = "";
            this.data.password = "";
            this.data.phone = "";
            this.data.file = {};
            //
            document.querySelector('#container-images1').innerHTML = '';
            this.numberOfImage1 = 0;
            this.data.files = [];
            //
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
