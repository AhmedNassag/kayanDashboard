<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.Package') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'package', params: {lang: locale || 'ar'}}">{{ $t('global.Package') }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t('global.CreatePackage') }}</li>
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
                                    :to="{name: 'package', params: {lang: locale || 'ar'}}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{ $t('global.back') }}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <!-- <div class="alert alert-danger text-center" v-if="errors['en.name']">{{ errors['en.name'][0] }}<br /> </div> -->
                                    <!-- <div class="alert alert-danger text-center" v-if="errors['ar.name']">{{ errors['ar.name'][0] }}<br /> </div> -->
                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>
                                    <form @submit.prevent="storePackage" class="needs-validation">
                                        <div class="form-row row">
                                            <!-- <div class="col-md-6 mb-3"> -->
                                                <!-- <label for="validationCustom01">{{ $t('global.NameEn') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.en.name.$model"
                                                       id="validationCustom01"
                                                       :placeholder="$t('global.NameEn')"
                                                       :class="{'is-invalid':v$.en.name.$error,'is-valid':!v$.en.name.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.en.name.required.$invalid">{{ $t('global.NameEnIsRequired') }}<br /> </span>
                                                    <span v-if="v$.en.name.minLength.$invalid">{{ $t('global.NameEnIsMustHaveAtLeast') }} {{
                                                            v$.name.minLength.$params.min
                                                        }} {{ $t('global.Letters') }} <br />
                                                    </span>
                                                    <span v-if="v$.en.name.maxLength.$invalid">{{ $t('global.NameEnIsMustHaveAtMost') }} {{
                                                            v$.name.maxLength.$params.max
                                                        }} {{ $t('global.Letters') }}
                                                    </span>
                                                </div>
                                            </div> -->
                                            <!-- <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.NameAr') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.ar.name.$model"
                                                       id="validationCustom02"
                                                       :class="{'is-invalid':v$.ar.name.$error,'is-valid':!v$.ar.name.$invalid}"
                                                       :placeholder="$t('global.NameAr')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.ar.name.required.$invalid">{{ $t('global.NameEnIsRequired') }} <br /></span>
                                                    <span v-if="v$.ar.name.minLength.$invalid">{{ $t('global.NameEnIsMustHaveAtLeast') }} {{
                                                            v$.name.minLength.$params.min
                                                        }} {{ $t('global.Letters') }}  <br />
                                                    </span>
                                                    <span v-if="v$.ar.name.maxLength.$invalid">
                                                        {{ $t('global.NameEnIsMustHaveAtMost') }} {{
                                                            v$.name.maxLength.$params.max
                                                        }} {{ $t('global.Letters') }}
                                                    </span>
                                                </div>
                                            </div> -->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.Name') }}</label>
                                                <input type="text"
                                                    class="form-control"
                                                    v-model.trim="v$.name.$model"
                                                    id="validationCustom02"
                                                    :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                    :placeholder="$t('global.Name')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.required.$invalid">
                                                        {{ $t('global.NameIsRequired') }} <br />
                                                    </span>
                                                    <span v-if="v$.name.minLength.$invalid">
                                                        {{ $t('global.NameIsMustHaveAtLeast') }} {{ v$.name.minLength.$params.min }} {{ $t('global.Letters') }}  <br />
                                                    </span>
                                                    <span v-if="v$.name.maxLength.$invalid">
                                                        {{ $t('global.NameIsMustHaveAtMost') }} {{ v$.name.maxLength.$params.max }} {{ $t('global.Letters') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row">
                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom04">{{ $t('global.Period') }}</label>
                                                <input type="number"
                                                    class="form-control"
                                                    v-model.number.lazy="v$.period.$model"
                                                    id="validationCustom04"
                                                    placeholder="Period"
                                                    :class="{'is-invalid':v$.period.$error,'is-valid':!v$.period.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.period.required.$invalid">{{ $t('global.fieldRequired') }} <br /></span>
                                                    <span v-if="v$.period.integer.$invalid">{{ $t('global.fieldNumber') }} <br /></span>
                                                    <span v-if="v$.period.between.$invalid">
                                                         {{ $t('global.mustBeField') }} {{ v$.period.between.$params.min }} {{ $t('global.and') }} {{ v$.period.between.$params.max }}.
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom05">{{ $t('global.Visiter') }}</label>
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    v-model.number.lazy="v$.visiter_num.$model"
                                                    id="validationCustom05"
                                                    :class="{'is-invalid':v$.visiter_num.$error,'is-valid':!v$.visiter_num.$invalid}"
                                                    placeholder="Visiter Num"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.visiter_num.required.$invalid">{{ $t('global.fieldRequired') }} <br /></span>
                                                    <span v-if="v$.visiter_num.integer.$invalid">{{ $t('global.fieldNumber') }} <br /></span>
                                                    <span v-if="v$.visiter_num.between.$invalid">{{ $t('global.mustBeField') }} {{ v$.visiter_num.between.$params.min }} {{ $t('global.and') }} {{ v$.visiter_num.between.$params.max }}. </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom05">{{ $t('global.packagePrice') }}</label>
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    v-model.number.lazy="v$.price.$model"
                                                    id="validationCustom06"
                                                    placeholder="Price"
                                                    :class="{'is-invalid':v$.price.$error,'is-valid':!v$.price.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.price.required.$invalid">{{ $t('global.fieldRequired') }} <br /></span>
                                                    <span v-if="v$.period.integer.$invalid">{{ $t('global.fieldNumber') }} <br /></span>
                                                    <span v-if="v$.price.between.$invalid">{{ $t('global.mustBeField') }} {{ v$.price.between.$params.min }} {{ $t('global.and') }}  {{ v$.price.between.$params.max }}. </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="WebsitePages">{{ $t('global.websitePages') }}</label>
                                                    <select
                                                        multiple
                                                        class="form-control coustom-select"
                                                        id="WebsitePages"
                                                        v-model="v$.pageView_id.$model"
                                                        :class="{'is-invalid':v$.pageView_id.$error,'is-valid':!v$.pageView_id.$invalid}"
                                                        v-if="pageWeb"
                                                    >
                                                        <optgroup
                                                            v-for="page in pageWeb"
                                                            :key="page.id"
                                                            :label="page.name"
                                                        >
                                                            <option
                                                                v-for="view in page.views"
                                                                :value="view.pivot.id"
                                                                :key="view.pivot.id"
                                                                v-show="!notWebId.includes(view.pivot.id)"
                                                            >
                                                                {{ page.name+ ' -- '  +view.type}}
                                                            </option>
                                                        </optgroup>
                                                    </select>
                                                    <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                    <div class="invalid-feedback">
                                                        <span v-if="v$.pageView_id.required.$invalid">{{ $t('global.fieldRequired') }}.</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="MobilePages">{{ $t('global.mobilePages') }}</label>
                                                    <select
                                                        multiple
                                                        class="form-control coustom-select"
                                                        id="MobilePages"
                                                        v-model="v$.pageViewMobile_id.$model"
                                                        v-if="pageMobile"
                                                        :class="{'is-invalid':v$.pageViewMobile_id.$error,'is-valid':!v$.pageViewMobile_id.$invalid}"
                                                    >
                                                        <optgroup
                                                            v-for="page in pageMobile"
                                                            :key="page.id"
                                                            :label="page.name"
                                                        >
                                                            <option
                                                                v-for="view in page.views"
                                                                :value="view.pivot.id"
                                                                v-show="!notMobileId.includes(view.pivot.id)"
                                                                :key="view.pivot.id"
                                                            >
                                                                {{ page.name+ ' -- '  +view.type}}
                                                            </option>
                                                        </optgroup>
                                                    </select>
                                                    <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                    <div class="invalid-feedback">
                                                        <span v-if="v$.pageViewMobile_id.required.$invalid">{{ $t('global.fieldRequired') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit">{{ $t('global.Submit') }}</button>
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
import {computed, onMounted, reactive,toRefs,inject,ref} from "vue";
import useVuelidate from '@vuelidate/core';
import {required,minLength,between,maxLength,alpha,integer} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "createPackage",
    data(){
        return {
            errors:{}
        }
    },
    setup(){
        const emitter = inject('emitter');

        // get create Package
        let pageWeb = ref([]);
        let pageMobile =  ref([]);
        let notWebId = ref([]);
        let notMobileId = ref([]);
        let loading = ref(false);
        const {t} = useI18n({});


        let getPagesViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/advertiserPackage/create`)
                .then((res) => {
                    let l = res.data.data;
                    pageWeb.value = l.pageView;
                    pageMobile.value = l.pageViewMobile;
                    notWebId.value = l.notWebId;
                    notMobileId.value = l.notMobileId;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

         onMounted(() => {
            getPagesViews();
        });

        emitter.on('get_lang', () => {
            getPagesViews();
        });

        //start design
        let addPackage =  reactive({
            data:{
                // en:{ name : ''},
                // ar:{ name : ''},
                name : '',
                period:0,
                visiter_num: 0,
                price: 0,
                pageView_id: [],
                pageViewMobile_id: []
            }
        });

        const rules = computed(() => {
            return {
                // en:{
                //     name: {
                //         minLength: minLength(3),
                //         maxLength:maxLength(40),
                //         required
                //     }
                // },
                // ar:{
                //     name: {
                //         minLength: minLength(3),
                //         maxLength:maxLength(40),
                //         required
                //     }
                // },
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(40),
                    required
                },
                period: {
                    between: between(1, 360),
                    required,
                    integer
                },
                price: {
                    required,
                    between: between(1, 4000),
                    integer
                },
                visiter_num: {
                    required,
                    between: between(1, 4000),
                    integer
                },
                pageView_id:{
                    required
                },
                pageViewMobile_id:{
                    required
                }
            }
        });


        const v$ = useVuelidate(rules,addPackage.data);


        return {t,getPagesViews,pageWeb,pageMobile,loading,...toRefs(addPackage),notWebId,notMobileId,v$};
    },
    methods: {
        storePackage(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/advertiserPackage`,this.data)
                    .then((res) => {

                        this.getPagesViews();

                        notify({
                            title: `${this.t('global.AddedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                        this.resetForm();
                        this.$nextTick(() => { this.v$.$reset() });
                    })
                    .catch((err) => {
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        resetForm(){
            // this.data.en.name = '';
            // this.data.ar.name = '';
            this.name = '';
            this.data.period = '';
            this.data.price = '';
            this.data.visiter_num = '';
            this.data.pageView_id = [];
            this.data.pageViewMobile_id = [];

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
</style>
