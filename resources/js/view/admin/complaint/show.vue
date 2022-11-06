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
                        <h3 class="page-title">{{ $t("global.Complaints") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexComplaint'}">{{ $t("global.complaints") }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("complaint.ShowComplaint") }}</li>
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
                                    :to="{name: 'indexComplaint'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{ $t("global.back") }}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div
                                        class="alert alert-danger text-center"
                                        v-if="errors['content']"
                                    >
                                        {{ t("global.Exist", {field:t("global.Content")}) }} <br />
                                    </div>
                                    <form class="needs-validation">
                                        <div class="form-row row">
                                            <!--Start Type Select-->
                                            <div class="col-md-7 mb-3">
                                                <label for="validationCustom0">
                                                    {{ $t("global.Type") }}
                                                </label>
                                                <select class="form-control" v-model.trim="v$.type.$model" disabled>
                                                    <option value="Product Complaint">
                                                        {{ $t("global.Product Complaint") }}
                                                    </option>
                                                    <option value="Website Complaint">
                                                        {{ $t("global.Website Complaint") }}
                                                    </option>
                                                    <option value="Application Complaint">
                                                        {{ $t("global.Application Complaint") }}
                                                    </option>
                                                    <option value="Sales Complaint">
                                                        {{ $t("global.Sales Complaint") }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!--End Type Select-->

                                            <!--Start Content-->
                                            <div class="col-md-7 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.Content") }}
                                                </label>
                                                <textarea type="text" class="form-control custom-textarea" cols="10" rows="10"
                                                    v-model.trim="v$.content.$model"
                                                    id="validationCustom034"
                                                    disabled
                                                ></textarea>
                                            </div>
                                            <!--End Content-->

                                            <!--Start Sender-->
                                            <div class="col-md-7 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.Sender") }}
                                                </label>
                                                <input
                                                type="text"
                                                class="form-control"
                                                v-model.trim="v$.sender.$model"
                                                id="validationCustom01"
                                                disabled
                                                />
                                            </div>
                                            <!--End Sender-->

                                            <!--Start Reply-->
                                            <div class="col-md-7 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.Reply") }}
                                                </label>
                                                <textarea type="text" class="form-control custom-textarea" cols="10" rows="10"
                                                    v-model.trim="v$.reply.$model"
                                                    id="validationCustom034"
                                                    disabled
                                                ></textarea>
                                            </div>
                                            <!--End Reply-->

                                            <!--Start Responser-->
                                            <div class="col-md-7 mb-3">
                                                <label for="validationCustom01">
                                                    {{ $t("global.Responser") }}
                                                </label>
                                                <input
                                                type="text"
                                                class="form-control"
                                                v-model.trim="v$.responser.$model"
                                                id="validationCustom01"
                                                disabled
                                                />
                                            </div>
                                            <!--End Responser-->

                                        </div>
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
    name: "showComplaint",
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

        let getComplaint = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/showcomplaint/${id.value}`)
            .then((res) => {
                let l = res.data.data;
                addComplaint.data.type = l.complaint.type;
                addComplaint.data.content = l.complaint.content;
                addComplaint.data.reply = l.complaint.reply;
                addComplaint.data.sender = l.sender.name;
                addComplaint.data.responser = l.responser.name;
            })
            .catch((err) => {
                this.errors = err.response.data.errors;
                console.log(err.response);
                // Swal.fire({
                //     icon: 'error',
                //     title: 'يوجد خطأ...',
                //     text: 'يوجد خطأ ما..!!',
                // });
            })
            .finally(() => {
                loading.value = false;
            })
        }

        onMounted(() => {
            getComplaint();
        });

        //start design
        let addComplaint =  reactive({
            data:{
                type : '',
                content : '',
                reply : '',
                sender : '',
                responser : ''
            }
        });

        const rules = computed(() => {
            return {
                type: {
                    // required
                },
                content: {
                    // minLength: minLength(3),
                    // maxLength:maxLength(70),
                    // required
                },
                reply: {
                    // minLength: minLength(3),
                    // maxLength:maxLength(70),
                    // required
                },
                sender: {
                    // required
                },
                responser: {
                    // required
                },
            };
        });

        const v$ = useVuelidate(rules,addComplaint.data);

        return {id,loading,...toRefs(addComplaint),v$};
    },
    // methods: {
    //     editComplaint(){
    //         this.v$.$validate();

    //         if(!this.v$.$error) {

    //             this.loading = true;
    //             this.errors = {};

    //             let formData = new FormData();
    //             formData.append('type',this.data.type);
    //             formData.append('content',this.data.content);
    //             formData.append('_method','PUT');

    //             adminApi.post(`/v1/dashboard/complaint/${this.id}`,formData)
    //             .then((res) => {
    //                 notify({
    //                     title: `تم التعديل بنجاح <i class="fas fa-check-circle"></i>`,
    //                     type: "success",
    //                     duration: 5000,
    //                     speed: 2000
    //                 });
    //             })
    //             .catch((err) => {
    //                 this.errors = err.response.data.errors;
                        // Swal.fire({
                        //             icon: "error",
                        //             title: `${t("global.ThereIsAnErrorInTheSystem")}`,
                        //             text: `${t("global.YouCanNotDelete")}`,
                        //         });
    //             })
    //             .finally(() => {
    //                 this.loading = false;
    //             });

    //         }
    //     }
    // }
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
