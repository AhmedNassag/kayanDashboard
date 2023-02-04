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
                            <li class="breadcrumb-item"><router-link :to="{name: 'package', params: {lang: locale || 'ar'}}">{{$t('dashboard.Dashboard')}}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t('global.EditPackage') }}</li>
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
                                    <form @submit.prevent="editPackage" class="needs-validation">
                                        <div class="form-row row">
                                            <!-- <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{ $t('global.NameEn') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.en.name.$model"
                                                       id="validationCustom01"
                                                       name="name_en"
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
                                                       name="name_ar"
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
                                                    name="name"
                                                    v-model.trim="v$.name.$model"
                                                    id="validationCustom02"
                                                    :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                    :placeholder="$t('global.Name')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.required.$invalid">{{ $t('global.NameIsRequired') }} <br /></span>
                                                    <span v-if="v$.name.minLength.$invalid">{{ $t('global.NameIsMustHaveAtLeast') }} {{ v$.name.minLength.$params.min }} {{ $t('global.Letters') }}  <br />
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
                                                    name="period"
                                                    :placeholder="$t('global.Period')"
                                                    :class="{'is-invalid':v$.period.$error,'is-valid':!v$.period.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.period.required.$invalid">{{ $t('global.fieldRequired') }} <br /></span>
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
                                                    name="visiter_num"
                                                    :class="{'is-invalid':v$.visiter_num.$error,'is-valid':!v$.visiter_num.$invalid}"
                                                    :placeholder="$t('global.Visiter')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.visiter_num.required.$invalid">{{ $t('global.fieldRequired') }} <br /></span>
                                                    <span v-if="v$.visiter_num.between.$invalid">{{ $t('global.mustBeField') }} {{ v$.visiter_num.between.$params.min }} {{ $t('global.and') }} {{ v$.visiter_num.between.$params.max }}. </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom05">{{ $t('global.Price') }}</label>
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    name="price"
                                                    v-model.number.lazy="v$.price.$model"
                                                    id="validationCustom06"
                                                    :placeholder="$t('global.Price')"
                                                    :class="{'is-invalid':v$.price.$error,'is-valid':!v$.price.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.price.required.$invalid">{{ $t('global.fieldRequired') }} <br /></span>
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
                                                        :class="['form-control coustom-select',{'is-invalid':v$.pageView_id.$error,'is-valid':!v$.pageView_id.$invalid}]"
                                                        id="WebsitePages"
                                                        v-if="pageWeb"
                                                        v-model="v$.pageView_id.$model"
                                                    >
                                                        <optgroup
                                                            v-for="page in pageWeb"
                                                            :key="page.id"
                                                            :label="page.name"
                                                        >
                                                            <option
                                                                v-for="view in page.views" :key="view.id"
                                                                :value="view.pivot.id"
                                                                v-show="(notWebId.includes(view.pivot.id) && webId.includes(view.pivot.id)) || !notWebId.includes(view.pivot.id)"
                                                            >
                                                                {{ page.name+ ' -- ' + view.type}}
                                                            </option>
                                                        </optgroup>
                                                    </select>
                                                    <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="MobilePages">{{ $t('global.mobilePages') }}</label>
                                                    <select
                                                        multiple
                                                        :class="['form-control coustom-select',{'is-invalid':v$.pageViewMobile_id.$error,'is-valid':!v$.pageViewMobile_id.$invalid}]"
                                                        id="MobilePages"
                                                        v-if="pageMobile"
                                                        v-model="v$.pageViewMobile_id.$model"
                                                    >
                                                        <optgroup
                                                            v-for="page in pageMobile"
                                                            :key="page.id"
                                                            :label="page.name"
                                                        >
                                                            <option
                                                                v-for="view in page.views" :key="view.id"
                                                                :value="view.pivot.id"
                                                                v-show="(notMobileId.includes(view.pivot.id) && mobileId.includes(view.pivot.id)) || !notMobileId.includes(view.pivot.id)"
                                                            >
                                                                {{ page.name+ ' -- '  +view.type}}
                                                            </option>
                                                        </optgroup>
                                                    </select>
                                                    <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

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
import {computed, onMounted, reactive,toRefs,ref} from "vue";
import {useStore} from "vuex";
import useVuelidate from '@vuelidate/core';
import {required, minLength, between, maxLength, alpha, integer} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import {notify} from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "editPackage",
    props: ['id'],
    setup(props){
        const store = useStore();

        const { id } = toRefs(props);
        const {t} = useI18n({});

        // get create Package
        let pageWeb = ref({});
        let pageMobile = ref({});
        let notWebId = ref([]);
        let notMobileId = ref([]);
        let webId = ref([]);
        let mobileId = ref([]);
        let loading = ref(false);
        //start design
        let editPackageDate =  reactive({
            data:{
                // en:{ name : ''},
                // ar:{ name : ''},
                name: '',
                period:0,
                visiter_num: 0,
                price: 0,
                pageView_id: [],
                pageViewMobile_id: [],
                _method:'PUT'
            }
        });


        let getPagesViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/advertiserPackage/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    pageWeb.value = l.pageView;
                    pageMobile.value = l.pageViewMobile;

                    // editPackageDate.data.en.name = l.Package.translations[1].name;
                    // editPackageDate.data.ar.name = l.Package.translations[0].name;
                    editPackageDate.data.name = l.Package.name;
                    editPackageDate.data.period = l.Package.period;
                    editPackageDate.data.visiter_num = l.Package.visiter_num;
                    editPackageDate.data.price = l.Package.price;

                    l.Package.page_view.forEach((view) => {
                        editPackageDate.data.pageView_id.push(view.pivot.page_view_id);
                        webId.value.push(view.pivot.page_view_id);
                    });
                    l.Package.page_view_mobile.forEach((view) => {
                        editPackageDate.data.pageViewMobile_id.push(view.pivot.page_view_mobile_id);
                        mobileId.value.push(view.pivot.page_view_mobile_id);
                    });
                    notWebId.value = l.notWebId;
                    notMobileId.value = l.notMobileId;

                    console.log(l)
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
                }
                ,
                visiter_num: {
                    required,
                    between: between(1, 4000),
                    integer
                },
                pageView_id:{

                },
                pageViewMobile_id:{

                }
            }
        });


        const v$ = useVuelidate(rules,editPackageDate.data);


        return {t,pageWeb,pageMobile,loading,v$,...toRefs(editPackageDate),notWebId,notMobileId,mobileId,webId};
    },
    methods:{
        editPackage (){

            this.v$.$validate();
            this.loading = true;

            if(!this.v$.$error){

                adminApi.post(`/v1/dashboard/advertiserPackage/${this.id}`,this.data)
                .then((res) => {

                    notify({
                        title: `${this.t('global.EditSuccessfully')}  <i class="fas fa-check-circle"></i>`,
                        type: "success",
                        duration: 5000,
                        speed: 2000
                    });

                })
                .catch((err) => {
                    commit('errorsEdit',err.response.data.errors);
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
.page-wrapper{
    min-height: 100vh;
}
.coustom-select {
    height: 100px;
}
.card{
    position: relative;
}
</style>
