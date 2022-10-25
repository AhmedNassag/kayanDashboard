<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.Package') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">{{$t('dashboard.Dashboard')}}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t('global.Package') }}</li>
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
                                    <div class="col-5 row justify-content-end">
                                        <router-link
                                            :to="{name: 'createPackage', params: {lang: locale || 'ar'}}"
                                            class="btn btn-custom btn-warning"
                                            v-if="permission.includes('package create')"
                                        >
                                            {{ $t('global.Add') }}
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">{{ $t('global.Name') }}</th>
                                        <th class="text-center">{{ $t('global.Period') }}</th>
                                        <th class="text-center">{{ $t('global.Price') }}</th>
                                        <th class="text-center">{{ $t('global.Visiter') }}</th>
                                        <th class="text-center">{{ $t('global.Status') }}</th>
                                        <th class="text-center">{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="packages.length">
                                        <tr v-for="(item,index) in packages" :key="item.id">
                                            <td class="text-center">{{index + 1}}</td>
                                            <td class="text-center">{{item.name}}</td>
                                            <td class="text-center">{{item.period}}</td>
                                            <td class="text-center">{{item.price}}</td>
                                            <td class="text-center">{{item.visiter_num}}</td>
                                            <td class="text-center"><span :class="[parseInt(item.status) ? 'text-success': 'text-danger']">{{parseInt(item.status) ? $t('global.Active') : $t('global.Inactive')}}</span></td>
                                            <td class="text-center">
                                                <router-link :to="{name: 'showPackage', params: {lang: locale || 'ar',id:item.id}}" v-if="permission.includes('package show')" class="btn btn-sm btn-info me-2">
                                                    <i class="fas fa-book-open"></i>
                                                </router-link>
                                                <router-link :to="{name: 'editPackage', params: {lang: locale || 'ar',id:item.id}}" v-if="permission.includes('package edit')" class="btn btn-sm btn-success me-2">
                                                    <i class="far fa-edit"></i>
                                                </router-link>
                                                <a href="#" @click="deletePackage(item.id,item.name,index)" data-bs-target="#staticBackdrop" v-if="permission.includes('package delete')" class="btn btn-sm btn-danger me-2">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
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
            <Pagination  :limit="2"  :data="packagesPaginate" @pagination-change-page="getPackages">
                <template #prev-nav>
                    <span>&lt; {{$t('global.Previous')}}</span>
                </template>
                <template #next-nav>
                    <span>{{$t('global.Next')}}  &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted,inject,watch,ref,computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup(){
        const emitter = inject('emitter');
        let store = useStore();
        const {t} = useI18n({});

        let permission = computed(() => store.getters['authAdmin/permission']);

        // get packages
        let packages = ref([]);
        let packagesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');

        let getPackages = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/advertiserPackage?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    packagesPaginate.value = l.package;
                    packages.value = l.package.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getPackages();
        });

        emitter.on('get_lang', () => {
            getPackages(search.value);
        });

        watch(search, (search, prevSearch) => {
           if(search.length >= 0){
               getPackages();
           }
        });


         function deletePackage(id,packageName,index){
            Swal.fire({
                title: `${t('global.AreYouSureDelete')} (${packageName})`,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/advertiserPackage/${id}`)
                        .then((res) => {
                            packages.value.splice(index, 1);

                            Swal.fire({
                                icon: 'success',
                                title: `${t("global.DeletedSuccessfully")}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `${t('global.ThereIsAnErrorInTheSystem')}`,
                                text: `${t('global.YouCanNotDelete')}`,
                            });
                        });
                }
            });
        }

        return {packages,loading,permission,getPackages,search,deletePackage,packagesPaginate};

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
