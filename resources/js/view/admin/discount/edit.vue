<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">الموردين</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexDiscount'}">الموريد</router-link></li>
                            <li class="breadcrumb-item active">تعديل مورد</li>
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
                                    :to="{name: 'indexDiscount'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    رجوع
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="editSupplier" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">الكود</label>
                                                <input
                                                    type="text" class="form-control"
                                                    v-model.trim="v$.code.$model"
                                                    id="validationCustom01"
                                                    placeholder="الكود"
                                                    :class="{'is-invalid':v$.code.$error || errors.code,'is-valid':!v$.code.$invalid && !errors.code}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.code.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.code.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.code.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.code.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.code.maxLength.$params.max }} حرف</span>
                                                    <span v-if="errors['code']">
                                                        {{ errors['code'][0] }}<br/>
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label >نوع</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.type.$model"
                                                    :class="{'is-invalid':v$.type.$error,'is-valid':!v$.type.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option value="fixed">{{ $t('global.Fixed') }}</option>
                                                    <option value="percentage">{{ $t('global.Percentage') }}</option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.type.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">القيمة</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim.number="v$.value.$model"
                                                       id="validationCustom03"
                                                       placeholder="القيمة"
                                                       :class="{'is-invalid':v$.value.$error,'is-valid':!v$.value.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.value.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.value.numeric.$invalid"> هذا الحقل يجب ان يكون رقم<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom04">عدد المستخدمين</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim.number="v$.use_times.$model"
                                                       id="validationCustom04"
                                                       placeholder="عدد المستخدمين"
                                                       :class="{'is-invalid':v$.use_times.$error,'is-valid':!v$.use_times.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.use_times.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.use_times.numeric.$invalid"> هذا الحقل يجب ان يكون رقم<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom06">وقت البداية</label>
                                                <input type="date" class="form-control"
                                                       v-model="v$.start_date.$model"
                                                       id="validationCustom06"
                                                       :class="{'is-invalid':v$.start_date.$error,'is-valid':!v$.start_date.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom07">وقت النهاية</label>
                                                <input type="date" class="form-control"
                                                       v-model="v$.expire_date.$model"
                                                       id="validationCustom07"
                                                       placeholder="اسم المسئول لدي المورد"
                                                       :class="{'is-invalid':v$.expire_date.$error,'is-valid':!v$.expire_date.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.expire_date.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom09">المبلغ اللازم للحصول على الكوبون</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.greater_than.$model"
                                                       id="validationCustom09"
                                                       placeholder="الرقم اكثر من "
                                                       :class="{'is-invalid':v$.greater_than.$error,'is-valid':!v$.greater_than.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.greater_than.numeric.$invalid"> هذا الحقل يجب ان يكون رقم<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label for="validationCustom034">الوصف</label>
                                                <textarea type="text" class="form-control custom-textarea"
                                                          v-model.trim="v$.description.$model"
                                                          id="validationCustom034"
                                                          placeholder="الوصف"
                                                          :class="{'is-invalid':v$.description.$error,'is-valid':!v$.description.$invalid}"
                                                ></textarea>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>


                                        </div>

                                        <button class="btn btn-primary" type="submit">تعديل</button>
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
import {required, minLength, maxLength, integer, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "editDepartment",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){

        const {id} = toRefs(props)
        // get create Package
        let loading = ref(false);


        let getSupplier = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/discount/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addDiscount.data.code = l.discount.code;
                    addDiscount.data.type = l.discount.type;
                    addDiscount.data.value = l.discount.value;
                    addDiscount.data.use_times = l.discount.use_times;
                    addDiscount.data.expire_date = l.discount.expire_date;
                    addDiscount.data.greater_than = l.discount.greater_than;
                    addDiscount.data.start_date = l.discount.start_date || new Date().toISOString().split('T')[0];
                    addDiscount.data.description = l.discount.description;
                    console.log(l);
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getSupplier();
        });


        //start design
        let addDiscount =  reactive({
            data:{
                code : '',
                type : '',
                value : null,
                description : '',
                use_times : null,
                start_date : '',
                expire_date : '',
                greater_than: null
            }
        });

        const rules = computed(() => {
            return {
                code: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                type: {
                    required
                },
                value: {
                    numeric,
                    required
                },
                use_times: {
                    required,
                    numeric
                },
                expire_date: {
                    required
                },
                greater_than: {
                    numeric
                },
                start_date:{},
                description:{}
            }
        });


        const v$ = useVuelidate(rules,addDiscount.data);


        return {loading,...toRefs(addDiscount),v$};
    },
    methods: {
        editSupplier(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/discount/${this.id}`,this.data)
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
</style>
