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
                        <h3 class="page-title">{{ $t("global.Schedule") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{ name: 'scheduleGet' }">{{ $t("global.Schedule") }}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t("schedule.EditSchedule") }}</li>
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
                                <router-link :to="{ name: 'scheduleGet' }" class="btn btn-custom btn-dark">
                                    {{ $t("global.back") }}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="message.length > 0">{{ message }}<br/></div>
                                    <form @submit.prevent="editSchedule" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Start Title-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.Title") }}
                                                </label>
                                                <input type="text" class="form-control"
                                                    v-model.trim="v$.title.$model" id="validationCustom01"
                                                    :placeholder="$t('global.Title')" :class="
                                                    {
                                                        'is-invalid': v$.title.$error,
                                                        'is-valid': !v$.title.$invalid,
                                                    }" />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <!-- <div class="invalid-feedback">
                                                    <span v-if="v$.title.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.title.maxLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtLeast") }}
                                                        {{ v$.title.minLength.$params.min }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                    <span v-if="v$.title.minLength.$invalid">
                                                        {{ $t("global.NameIsMustHaveAtMost") }}
                                                        {{ v$.title.maxLength.$params.max }}
                                                        {{ $t("global.Letters") }}
                                                        <br />
                                                    </span>
                                                </div> -->
                                            </div>
                                            <!--End Title-->

                                            <!--Start Start-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">
                                                    {{ $t("global.Started_at") }}
                                                </label>
                                                <input type="date" class="form-control"
                                                    v-model.trim="v$.start.$model" id="validationCustom05"
                                                    :placeholder="$t('global.Started_at')" :class="{
                                                        'is-invalid': v$.start.$error,
                                                        'is-valid': !v$.start.$invalid,
                                                    }" />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <!-- <div class="invalid-feedback">
                                                    <span v-if="v$.start.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div> -->
                                            </div>
                                            <!--End Start-->

                                            <!--Start End-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">
                                                    {{ $t("global.Ended_at") }}
                                                </label>
                                                <input type="date" class="form-control" v-model.trim="v$.end.$model"
                                                    id="validationCustom05" :placeholder="$t('global.Ended_at')"
                                                    :class="
                                                    {
                                                        'is-invalid': v$.end.$error,
                                                        'is-valid': !v$.end.$invalid,
                                                    }" />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <!-- <div class="invalid-feedback">
                                                    <span v-if="v$.end.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div> -->
                                            </div>
                                            <!--End End-->

                                            <!--Start Link-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.Link") }}
                                                </label>
                                                <input type="text" class="form-control"
                                                    v-model.trim="v$.link.$model" id="validationCustom01"
                                                    :placeholder="$t('global.Link')"
                                                />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <!-- <div class="invalid-feedback">
                                                    <span v-if="v$.link.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div> -->
                                            </div>
                                            <!--End Link-->

                                            <!--Start Price-->
                                            <!-- <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.Price") }}
                                                </label>
                                                <input type="number" class="form-control"
                                                    v-model.trim="v$.price.$model" id="validationCustom01"
                                                    :placeholder="$t('global.Price')" :class="{
                                                        'is-invalid': v$.price.$error,
                                                        'is-valid': !v$.price.$invalid,
                                                    }" />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.price.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div> -->
                                            <!--End Price-->

                                            <!--Start Visitor-->
                                            <!-- <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.Visitor") }}
                                                </label>
                                                <input type="number" class="form-control"
                                                    v-model.trim="v$.visitor.$model" id="validationCustom01"
                                                    :placeholder="$t('global.Visitor')" :class="{
                                                        'is-invalid': v$.visitor.$error,
                                                        'is-valid': !v$.visitor.$invalid,
                                                    }" />
                                                <div class="valid-feedback">
                                                    {{ $t("global.LooksGood") }}
                                                </div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.visitor.required.$invalid">
                                                        {{ $t("global.NameIsRequired") }}
                                                        <br/>
                                                    </span>
                                                </div>
                                            </div> -->
                                            <!--End Visitor-->

                                            <!--Start User Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom0">
                                                    {{ $t("global.User") }}
                                                </label>
                                                <Select2 v-model.trim="v$.user_id.$model" :options="users" :settings="{ width: '100%' }" />
                                            </div>
                                            <!--End User Select-->

                                            <!--Start Advertising Packages Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom0">
                                                    {{ $t("global.Advertising Package") }}
                                                </label>
                                                <Select2 v-model.trim="v$.advertising_package_id.$model" :options="advertisingPackages" :settings="{ width: '100%' }" />
                                            </div>
                                            <!--End Advertising Packag Select-->

                                        </div>

                                        <button class="btn btn-primary" type="submit">{{ $t("global.Submit")}}</button>
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
import { required, minLength, maxLength, integer } from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import { useI18n } from "vue-i18n";

export default {
    name: "editSchedule",
    data() {
        return {
            errors: {}
        }
    },
    props: ["id"],
    setup(props) {
        const emitter = inject("emitter");
        const { id } = toRefs(props);
        const { t } = useI18n({});
        let message = ref('');
        let users = ref([]);
        let advertisingPackages = ref([]);


        // get create Package
        let loading = ref(false);

        let getSchedule = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/scheduleAdvertise/${id.value}/edit`)
            .then((res) => {
                let l = res.data.data;
                addSchedule.data.title                  = l.schedule.title;
                addSchedule.data.start                  = l.schedule.start;
                addSchedule.data.end                    = l.schedule.end;
                addSchedule.data.link                   = l.schedule.link;
                addSchedule.data.price                  = l.schedule.price;
                addSchedule.data.visitor                = l.schedule.visitor;
                addSchedule.data.user_id                = l.schedule.user_id;
                addSchedule.data.advertising_package_id = l.schedule.advertising_package_id;

                users.value = l.users;
                advertisingPackages.value = l.advertisingPackages;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            })
        }

        onMounted(() => {
            getSchedule();
        });

        //start design
        let addSchedule = reactive({
            data: {
                title: "",
                start: "",
                end: "",
                link: "",
                price: "",
                visitor: "",
                user_id: "",
                advertising_package_id: "",
                // image: {},
                nameExist: false,
            }
        });

        const rules = computed(() => {
            return {
                title: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(70),
                },
                start: {
                    required,
                },
                end: {
                    required,
                },
                link: {
                    // required,
                },
                price: {
                    // required,
                },
                visitor: {
                    // required,
                },
                user_id: {
                    required,
                },
                advertising_package_id: {
                    required,
                },
            };
        });

        const v$ = useVuelidate(rules, addSchedule.data);

        return { id, loading, ...toRefs(addSchedule), v$, message, users, advertisingPackages };
    },
    methods: {
        editSchedule() {
            this.v$.$validate();

            if (!this.v$.$error) {

                this.loading = true;
                this.errors = {};

                let formData = new FormData();
                formData.append("title", this.data.title);
                formData.append("start", this.data.start);
                formData.append("end", this.data.end);
                formData.append("link", this.data.link);
                formData.append("price", this.data.price);
                formData.append("visitor", this.data.visitor);
                formData.append("user_id", this.data.user_id);
                formData.append("advertising_package_id", this.data.advertising_package_id);
                // formData.append("image", this.data.image);
                formData.append('_method', 'PUT');

                adminApi.post(`/v1/dashboard/scheduleAdvertise/${this.id}`, formData)
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
                    this.message = err.response.data.message;
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
