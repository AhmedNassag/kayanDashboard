<template>
    <div
      :class="[
        'page-wrapper',
        this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
      ]"
    >

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar' ? 'top left' : 'top right'"/>

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.Alternative") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexAlternative'}">{{ $t("global.Alternative") }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("alternative.EditAlternative") }}</li>
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
                                    :to="{name: 'indexAlternative'}"
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
                                        {{ t("global.Exist", {field:t("global.Name")}) }} <br />
                                    </div>
                                    <form @submit.prevent="editAlternative" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Start NameAr-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.NameAr") }}
                                                </label>
                                                <input type="text" class="form-control" v-model.trim="v$.nameAr.$model" id="validationCustom01"
                                                    :placeholder="$t('global.Name')"
                                                    :class="{ 'is-invalid': v$.nameAr.$error || data.nameExist, 'is-valid': !v$.nameAr.$invalid }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.nameAr.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.nameAr.maxLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtLeast") }}
                                                        {{ v$.nameAr.minLength.$params.min }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.nameAr.minLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtMost") }}
                                                        {{ v$.nameAr.maxLength.$params.max }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="!v$.nameAr.$invalid && data.nameExist">
                                                        {{ $t("global.NameIsExist") }}
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End NameAr-->

                                            <!--Start NameEn-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.NameEn") }}
                                                </label>
                                                <input type="text" class="form-control" v-model.trim="v$.nameEn.$model" id="validationCustom02"
                                                    :placeholder="$t('global.NameEn')"
                                                    :class="{ 'is-invalid': v$.nameEn.$error || data.nameExist, 'is-valid': !v$.nameEn.$invalid }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.nameEn.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.nameEn.maxLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtLeast") }}
                                                        {{ v$.nameEn.minLength.$params.min }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.nameEn.minLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtMost") }}
                                                        {{ v$.nameEn.maxLength.$params.max }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="!v$.nameEn.$invalid && data.nameExist">
                                                        {{ $t("global.NameIsExist") }}
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End NameEn-->

                                            <!--Start Category Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.MainCategory") }}</label>
                                                <select @change="getSubCategory(v$.category_id.$model)"
                                                    name="type"
                                                    class="form-select"
                                                    v-model="v$.category_id.$model"
                                                    :class="{'is-invalid':v$.category_id.$error,'is-valid':!v$.category_id.$invalid}"
                                                >
                                                    <option v-for="category in categories" :key="category.id" :value="category.id" >
                                                        {{ category.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.category_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End Category Select-->

                                            <!--Start SubCategory Select-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t("global.SubCategory") }}</label>
                                                <Select2 v-model="v$.sub_category_id.$model" :options="subCategories" :settings="{ width: '100%' }" />
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_category_id.required.$invalid">{{ $t("global.NameIsRequired") }}<br /></span>
                                                </div>
                                            </div>
                                            <!--End SubCategory Select-->

                                            <!--Start Image-->
                                            <div class="col-md-12 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        {{ $t("global.ChooseImage") }}
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input name="mediaPackage" type="file" @change="preview" id="mediaPackage" accept="image/png,jepg,jpg" />
                                                </div>
                                                <span class="text-danger text-center">{{ $t("global.ImageValidation") }}</span>
                                                <p class="num-of-files">
                                                    {{
                                                    numberOfImage
                                                    ? numberOfImage + " Files Selected"
                                                    : "No Files Chosen"
                                                    }}
                                                </p>
                                                <div class="container-images" id="container-images" v-show="data.file && numberOfImage"></div>
                                                <div class="container-images" v-show="!numberOfImage">
                                                    <figure>
                                                        <figcaption v-if="image">
                                                            <img :src="`/upload/alternative/${image}`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <!--End Image-->

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
import {required, minLength, maxLength, integer} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import { useI18n } from "vue-i18n";

export default {
    name: "editAlternative",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const emitter = inject("emitter");
        const { id } = toRefs(props);
        const { t } = useI18n({});

        // get create Package
        let loading = ref(false);
        let categories = ref([]);
        let subCategories = ref([]);
        let image = ref('');

        let getAlternative = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/alternative/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addAlternative.data.nameAr          = l.alternative.nameAr;
                    addAlternative.data.nameEn          = l.alternative.nameEn;
                    addAlternative.data.category_id     = l.alternative.category_id;
                    addAlternative.data.sub_category_id = l.alternative.sub_category_id;
                    image.value                         = l.alternative.media.file_name;
                    categories.value                    = l.categories;
                    getSubCategory(l.product.category_id);
                })
                .catch((err) => {
                    this.errors = err.response.data.errors;
                    console.log(err.response);
                    Swal.fire({
                        icon: 'error',
                        title: '???????? ??????...',
                        text: '???????? ?????? ????..!!',
                    });
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getAlternative();
        });

        let getSubCategory= (id) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/category/${id}`)
                .then((res) => {
                    let l = res.data.data;
                    subCategories.value = l.subCategories;
                })
                .catch((err) => {
                    console.log(err.response);
                    this.errors = err.response.data.errors;
                    Swal.fire({
                        icon: 'error',
                        title: '???????? ??????...',
                        text: '???????? ?????? ????..!!',
                    });
                })
                .finally(() => {
                    loading.value = false;
                })
        };

        //start design
        let addAlternative =  reactive({
            data:{
                nameAr : '',
                nameEn: '',
                category_id: null,
                sub_category_id: null,
                file : {}
            }
        });

        const rules = computed(() => {
            return {
                nameAr: {
                    minLength: minLength(3),
                    maxLength:maxLength(100),
                    required
                },
                nameEn: {
                    minLength: minLength(3),
                    maxLength: maxLength(100),
                    required
                },
                category_id: {
                    required,
                },
                sub_category_id: {
                    required,
                },
            };
        });

        const v$ = useVuelidate(rules,addAlternative.data);

        let preview = (e) => {

            let containerImages = document.querySelector('#container-images');
            if(numberOfImage.value){
                containerImages.innerHTML = '';
            }
            addAlternative.data.file = {};

            numberOfImage.value = e.target.files.length;

            addAlternative.data.file = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement('figure');
            let figcap = document.createElement('figcaption');

            figcap.innerText = addAlternative.data.file.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement('img');
                img.setAttribute('src',reader.result);
                figure.insertBefore(img,figcap);
            }

            containerImages.appendChild(figure);
            reader.readAsDataURL(addAlternative.data.file);

        };

        const numberOfImage = ref(0);

        return {
            id,
            loading,
            ...toRefs(addAlternative),
            v$,
            preview,
            numberOfImage,
            image,
            categories,
            subCategories,
            getSubCategory,
        };
    },
    methods: {
        editAlternative(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                let formData = new FormData();
                formData.append('nameAr',this.data.nameAr);
                formData.append('nameEn', this.data.nameEn);
                formData.append('category_id', this.data.category_id);
                formData.append('sub_category_id', this.data.sub_category_id);
                formData.append('file',this.data.file);
                formData.append('_method','PUT');

                adminApi.post(`/v1/dashboard/alternative/${this.id}`,formData)
                .then((res) => {
                    notify({
                        title: `???? ?????????????? ?????????? <i class="fas fa-check-circle"></i>`,
                        type: "success",
                        duration: 5000,
                        speed: 2000
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
        }
    }
}
</script>

<style scoped>
.coustom-select {
    height: 100px;
}
.card{
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

.num-of-files{
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
