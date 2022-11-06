<template>
  <div
    class="footer-link-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <LinkFooterForm
      :selectedLink="selectedLink"
      @created="onCreated"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.FooterLinks") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.FooterLinks") }}</li>
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
                  <div class="col-5 row justify-content-end">
                    <button
                      @click="onAddClicked"
                      data-toggle="modal"
                      data-target="#footerLinkFormModal"
                      v-if="permission.includes('footer-link create')"
                      class="btn btn-custom btn-warning"
                    >
                      {{ $t("global.Add") }}
                    </button>
                  </div>
                </div>
              </div>
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
                  <tbody v-if="footerLinks.length">
                    <tr v-for="(footerLink, index) in footerLinks" :key="footerLink.id">
                      <td>{{ index + 1 }}</td>
                      <td>
                        {{ footerLink.name }}
                      </td>
                      <td>
                        <img :src="`/upload/footer-links/${footerLink.image}`" />
                      </td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(footerLink, index)"
                          data-toggle="modal"
                          data-target="#footerLinkFormModal"
                          v-if="permission.includes('footer-link edit')"
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
import footerStore from "./footer-link-store";
import footerLinkClient from "../../../http-clients/footer-link-client";
import LinkFooterForm from "./form";
import { useStore } from "vuex";
export default {
  components: {
    LinkFooterForm,
  },
  setup() {
    const data = reactive({
      footerLinks: [],
      selectedLink: null,
      selectedLinkIndex: 0,
      loading: false,
    });
    provide("link_footer_store", footerStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedLink = null;
      //Make little delay to ensure that watcher that found in footerLink form component
      // catch selectedLink input prop
      setTimeout(() => {
        footerStore.onFormShow = !footerStore.onFormShow;
      }, 1);
    }
    function onEditClicked(footerLink, index) {
      data.selectedLink = footerLink;
      data.selectedLinkIndex = index;
      //Make little delay to ensure that watcher catch selectedLink input prop
      //in footerLink form component
      setTimeout(() => {
        footerStore.onFormShow = !footerStore.onFormShow;
      }, 1);
    }
    function getFooterLinks() {
      data.loading = true;
      footerLinkClient
        .getFooterLinks()
        .then((response) => {
          data.loading = false;
          data.footerLinks = response.data;
        })
        .catch((error) => {});
    }
    function onCreated(event) {
      data.footerLinks.unshift(event);
    }
    function onUpdated(event) {
      data.footerLinks[data.selectedLinkIndex] = event;
      data.selectedLink = null;
    }
    //Commons
    function created() {
      getFooterLinks();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getFooterLinks,
      onCreated,
      onUpdated,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.footer-link-container {
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
