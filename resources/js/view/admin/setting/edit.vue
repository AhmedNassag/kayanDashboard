<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('sidebar.setting')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexSetting'}">{{$t('sidebar.setting')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.Edit')}}</li>
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
                                    :to="{name: 'indexSetting'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['close']">{{ errors['close'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['show_price']">{{ errors['show_price'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['wats_app']">{{ errors['wats_app'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['facebook']">{{ errors['facebook'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['phone']">{{ errors['phone'][0] }}<br /> </div>
                                    <form @submit.prevent="editJob" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.Phone') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.phone.$model"
                                                       :class="{'is-invalid':v$.phone.$error,'is-valid':!v$.phone.$invalid}"
                                                       :placeholder="$t('global.Phone')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span
                                                        v-if="v$.phone.required.$invalid">{{ $t('global.PhoneIsRequired') }} <br/></span>
                                                    <span
                                                        v-if="v$.phone.minLength.$invalid">{{ $t('global.PhoneIsMustHaveAtLeast') }} {{
                                                            v$.phone.minLength.$params.min
                                                        }} {{ $t('global.Letters') }} <br/></span>
                                                    <span
                                                        v-if="v$.phone.maxLength.$invalid">{{ $t('global.PhoneIsMustHaveAtMost') }} {{
                                                            v$.phone.maxLength.$params.max
                                                        }} {{ $t('global.Letters') }} </span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.whatsApp') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.wats_app.$model"
                                                       :class="{'is-invalid':v$.wats_app.$error,'is-valid':!v$.wats_app.$invalid}"
                                                       :placeholder="$t('global.whatsApp')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span
                                                        v-if="v$.wats_app.required.$invalid">{{ $t('global.PhoneIsRequired') }} <br/></span>
                                                    <span
                                                        v-if="v$.wats_app.minLength.$invalid">{{ $t('global.PhoneIsMustHaveAtLeast') }} {{
                                                            v$.wats_app.minLength.$params.min
                                                        }} {{ $t('global.Letters') }} <br/></span>
                                                    <span
                                                        v-if="v$.wats_app.maxLength.$invalid">{{ $t('global.PhoneIsMustHaveAtMost') }} {{
                                                            v$.wats_app.maxLength.$params.max
                                                        }} {{ $t('global.Letters') }} </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.email') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.email.$model"
                                                       :class="{'is-invalid':v$.email.$error,'is-valid':!v$.email.$invalid}"
                                                       :placeholder="$t('global.email')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.facebook') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.facebook.$model"
                                                       :class="{'is-invalid':v$.facebook.$error,'is-valid':!v$.facebook.$invalid}"
                                                       :placeholder="$t('global.facebook')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.linkedin') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.linkedin.$model"
                                                       :class="{'is-invalid':v$.linkedin.$error,'is-valid':!v$.linkedin.$invalid}"
                                                       :placeholder="$t('global.linkedin')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.pinterest') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.pinterest.$model"
                                                       :class="{'is-invalid':v$.pinterest.$error,'is-valid':!v$.pinterest.$invalid}"
                                                       :placeholder="$t('global.pinterest')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.instagram') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.instagram.$model"
                                                       :class="{'is-invalid':v$.instagram.$error,'is-valid':!v$.instagram.$invalid}"
                                                       :placeholder="$t('global.instagram')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.youtube') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.youtube.$model"
                                                       :class="{'is-invalid':v$.youtube.$error,'is-valid':!v$.youtube.$invalid}"
                                                       :placeholder="$t('global.youtube')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.address') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.address.$model"
                                                       :class="{'is-invalid':v$.address.$error,'is-valid':!v$.address.$invalid}"
                                                       :placeholder="$t('global.address')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.work_time') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.work_time.$model"
                                                       :class="{'is-invalid':v$.work_time.$error,'is-valid':!v$.work_time.$invalid}"
                                                       :placeholder="$t('global.work_time')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom02">{{$t('global.closeApp')}}</label>
                                                <input type="checkbox" id="validationCustom02" v-model="data.close" class="m-5" :checked="data.close">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{$t('global.showPrice')}}</label>
                                                <input type="checkbox"  v-model="data.show_price" class="m-5" :checked="data.show_price">
                                            </div>

                                        </div>

                                        <button class="btn btn-primary" type="submit">{{$t('global.Submit')}}</button>
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
import {required, minLength, maxLength, numeric,email} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "editJob",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const {id} = toRefs(props)
        const {t} = useI18n({});
        let job = ref({});
        let loading = ref(false);

        let getMainJobViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/setting/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addJob.data.close = l.setting.close;
                    addJob.data.show_price = l.setting.show_price;
                    addJob.data.phone = l.setting.phone;
                    addJob.data.wats_app = l.setting.wats_app;
                    addJob.data.facebook = l.setting.facebook;
                    addJob.data.linkedin = l.setting.linkedin;
                    addJob.data.address = l.setting.address;
                    addJob.data.email = l.setting.email;
                    addJob.data.youtube = l.setting.youtube;
                    addJob.data.pinterest = l.setting.pinterest;
                    addJob.data.instagram = l.setting.instagram;
                    addJob.data.work_time = l.setting.work_time;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getMainJobViews();
        });

        let addJob =  reactive({
            data:{
                close:0,
                show_price:0,
                phone: '',
                wats_app: '',
                facebook: '',
                instagram: '',
                youtube: '',
                pinterest: '',
                email: '',
                address: '',
                work_time: '',
                linkedin: '',
            }
        });

        const rules = computed(() => {
            return {
                phone: {
                    required,
                    minLength: minLength(8),
                    maxLength: maxLength(20)
                },
                wats_app: {
                    required,
                    minLength: minLength(8),
                    maxLength: maxLength(20)
                },
                facebook: {
                    required,
                },
                instagram: {

                },
                linkedin: {

                },
                work_time: {

                },
                pinterest: {

                },
                youtube: {

                },
                email: {
                   email
                },
                address: {

                },
            }
        });


        const v$ = useVuelidate(rules,addJob.data);


        return {t,loading,...toRefs(addJob),v$};
    },
    methods: {
        editJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/setting/${this.id}`,this.data)
                    .then((res) => {
                        notify({
                            title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });
                    })
                    .catch((err) => {
                        this.errors = err.response.data.errors;
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
</style>
