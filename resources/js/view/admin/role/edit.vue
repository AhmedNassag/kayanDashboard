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
                        <h3 class="page-title">{{ $t("global.Roles") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{ name: 'indexJob' }">{{
                                    $t("global.Roles")
                                }}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t("role.EditRole") }}</li>
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
                                <router-link :to="{ name: 'indexRole' }" class="btn btn-custom btn-dark">
                                    {{ $t("global.back") }}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['name']">
                                        {{ errors["name"][0] }}<br />
                                    </div>
                                    <div class="alert alert-danger text-center" v-if="errors['permission']">
                                        {{ errors["permission"][0] }}<br />
                                    </div>
                                    <form @submit.prevent="editRole" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-lg-4">
                                                <div class="card">
                                                    <div class="card-body pt-0">
                                                        <div class="card-header mb-4">
                                                            <h5 class="card-title">
                                                                {{ $t("global.Roles") }}
                                                            </h5>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>{{ $t("global.Name") }}</label>
                                                            <input type="text" class="form-control"
                                                                v-model.trim="v$.name.$model" id="validationCustom03"
                                                                :placeholder="$t('global.Name')" :class="{
                                                                    'is-invalid': v$.name.$error,
                                                                    'is-valid': !v$.name.$invalid,
                                                                }" />
                                                            <div class="valid-feedback">
                                                                {{ $t("global.LooksGood") }}
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.name.required.$invalid">{{
                                                                    $t("global.NameIsRequired")
                                                                }}<br />
                                                                </span>
                                                                <span v-if="v$.name.minLength.$invalid">{{
                                                                    $t("global.NameIsMustHaveAtLeast")
                                                                }}
                                                                    {{ v$.name.minLength.$params.min }}
                                                                    {{ $t("global.Letters") }} <br /></span>
                                                                <span v-if="v$.name.maxLength.$invalid">{{
                                                                    $t("global.NameIsMustHaveAtMost")
                                                                }}
                                                                    {{ v$.name.maxLength.$params.max }}
                                                                    {{ $t("global.Letters") }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="card">
                                                    <div class="card-body pt-0">
                                                        <div class="card-header mb-4">
                                                            <h5 class="card-title">{{ $t('global.Permissions') }}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="card-body pt-0"
                                                        v-for="(permission, category) in permissions" :kay="category">
                                                        <div class="card-header mb-4">
                                                            <h5 class="card-title">{{ $t('global.'+category) }}</h5>
                                                        </div>
                                                        <div
                                                            class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
                                                            <div class="col mb-3 d-flex" v-for="permissio in permission"
                                                                :kay="permissio.id">
                                                                <div class="card flex-fill">
                                                                    <div class="card-body p-3 text-center">
                                                                        <p class="card-text f-12">{{ $t('role.'+permissio.name) }}
                                                                        </p>
                                                                    </div>
                                                                    <div class="card-footer">
                                                                        <label class="form-group toggle-switch mb-0"
                                                                            :for="'notification_switch' + permissio.id">
                                                                            <input type="checkbox"
                                                                                class="toggle-switch-input"
                                                                                :id="'notification_switch' + permissio.id"
                                                                                :value="permissio.id"
                                                                                v-model="v$.permission.$model"
                                                                                :checked="rolePermissions[permissio.id] == permissio.id">
                                                                            <span class="toggle-switch-label mx-auto">
                                                                                <span
                                                                                    class="toggle-switch-indicator"></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-primary" type="submit">
                                            {{ $t("global.Submit") }}
                                        </button>
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
import { required, minLength, maxLength } from "@vuelidate/validators";
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import { useI18n } from "vue-i18n";

export default {
    name: "edit",
    data() {
        return {
            errors: {},
        };
    },
    props: ["id"],
    setup(props) {
        const emitter = inject("emitter");

        const { id } = toRefs(props);
        const { t } = useI18n({});
        let loading = ref(false);
        let permissions = ref([]);
        let rolePermissions = ref({});

        let getMainRoleViews = () => {
            loading.value = true;

            adminApi
                .get(`/v1/dashboard/role/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addRole.data.name = l.role.name;
                    permissions.value = l.permission;
                    rolePermissions.value = l.rolePermissions;
                    addRole.data.permission = [];
                    for (let x in l.rolePermissions) {
                        addRole.data.permission.push(l.rolePermissions[x]);
                    }
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                });
        };

        onMounted(() => {
            getMainRoleViews();
        });

        let addRole = reactive({
            data: {
                name: "",
                permission: [],
            },
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength: maxLength(40),
                    required,
                },
                permission: {
                    required,
                },
            };
        });

        const v$ = useVuelidate(rules, addRole.data);

        return {
            t,
            permissions,
            rolePermissions,
            loading,
            ...toRefs(addRole),
            v$,
        };
    },
    methods: {
        editRole() {
            this.v$.$validate();

            if (!this.v$.$error) {
                this.loading = true;
                this.errors = {};

                adminApi
                    .put(`/v1/dashboard/role/${this.id}`, this.data)
                    .then((res) => {
                        notify({
                            title: `${this.t(
                                "global.EditSuccessfully"
                            )} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000,
                        });
                    })
                    .catch((err) => {
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            } else {
                let message = this.v$.name.$error
                    ? "يوجد خطأ في حقل الاسم"
                    : "يوجد خطأ في اختيار الصلاحيات";
                notify({
                    title: message,
                    type: "error",
                    duration: 5000,
                    speed: 2000,
                });
            }
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
</style>
