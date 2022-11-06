<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('sidebar.Schedule')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">{{$t('dashboard.Dashboard')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('sidebar.Schedule')}}</li>
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
                            <div class="card-header pt-0">
                                <div class="row justify-content-between">
                                    <div class="col-5">
                                        {{ $t('global.Search') }}:
                                        <input type="search" v-model="search" class="custom" />
                                    </div>
                                    <div class="col-4 row justify-content-end">
                                        <!-- v-if="permission.includes('schedule create')" -->
                                        <!-- <router-link
                                            :to="{ name: 'createSchedule' }"
                                            class="btn btn-custom btn-warning"
                                        >
                                            {{ $t("global.Add") }}
                                        </router-link> -->
                                        &emsp;
                                        <router-link
                                            :to="{ name: 'packagesSchedule' }"
                                            class="btn btn-custom btn-warning"
                                        >
                                            {{ $t("global.payNow") }}
                                        </router-link>
                                        &emsp;
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr class="text-center">
                                        <th class="text-center">#</th>
                                        <!-- <td class="text-center">{{ $t('sidebar.advertise') }}</td> -->
                                        <th class="text-center">{{ $t('global.Start Date') }}</th>
                                        <th class="text-center">{{ $t('global.End Date') }}</th>
                                        <th class="text-center">{{ $t('global.Package') }}</th>
                                        <th class="text-center">{{ $t('global.Status') }}</th>
                                        <th class="text-center">{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="calender.length">
                                        <tr class="text-center" v-for="(item,index) in calender" :key="item.id">
                                            <td class="text-center">{{index + 1}}</td>
                                            <!-- <td class="text-center">{{item.users.name}}</td> -->
                                            <td class="text-center">{{dateFormate(item.start)}}</td>
                                            <td class="text-center">{{dateFormate(item.end)}}</td>
                                            <td class="text-center">{{item.packages.name}}</td>
                                            <td class="text-center">
                                                <a href="#" @click="activation(item.id,item.title,item.status,index)">
                                                    <span :class="[parseInt(item.status) ? 'text-success hover': 'text-danger hover']">{{ parseInt(item.status) ? $t('global.Active') : $t('global.Inactive')}} </span>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <!-- v-if="permission.includes('schedule edit')" -->
                                                <router-link
                                                :to="{ name: 'editSchedule', params: { id: item.id }}"
                                                class="btn btn-sm btn-success me-2"
                                                >
                                                    <i class="far fa-edit"></i>
                                                </router-link>

                                                <!-- v-if="permission.includes('schedule delete')" -->
                                                <a
                                                    href="#"
                                                    @click="deleteSchedule(item.id, index)"
                                                    data-bs-target="#staticBackdrop"
                                                    class="btn btn-sm btn-danger me-2"
                                                    >
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="9">{{ $t('global.NoDataFound') }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
            <!-- start Pagination -->
            <Pagination :limit="2" :data="calenderPagination" @pagination-change-page="getCalender">
                <template #prev-nav>
                    <span>&lt; Previous</span>
                </template>
                <template #next-nav>
                    <span>Next &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {computed, inject, onMounted, ref, watch} from "vue";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "scheduleGet",
    setup(){
        const emitter = inject('emitter');

        // get packages
        let calender = ref([]);
        let calenderPagination = ref({});
        let loading = ref(false);
        const {t} = useI18n({});

        let getCalender = (page = 1) => {

            loading.value = true;

            adminApi.get(`/v1/dashboard/scheduleAdvertise?page=${page}&search=${search.value}`)
            .then((res) => {
                let l = res.data.data;
                calender.value = l.schedule.data;
                calenderPagination.value = l.schedule;
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
            .finally(() => {
                loading.value = false;
            });
        }

        emitter.on('get_lang', () => {
            getCalender();
        });

        const search = ref('')
        watch(search, (search, prevSearch) => {
            if(search.length > 0){
                getCalender();
            }else{
                getCalender();
            }
        });

        onMounted(() => {
            getCalender();
        });

        let dateFormate = (item) => {
            return new Date(item).toDateString();
        }


        function deleteSchedule(id, index) {
            Swal.fire({
                title: `${t("global.AreYouSureDelete")}`,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: t("global.Yeas"),
                cancelButtonText: t("global.No"),
            })
            .then((result) => {
                if (result.isConfirmed) {
                    adminApi
                    .delete(`/v1/dashboard/scheduleAdvertise/${id}`)
                    .then((res) => {
                        calender.value.splice(index, index + 1);

                        Swal.fire({
                            icon: "success",
                            title: `${t("global.DeletedSuccessfully")}`,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: `${t("global.ThereIsAnErrorInTheSystem")}`,
                            text: `${t("global.YouCanNotDelete")}`,
                        });
                    });
                }
            });
        }

        function activation(id, jobName, active,index) {
            Swal.fire({
                title: `${active ? t('global.AreYouSureInactive') : t('global.AreYouSureActive')} `, /*(${jobName})*/
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.get(`/v1/dashboard/activation/${id}`)
                    .then((res) => {
                        Swal.fire({
                            icon: 'success',
                            title: `${active ? t('global.InactiveSuccessfully') :t('global.ActiveSuccessfully')}`,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        calender.value[index]['status'] =  active ? 1:0;
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: 'error',
                            title: `${t('global.ThereIsAnErrorInTheSystem')}`,
                            text: `${t('global.YouCanNotModifyThisSafe')}`,
                        });
                    });
                }
            });
        }

        return {calender,loading,getCalender,search,dateFormate,activation,deleteSchedule,calenderPagination};

    },
    data(){
        return {
            locale: localStorage.getItem("langAdmin")
        }
    }
}
</script>

<style scoped>
.card {
    position: relative;
}
.btn-custom {
    width: 30%;
}
.custom {
    border: 1px solid #D7D7D7;
    padding: 2px;
    border-radius: 5px;
    width: 35%;
}
.btn {
    color: #fff;
}
</style>
