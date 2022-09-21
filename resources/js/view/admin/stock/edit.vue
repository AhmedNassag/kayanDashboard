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
                        <h3 class="page-title">{{ $t("global.Product") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexStock'}">{{ $t("global.Stock") }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("stock.EditStock") }}</li>
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
                                    :to="{name: 'indexStock'}"
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
                                    <form @submit.prevent="editStock" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Star Name-->
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
                                            <!--End Name-->

                                            <!--Start Governorate-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">
                                                    {{ $t("global.Governorate") }}
                                                </label>
                                                <input
                                                type="text"
                                                class="form-control"
                                                v-model.trim="v$.governorate.$model"
                                                id="validationCustom02"
                                                :placeholder="$t('global.Governorate')"
                                                :class="{
                                                    'is-invalid': v$.governorate.$error,
                                                    'is-valid': !v$.governorate.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.governorate.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Governorate-->

                                            <!--Start Region-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">
                                                    {{ $t("global.Region") }}
                                                </label>
                                                <input
                                                type="text"
                                                class="form-control"
                                                v-model.trim="v$.region.$model"
                                                id="validationCustom03"
                                                :placeholder="$t('global.Region')"
                                                :class="{
                                                    'is-invalid': v$.region.$error,
                                                    'is-valid': !v$.region.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.region.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Region-->

                                            <!--Start Title-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom04">
                                                    {{ $t("global.Title") }}
                                                </label>
                                                <input
                                                type="text"
                                                class="form-control"
                                                v-model.trim="v$.title.$model"
                                                id="validationCustom04"
                                                :placeholder="$t('global.Title')"
                                                :class="{
                                                    'is-invalid': v$.title.$error,
                                                    'is-valid': !v$.title.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.title.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Title-->

                                            <!--Start Location-->
                                            <!-- <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">
                                                    {{ $t("global.Title") }}
                                                </label>
                                                <input
                                                type="text"
                                                class="form-control"
                                                v-model.trim="v$.title.$model"
                                                id="validationCustom03"
                                                :placeholder="$t('global.Title')"
                                                :class="{
                                                    'is-invalid': v$.title.$error,
                                                    'is-valid': !v$.title.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.title.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div> -->
                                            <!--End Location-->

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

                                            <!--Start Email-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom06">
                                                    {{ $t("global.Email") }}
                                                </label>
                                                <input
                                                type="email"
                                                class="form-control"
                                                v-model.trim="v$.email.$model"
                                                id="validationCustom06"
                                                :placeholder="$t('global.Email')"
                                                :class="{
                                                    'is-invalid': v$.email.$error,
                                                    'is-valid': !v$.email.$invalid,
                                                }"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.email.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Email-->

                                            <!--Start Employee Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom07">
                                                    {{ $t("global.Empolyee Name") }}
                                                </label>
                                                <Select2 v-model.trim="v$.employee_id.$model" :options="employees" :settings="{ width: '100%' }" />
                                                <!-- <select class="form-control" v-model.trim="v$.employee_id.$model">
                                                    <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                                                        {{ employee.user.name }}
                                                    </option>
                                                </select> -->
                                            </div>
                                            <!--End Employee Select-->

                                            <!--Start Shift Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom08">
                                                    {{ $t("global.Shift Name") }}
                                                </label>
                                                <Select2 v-model.trim="v$.shift_id.$model" :options="shifts" :settings="{ width: '100%' }" />
                                                <!-- <select class="form-control" v-model.trim="v$.shift_id.$model">
                                                    <option v-for="shift in shifts" :key="shift.id" :value="shift.id">
                                                        {{ shift.name }}
                                                    </option>
                                                </select> -->
                                            </div>
                                            <!--End Shift Select-->

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
    name: "editStock",
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
        let employees = ref([]);
        let shifts = ref([]);

        let getStock = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/stock/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;

                    addStock.data.name = l.stock.name;
                    addStock.data.governorate = l.stock.governorate;
                    addStock.data.region = l.stock.region;
                    addStock.data.title = l.stock.title;
                    // addStock.data.latitude = l.stock.latitude;
                    // addStock.data.longitude = l.stock.longitude;
                    addStock.data.phone = l.stock.phone;
                    addStock.data.email = l.stock.email;
                    addStock.data.employee_id = l.stock.employee_id;
                    addStock.data.shift_id = l.stock.shift_id;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getStock();
        });

        //start design
        let addStock =  reactive({
            data:{
                name : '',
                governorate : '',
                region : '',
                title : '',
                latitude : '',
                longitude : '',
                phone : '',
                email : '',
                employee_id : '',
                shift_id : '',
            }
        });

        //
        getEmployees();
        getShifts();
        //

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                governorate: {
                    required,
                },
                region: {
                    required,
                },
                title: {
                    required,
                },
                // latitude: {
                //   required,
                // },
                // longitude: {
                //   required,
                // },
                phone:{
                    minLength: minLength(10),
                    required,
                },
                email:{
                    required,
                },
                employee_id:{
                    required,
                },
                shift_id:{
                    required,
                },

            };
        });

        const v$ = useVuelidate(rules,addStock.data);

        //Commons
        function getEmployees(){
            adminApi
            .get(`/v1/dashboard/getEmpolyees`)
            .then((res) => {
                employees.value =res.data.data.employees ;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
        }

        function getShifts(){
            adminApi
            .get(`/v1/dashboard/getShifts`)
            .then((res) => {
                shifts.value =res.data.data.shifts ;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
        }
        //end common
        return {id,loading,...toRefs(addStock),v$,employees,shifts};
    },
    methods: {
        editStock(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                let formData = new FormData();
                formData.append("name", this.data.name);
                formData.append("governorate", this.data.governorate);
                formData.append("region", this.data.region);
                formData.append("title", this.data.title);
                // formData.append("latitude", this.data.latitude);
                // formData.append("longitude", this.data.longitude);
                formData.append("phone", this.data.phone);
                formData.append("email", this.data.email);
                formData.append("employee_id", this.data.employee_id);
                formData.append("shift_id", this.data.shift_id);
                formData.append('_method','PUT');

                adminApi.post(`/v1/dashboard/stock/${this.id}`,formData)
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
