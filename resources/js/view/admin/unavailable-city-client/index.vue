<template>
  <div
    class="unavailable-city-user-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.UnvailableCitiesClients") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">
                {{ $t("global.UnvailableCitiesClients") }}
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
                    <button @click="downloadExcelFile" class="btn btn-custom btn-warning">
                      <i class="fa fa-download" aria-hidden="true"></i>
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
                      <th>{{ $t("global.Phone") }}</th>
                      <th>{{ $t("global.Email") }}</th>
                      <th>{{ $t("global.City") }}</th>
                      <th>{{ $t("global.Address") }}</th>
                      <th>{{ $t("global.StoreName") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="clients.length">
                    <tr v-for="(client, index) in clients" :key="client.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ client.user.name }}</td>
                      <td>{{ client.user.phone }}</td>
                      <td>{{ client.user.email }}</td>
                      <td>{{ client.city.name }}</td>
                      <td>{{ client.address }}</td>
                      <td>{{ client.store_name }}</td>
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
      <Pagination :data="paginate" @pagination-change-page="getUnavailableCitiesClients">
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
import { watch } from "@vue/runtime-core";
import unavailableCityClientClient from "../../../http-clients/unavailable-city-client-client";
import exportFromJSON from "export-from-json";
export default {
  setup() {
    const data = reactive({
      paginate: {},
      clients: [],
      text: "",
      timeout: null,
      page: 1,
      pageSize: 5,
      loading: false,
    });
    created();
    //Methods
    function getUnavailableCitiesClients(page = 1) {
      data.page = page;
      data.loading = true;
      unavailableCityClientClient
        .getUnavailableCitiesClients(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.clients = response.data.data;
          data.paginate = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      data.loading = true;
      unavailableCityClientClient.getAllUnavailableCitiesClients().then((response) => {
        data.loading = false;
        const fileName = "unavailable-cities-clients";
        const exportType = exportFromJSON.types.csv;
        let _data = response.data.map((element) => {
          return {
            store_name: element.store_name,
            address: element.address,
            user_name: element.user.name,
            user_phone: element.user.phone,
            user_email: element.user.email,
            city_name: element.city.name,
          };
        });
        exportFromJSON({ data: _data, fileName, exportType });
      });
    }

    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getUnavailableCitiesClients();
      }, 500);
    }

    watch(
      () => data.text,
      () => {
        search();
      }
    );
    //Commons
    function created() {
      getUnavailableCitiesClients();
    }
    return {
      ...toRefs(data),
      getUnavailableCitiesClients,
      downloadExcelFile,
      search,
    };
  },
};
</script>

<style lang="scss" scoped>
.unavailable-city-user-container {
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
      color: #FFF;
    }
  }
}
</style>
