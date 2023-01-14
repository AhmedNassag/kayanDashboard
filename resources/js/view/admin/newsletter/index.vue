<template>
  <div
    class="newsletter-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.Newsletters") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Newsletters") }}</li>
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
                      <th>{{ $t("global.Email") }}</th>
                      <th>{{ $t("global.Date") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="newsletters.length">
                    <tr v-for="(newsletter, index) in newsletters" :key="newsletter.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ newsletter.email }}</td>
                      <td>{{ newsletter.created_at }}</td>
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
      <Pagination :data="paginate" @pagination-change-page="getNewsletters">
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
import newsletterClient from "../../../http-clients/newsletter-client";
import exportFromJSON from "export-from-json";
export default {
  setup() {
    const data = reactive({
      paginate: {},
      newsletters: [],
      text: "",
      timeout: null,
      page: 1,
      pageSize: 5,
      loading: false,
    });
    created();
    //Methods
    function getNewsletters(page = 1) {
      data.page = page;
      data.loading = true;
      newsletterClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.newsletters = response.data.data;
          data.paginate = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      data.loading = true;
      newsletterClient.getNewsletters().then((response) => {
        data.loading = false;
        const fileName = "newsletter";
        const exportType = exportFromJSON.types.csv;
        exportFromJSON({ data: response.data, fileName, exportType });
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getNewsletters();
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
      getNewsletters();
    }
    return {
      ...toRefs(data),
      getNewsletters,
      downloadExcelFile,
      search,
    };
  },
};
</script>

<style lang="scss" scoped>
.newsletter-container {
  table {
    td,
    th {
      width: 50%;
    }
  }
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
