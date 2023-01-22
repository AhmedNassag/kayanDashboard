<template>
    <loader v-if="loading" />
    <div class="header">

        <!-- Logo -->
        <div :class="['header-left','header-left-ar']">
            <router-link :to="{name:'dashboard'}" class="logo">
                <img src="/admin/img/Logo Dashboard.png" class="big" alt="Logo">
            </router-link>
            <router-link :to="{name:'dashboard'}" class="logo logo-small">
                <img src="/admin/img/Logo Dashboard.png" :class="['img-ar']" alt="Logo" width="30" height="30">
            </router-link>
            <!-- Sidebar Toggle -->
            <a href="javascript:void(0);" id="toggle_btn">
                <i class="feather-chevrons-left"></i>
            </a>
            <!-- /Sidebar Toggle -->

            <!-- Mobile Menu Toggle -->
            <a class="mobile_btn" id="mobile_btn">
                <i class="feather-chevrons-left"></i>
            </a>
            <!-- /Mobile Menu Toggle -->
        </div>
        <!-- /Logo -->
        <!-- Header Menu -->
        <ul :class="['nav user-menu','user-menu-ar']">

            <!-- Notifications -->
            <!-- <Notification /> -->
            <!-- /Notifications -->

            <!-- User Menu -->
            <li class="nav-item dropdown has-arrow main-drop">
                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                    <span class="user-img">
                        <span class="status online">{{ user && user.name ? user.name:'' }}</span>
                        <img :src="user && user.image != null ? `/upload/user/${user.image}` : '/admin/img/Logo Dashboard.png'" alt="user-image">
                    </span>
                </a>
                <div class="dropdown-menu">
                    <router-link :to="{name:'indexProfile',params: {lang:this.$i18n.locale}}" class="dropdown-item"><i data-feather="user" class="me-1"></i> {{$t('global.Profile')}}</router-link>
                    <router-link :to="{name:'indexSetting'}" class="dropdown-item" ><i data-feather="settings" class="me-1"></i> {{ $t('global.Setting') }}</router-link>
                    <a class="dropdown-item" href="#" @click="logout"><i data-feather="log-out" class="me-1"></i> {{ $t('global.Logout') }}</a>
                </div>
            </li>
            <!-- /User Menu -->

        </ul>
        <!-- /Header Menu -->

    </div>
</template>

<script>
    import Notification from './notification.vue';
    import {useStore} from "vuex";
    import {computed,ref,onMounted} from "vue";
    import Cookies from "js-cookie";

export default {
    setup(){
        const store = useStore();
        const userId = ref(0);

        let loading = computed(() => {
            return store.getters['authAdmin/loading'];
        });
        const user = computed(() => {
            return store.getters['authAdmin/user'];
        });

        onMounted(() => {
            if (Cookies.get("tokenAdmin")){
                userId.value = JSON.parse(localStorage.getItem("user")).id
            }
        });

        function logout(){
            store.dispatch('authAdmin/logout');
        }

        return {logout,loading,userId,user};
    },
    components: {
        Notification
    }
}
</script>

<style>
.big {
    max-height: 50px !important;
    width: auto;
}
.mini-sidebar .header-left .logo img.img-ar{
    margin-left: 3px;
    margin-right: -22px;
}

.header .header-left{
    background: #FFF;
    /* background: #00DD2F; */
}
</style>
