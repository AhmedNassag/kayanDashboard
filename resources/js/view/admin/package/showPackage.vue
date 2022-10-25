<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.ShowPackage') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'package', params: {lang: locale || 'ar'}}">{{ $t('global.Package') }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t('global.ShowPackage') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="mt-3 ml-3">
                            <router-link
                                :to="{name: 'package', params: {lang: locale || 'ar'}}"
                                class="btn btn-custom btn-dark"
                            >
                                {{ $t('global.back') }}
                            </router-link>
                        </div>
                        <loader v-if="loading" />
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-4">
                                    <div class="package-detail">
                                        <h4>{{ $t('global.Plan') }} ( {{Package.name}} )</h4>
                                        <h3 class="package-price">${{parseFloat(Package.price,2)}}</h3>
                                        <div class="package-feature">
                                            <ul>
                                                <li>{{Package.visiter_num}} {{ $t('global.Number of Visitors') }}</li>
                                                <li>{{Package.period}} {{ $t('global.Days visibility') }}</li>
                                                <li>{{Package.period}} {{ $t('global.Days Package Expiry') }}</li>
                                                <p v-if="pageView.length > 0">{{ $t('global.Ad appearance pages') }}</p>
                                                <span v-for="pageView in pageWeb" :key="pageView.id">
                                                    <li>{{pageView.page.name + ' -- '+ pageView.view.type }}</li>
                                                </span>
                                                <p v-if="pageViewMob">{{ $t('global.Ad appearance mobile pages') }}</p>
                                                <span v-for="pageViewMob in pageMobile" :key="pageViewMob.id">
                                                    <li>{{pageViewMob.page_mobile.name + ' -- '+ pageViewMob.view.type }}</li>
                                                </span>
                                            </ul>
                                        </div>
                                        <a href="#" class="btn btn-primary price-btn btn-block">{{ $t('global.Select Plan')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="card col-md-4 ">
                                    <div class="card-body p-3 text-center">
                                        <p class="card-text f-12">{{ $t('global.Status') }}</p>
                                    </div>
                                    <div class="card-footer">
                                        <label class="form-group toggle-switch mb-0" for="notification_switch1" v-if="Package">
                                            <input type="checkbox" @change="statusPackage" v-model="status" :checked="status"  class="toggle-switch-input" id="notification_switch1">
                                            <span class="toggle-switch-label mx-auto">
                                            <span class="toggle-switch-indicator"></span></span>
                                        </label>
                                    </div>
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
import {computed,inject,toRefs,onBeforeMount,ref} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
    name: "showPackage",
    props: ['id'],
    setup(props){
        const emitter = inject('emitter');
        const { id } = toRefs(props);
        const status = ref('')

        // get create Package
        let Package = ref({});
        let pageWeb = ref({});
        let pageMobile = ref({});
        let loading = ref(false);

        let getPackage = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/advertiserPackage/${id.value}`)
                .then((res) => {
                    let l = res.data.data;

                    Package.value = l.package;
                    status.value = parseInt(l.package.status);
                    pageWeb.value = l.package.page_view;
                    pageMobile.value = l.package.page_view_mobile;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onBeforeMount(() => {
            getPackage();
        });

        emitter.on('get_lang', () => {
            getPackage();
        });

        let statusPackage = () => {

            loading.value = true;
            let data = {status: status.value, id: id.value}

            adminApi.post(`/v1/dashboard/advertiserPackage/statusPackage`,data)
                .then((res) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح التعديل .',
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
                .catch((err) => {
                })
                .finally(() => {
                    loading.value = false;
                });
        }


        return {loading,Package,pageWeb,pageMobile,statusPackage,status};
    },
    data(){
        return {
            locale: localStorage.getItem("langAdmin")
        }
    }
}
</script>

<style scoped>
.card-body {
    min-height: 600px;
}

.package-detail {
    background: #fff;
    border: 1px solid #FEFEFE;
    box-shadow: 0px 4px 24px rgb(199 199 199 / 25%);
    border-radius: 5px;
    padding: 25px;
    margin-bottom: 25px;
}
.package-detail h4 {
    font-weight: 500;
    font-size: 18px;
}
.package-detail .package-price {
    font-weight: 700;
    font-size: 28px;
    color: #ff5b37;
    margin-bottom: 0;
}
.package-feature ul{
    padding: 0;
}

.package-feature ul li {
    margin-bottom: 15px;
    position: relative;
    font-size: 16px;
    font-weight: 500;
    padding-left: 25px;
}

.package-feature ul li:first-child {
    margin-top: 10px;
}

.package-feature ul li::before {
    content: "\f00c";
    font-family: "Font Awesome 5 Free";
    font-weight: 600;
    color: #4B9F18;
    left: 0;
    position: absolute;
    top: 0;
}

.package-feature ul li:last-child {
    margin-bottom: 10px;
}

.price-btn {
    background: #131135;
    border: 1px solid #131135;
    border-radius: 5px;
    font-weight: 500;
    color: #fff;
    padding: 12px 20px;
}

.ml-3{
    margin-left: 1.5rem;
}
</style>
