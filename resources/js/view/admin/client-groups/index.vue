<template>
  <div
    class="client-group-container"
    :class="[
      'page-wrapper',
      this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
    ]"
  >
    <ClientGroupForm
      :clients="clients"
      :selectedClientGroup="selectedClientGroup"
      @created="onCreated"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.ClientGroups") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">
                {{ $t("global.ClientGroups") }}
              </li>
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
                    {{ $t("global.Search") }}:
                    <input type="search" v-model="text" class="custom" />
                  </div>
                  <div class="col-5 row justify-content-end">
                    <button
                      @click="onAddClicked()"
                      data-toggle="modal"
                      data-target="#clientGroupFormModal"
                      v-if="permission.includes('client-group create')"
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
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="clientGroups.length">
                    <tr
                      v-for="(clientGroup, index) in clientGroups"
                      :key="clientGroup.id"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>{{ clientGroup.name }}</td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(clientGroup, index)"
                          data-toggle="modal"
                          data-target="#clientGroupFormModal"
                          v-if="permission.includes('client-group edit')"
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
      <!-- start Pagination -->
      <Pagination :data="paginate" @pagination-change-page="getClientGroups">
        <template #prev-nav>
          <span>&lt; {{ $t("global.Previous") }}</span>
        </template>
        <template #next-nav>
          <span>{{ $t("global.Next") }} &gt;</span>
        </template>
      </Pagination>
      <!-- end Pagination -->
    </div>
  </div>
</template>

<script>
import { reactive, toRefs } from "@vue/reactivity";
import { computed, provide, watch } from "@vue/runtime-core";
import clientGroupStore from "./client-group-store";
import clientGroupClient from "../../../http-clients/client-group-client";
import ClientGroupForm from "./form";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
export default {
  components: {
    ClientGroupForm,
  },
  setup() {
    const data = reactive({
      paginate: {},
      clientGroups: [],
      text: "",
      timeout: null,
      selectedClientGroup: null,
      selectedClientGroupIndex: 0,
      page: 1,
      pageSize: 5,
      loading: false,
      clients: [],
    });
    const { t, locale } = useI18n({});
    provide("client_group_store", clientGroupStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedClientGroup = null;
      //Make little delay to ensure that watcher that found in clientGroup form component
      // catch selectedClientGroup input prop
      setTimeout(() => {
        clientGroupStore.onFormShow = !clientGroupStore.onFormShow;
      }, 1);
    }
    function onEditClicked(clientGroup, index) {
      data.selectedClientGroup = clientGroup;
      data.selectedClientGroupIndex = index;
      //Make little delay to ensure that watcher catch selectedClientGroup input prop
      //in clientGroup form component
      setTimeout(() => {
        clientGroupStore.onFormShow = !clientGroupStore.onFormShow;
      }, 1);
    }
    function getClientGroups(page = 1) {
      data.page = page;
      data.loading = true;
      clientGroupClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.clientGroups = response.data.data;
          data.paginate = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      const _data = data.clientGroups;
      const fileName = "ClientGroups";
      const exportType = exportFromJSON.types.csv;
      if (_data) exportFromJSON({ _data, fileName, exportType });
    }
    function onCreated(event) {
      data.clientGroups.unshift(event);
    }
    function onUpdated(event) {
      data.clientGroups[data.selectedClientGroupIndex] = event;
      data.selectedClientGroup = null;
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getClientGroups();
      }, 500);
    }
    watch(
      () => data.text,
      () => {
        search();
      }
    );

    //Commons
    function getAllClients() {
      data.loading = true;
      clientGroupClient
        .getAllClients()
        .then((response) => {
          data.loading = false;
          data.clients = response.data;
        })
        .catch((error) => {});
    }
    function created() {
      getClientGroups();
      getAllClients();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getClientGroups,
      downloadExcelFile,
      onCreated,
      onUpdated,
      search,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.client-group-container {
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
  }
}
</style>