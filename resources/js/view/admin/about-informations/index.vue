<template>
  <div
    class="about-section-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <AboutSectionForm
      :AboutInformationsIndex="selectedAboutSection"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.AboutSections") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.AboutSections") }}</li>
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
                  <tbody v-if="aboutSections.length">
                    <tr
                      v-for="(aboutSection, index) in aboutSections"
                      :key="aboutSection.id"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>{{ aboutSection.header ? $t(aboutSection.header) :''}}</td>
                      <td v-if="aboutSection.id!=1">
                        <img
                          v-if="aboutSection.image"
                          :src="`/upload/${aboutSection.image}`"
                        />
                        <img v-else src="/images/empty.jpg" />
                      </td>
                      <td v-else>{{$t("global.ImageNotFoundForThisSection")}}</td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(aboutSection, index)"
                          data-toggle="modal"
                          data-target="#aboutSectionFormModal"
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
import aboutInformationsStore from "./about-informations-store";
import aboutInformationsClient from "../../../http-clients/about-informations-client";
import AboutSectionForm from "./form";
import { useStore } from "vuex";
export default {
  components: {
    AboutSectionForm,
  },
  setup() {
    const data = reactive({
      aboutSections: [],
      selectedAboutSection: null,
      selectedAboutSectionIndex: 0,
      loading: false,
    });
    provide("about_section_store", aboutInformationsStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onEditClicked(aboutSection, index) {
      data.selectedAboutSection = aboutSection;
      data.selectedAboutSectionIndex = index;
      //Make little delay to ensure that watcher catch selectedAboutSection input prop
      //in aboutSection form component
      setTimeout(() => {
        aboutInformationsStore.onFormShow = !aboutInformationsStore.onFormShow;
      }, 1);
    }
    function getAboutSections() {
      data.loading = true;
      aboutInformationsClient
        .getAboutSections()
        .then((response) => {
          data.loading = false;
          data.aboutSections = response.data;
        })
        .catch((error) => {});
    }
    function onUpdated(event) {
      data.aboutSections[data.selectedAboutSectionIndex] = event;
      data.selectedAboutSection = null;
    }
    //Commons
    function created() {
       getAboutSections();
    }
    return {
      ...toRefs(data),
      onEditClicked,
      getAboutSections,
      onUpdated,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.about-section-container {
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
