<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Update Desktop Banners')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">{{$t('dashboard.Dashboard')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.Update Desktop Banners')}}</li>
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

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>{{ $t('global.Page Name') }}</th>
                                        <td>{{ $t('global.Direction') }}</td>
                                        <th>{{ $t('global.Status') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="Object.keys(banners??[]).length">
                                        <tr class="text-center" v-for="(item,index) in banners" :key="item.id">
                                            <td>{{index + 1}}</td>
                                            <td>{{item.page.name}}</td>
                                            <td>{{item.view.type}}</td>
                                            <td>
                                                <label class="form-group toggle-switch mb-0"
                                                        :for="'notification_switch-'+item.id">
                                                    <input type="checkbox"
                                                            class="toggle-switch-input"
                                                            :id="'notification_switch-'+item.id" :checked="item.status"
                                                            @change="changeStatus(item.id)"
                                                            >
                                                    <span class="toggle-switch-label mx-auto">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
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
        let banners = ref({});
        let loading = ref(false);
        const {t} = useI18n({});

        let getBanners = () => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/get_desktop_banners`)
                .then((res) => {
                    banners.value = res.data.banners;
                    console.log(banners.value)
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        emitter.on('get_lang', () => {
            getBanners();
        });


        onMounted(() => {
            getBanners();
        });


        const changeStatus =async (banner_id) => {
            await adminApi.post('/v1/dashboard/change_desktop_banner_status',{'banner_id':banner_id})
        }

        return {banners,loading,changeStatus};

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
