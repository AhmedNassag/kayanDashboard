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
                        <h3 class="page-title">{{ $t("global.Shifts") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexShift'}">{{ $t("global.SubCategory") }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("shift.EditShift") }}</li>
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
                                    :to="{name: 'indexShift'}"
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
                                    <form @submit.prevent="editShift" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t("global.Name")}}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.name.$model"
                                                       id="validationCustom01"
                                                       :placeholder="$t('global.Name')"
                                                       :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t("global.LooksGood") }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.required.$invalid">{{ $t("global.NameIsRequired") }} <br/> </span>
                                                    <span v-if="v$.name.maxLength.$invalid">{{ $t("global.NameIsMustHaveAtLeast") }} {{ v$.name.minLength.$params.min }} {{ $t("global.Letters") }} <br/></span>
                                                    <span v-if="v$.name.minLength.$invalid">{{ $t("global.NameIsMustHaveAtMost") }} {{ v$.name.maxLength.$params.max }} {{ $t("global.Letters") }}</span>
                                                </div>
                                            </div>

                                            <!--Start type Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom0">
                                                    {{ $t("global.Type") }}
                                                </label>
                                                <select class="form-select" v-model.trim="v$.type.$model">
                                                    <option value="صباحى">
                                                        {{ $t("global.Morning") }}
                                                    </option>
                                                    <option value="مسائى">
                                                        {{ $t("global.Evening") }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!--End Type Select-->

                                            <!--Start Started At-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">
                                                    {{ $t("global.Started_at") }}
                                                </label>
                                                <input
                                                type="time"
                                                class="form-control"
                                                v-model.trim="v$.started_at.$model"
                                                id="validationCustom02"
                                                :placeholder="$t('global.Started_at')"
                                                :class="{
                                                    'is-invalid': v$.started_at.$error,
                                                    'is-valid': !v$.started_at.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.started_at.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Started At-->

                                            <!--Start Ended At-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">
                                                    {{ $t("global.Ended_at") }}
                                                </label>
                                                <input
                                                type="time"
                                                class="form-control"
                                                v-model.trim="v$.ended_at.$model"
                                                id="validationCustom03"
                                                :placeholder="$t('global.Ended_at')"
                                                :class="{
                                                    'is-invalid': v$.ended_at.$error,
                                                    'is-valid': !v$.ended_at.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.ended_at.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Ended At-->

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
//import {computed, onMounted, reactive,toRefs,ref} from "vue";
import { computed, onMounted, reactive, toRefs, inject, ref } from "vue";
import useVuelidate from "@vuelidate/core";
import {required, minLength, maxLength, integer} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
//
import { useI18n } from "vue-i18n";
//

export default {
    name: "editShift",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        //
        const emitter = inject("emitter");
        const { id } = toRefs(props);
        const { t } = useI18n({});
        //

        // get create Package
        let loading = ref(false);

        let getShift = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/shift/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;

                    addShift.data.name = l.shift.name;
                    addShift.data.type = l.shift.type;
                    addShift.data.started_at = l.shift.started_at;
                    addShift.data.ended_at = l.shift.ended_at;
                })
                .catch((err) => {
                    console.log(err.response);
                    this.errors = err.response.data.errors;
                    // Swal.fire({
                    //     icon: 'error',
                    //     title: 'يوجد خطأ...',
                    //     text: 'يوجد خطأ ما..!!',
                    // });
                })
                // .finally(() => {
                //     loading.value = false;
                // })
        }

        onMounted(() => {
            getShift();
        });

        //start design
        let addShift =  reactive({
            data:{
                name : '',
                type : '',
                started_at : '',
                ended_at : ''
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                type: {
                    required,
                },
                started_at: {
                    required,
                },
                ended_at: {
                    required,
                },
            };
        });

        const v$ = useVuelidate(rules,addShift.data);

        return {id, loading, ...toRefs(addShift), v$};
    },
    methods: {
        editShift(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                let formData = new FormData();
                formData.append('name',this.data.name);
                formData.append('type',this.data.type);
                formData.append('started_at',this.data.started_at);
                formData.append('ended_at',this.data.ended_at);
                formData.append('_method','PUT');

                adminApi.post(`/v1/dashboard/shift/${this.id}`,formData)
                    .then((res) => {
                        notify({
                            title: `تم التعديل بنجاح <i class="fas fa-check-circle"></i>`,
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
