<template>
  <div
    class="footer-link-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <LinkFooterForm
      :selectedFooterLink="selectedFooterLink"
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
                        <img
                          v-if="footerLink.image"
                          :src="`/upload/${footerLink.image}`"
                        />
                        <img v-else src="/images/empty.jpg" />
                      </td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(footerLink, index)"
                          data-toggle="modal"
                          data-target="#footerLinkFormModal"
                          v-if="permission.includes('footer edit')"
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
      selectedFooterLink: null,
      selectedFooterLinkIndex: 0,
      loading: false,
    });
    provide("link_footer_store", footerStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onEditClicked(footerLink, index) {
      data.selectedFooterLink = footerLink;
      data.selectedFooterLinkIndex = index;
      //Make little delay to ensure that watcher catch selectedFooterLink input prop
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
      data.footerLinks[data.selectedFooterLinkIndex] = event;
      data.selectedFooterLink = null;
    }
    //Commons
    function created() {
      getFooterLinks();
    }
    return {
      ...toRefs(data),
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
