<template>
  <div
    class="about-banner-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <AboutBannerForm
      :selectedAboutBanner="selectedAboutBanner"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.AboutBanners") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.AboutBanners") }}</li>
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
                    <tr>
                      <th>#</th>
                      <th>{{ $t("global.Name") }}</th>
                      <th>{{ $t("global.Image") }}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="aboutBanners.length">
                    <tr
                      v-for="(aboutBanner, index) in aboutBanners"
                      :key="aboutBanner.id"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>{{ $t(aboutBanner.name) }}</td>
                      <td>
                        <img
                          v-if="aboutBanner.image"
                          :src="`/upload/${aboutBanner.image}`"
                        />
                        <img v-else src="/images/empty.jpg" />
                      </td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(aboutBanner, index)"
                          data-toggle="modal"
                          data-target="#aboutBannerFormModal"
                          v-if="permission.includes('about edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <th class="text-center" colspan="7">
                        {{ $t("global.NoDataFound") }}
                      </th>
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
  </div>
</template>

<script>
import { reactive, toRefs } from "@vue/reactivity";
import { computed, provide } from "@vue/runtime-core";
import aboutBannerStore from "./about-banner-store";
import aboutBannerClient from "../../../http-clients/about-banner-client";
import AboutBannerForm from "./form";
import { useStore } from "vuex";
export default {
  components: {
    AboutBannerForm,
  },
  setup() {
    const data = reactive({
      aboutBanners: [],
      selectedAboutBanner: null,
      selectedAboutBannerIndex: 0,
      loading: false,
    });
    provide("about_banner_store", aboutBannerStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onEditClicked(aboutBanner, index) {
      data.selectedAboutBanner = aboutBanner;
      data.selectedAboutBannerIndex = index;
      //Make little delay to ensure that watcher catch selectedAboutBanner input prop
      //in aboutBanner form component
      setTimeout(() => {
        aboutBannerStore.onFormShow = !aboutBannerStore.onFormShow;
      }, 1);
    }
    function getAboutBanners() {
      data.loading = true;
      aboutBannerClient
        .getAboutBanners()
        .then((response) => {
          data.loading = false;
          data.aboutBanners = response.data;
        })
        .catch((error) => {});
    }
    function onUpdated(event) {
      data.aboutBanners[data.selectedAboutBannerIndex] = event;
      data.selectedAboutBanner = null;
    }
    //Commons
    function created() {
      getAboutBanners();
    }
    return {
      ...toRefs(data),
      onEditClicked,
      getAboutBanners,
      onUpdated,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.about-banner-container {
  padding-bottom: 20px;
  .card {
    position: relative;
    .btn-custom {
      width: 30%;
    }
    .custom {
      border: 1px solid #d7d7d7;
      padding: 2px;
      border-radius: 5px;
      width: 45%;
    }
    .btn {
      color: #fff;
    }
    .active {
      background: none;
      border: none;
    }
    table {
      td {
        img {
          width: 100px;
          height: 60px;
        }
      }
    }
  }
}
</style>
