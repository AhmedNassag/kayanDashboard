<template>
  <div
    class="client-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.clientDoesntHaveOrders") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.clientDoesntHaveOrders") }}</li>
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
                    <input type="search" v-model="search" class="custom" />
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
                    </tr>
                  </thead>
                  <tbody v-if="Object.keys(clients.data ?? []).length">
                    <tr v-for="(client, index) in clients.data" :key="client.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ client.name }}</td>
                      <td>{{ client.phone }}</td>
                      <td>{{ client.email }}</td>
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
      <Pagination :data="clients" @pagination-change-page="getClients">
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
import { computed, onMounted, provide, watch,ref } from "@vue/runtime-core";
import adminApi from "../../../api/adminAxios";
export default {
  setup() {
    const clients = ref({})
    const debounce = ref('')
    const search = ref('')
    const getClients = async(page = 1 ) => {
      adminApi.get(`/v1/dashboard/get_client_doesnt_have_orders?page=${page}&search=${search.value}`).then((response) => {
        clients.value = response.data.clients;
      })
    }

    onMounted(() => {
      getClients()
    })


    watch(search,()=>{
      // clear timeout variable
      clearTimeout(debounce.value);
      debounce.value = setTimeout(() => {
          getClients();
        }, 500);
    })


    return {
      getClients,
      clients,search
    };
  },
};
</script>

<style lang="scss" scoped>
.client-container {
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
  }
}
</style>
