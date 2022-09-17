<template>
  <div
    class="know-us-way-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <KnowUsWayForm
      :selectedKnowUsWay="selectedKnowUsWay"
      @created="onCreated"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.KnowUsWays") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.KnowUsWays") }}</li>
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
                      data-target="#knowUsWayFormModal"
                      v-if="permission.includes('know-us-way create')"
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
                  <tbody v-if="knowUsWays.length">
                    <tr v-for="(knowUsWay, index) in knowUsWays" :key="knowUsWay.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ knowUsWay.name }}</td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(knowUsWay, index)"
                          data-toggle="modal"
                          data-target="#knowUsWayFormModal"
                          v-if="permission.includes('know-us-way edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                        <a
                          href="#"
                          @click="deleteKnowUsWay(knowUsWay, index)"
                          data-bs-target="#staticBackdrop"
                          v-if="permission.includes('know-us-way delete')"
                          class="btn btn-sm btn-danger me-2"
                        >
                          <i class="far fa-trash-alt"></i>
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
      <Pagination :data="paginate" @pagination-change-page="getKnowUsWays">
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
import knowUsWayStore from "./know-us-way-store";
import knowUsWayClient from "../../../http-clients/know-us-way-client";
import KnowUsWayForm from "./form";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
export default {
  components: {
    KnowUsWayForm,
  },
  setup() {
    const data = reactive({
      paginate: {},
      knowUsWays: [],
      text: "",
      timeout: null,
      selectedKnowUsWay: null,
      selectedKnowUsWayIndex: 0,
      page: 1,
      pageSize: 5,
      loading: false,
    });
    const { t, locale } = useI18n({});
    provide("know_us_way_store", knowUsWayStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedKnowUsWay = null;
      //Make little delay to ensure that watcher that found in knowUsWay form component
      // catch selectedKnowUsWay input prop
      setTimeout(() => {
        knowUsWayStore.onFormShow = !knowUsWayStore.onFormShow;
      }, 1);
    }
    function onEditClicked(knowUsWay, index) {
      data.selectedKnowUsWay = knowUsWay;
      data.selectedKnowUsWayIndex = index;
      //Make little delay to ensure that watcher catch selectedKnowUsWay input prop
      //in knowUsWay form component
      setTimeout(() => {
        knowUsWayStore.onFormShow = !knowUsWayStore.onFormShow;
      }, 1);
    }
    function getKnowUsWays(page = 1) {
      data.page = page;
      data.loading = true;
      knowUsWayClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.knowUsWays = response.data.data;
          data.paginate = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      const _data = data.knowUsWays;
      const fileName = "knowUsWays";
      const exportType = exportFromJSON.types.csv;
      if (_data) exportFromJSON({ _data, fileName, exportType });
    }
    function onCreated(event) {
      data.knowUsWays.unshift(event);
    }
    function onUpdated(event) {
      data.knowUsWays[data.selectedKnowUsWayIndex] = event;
      data.selectedKnowUsWay = null;
    }
    function deleteKnowUsWay(knowUsWay, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${knowUsWay.name})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(knowUsWay, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getKnowUsWays();
      }, 500);
    }
    watch(
      () => data.text,
      () => {
        search();
      }
    );

    //Commons
    function httpDeleteRequest(knowUsWay, index) {
      data.loading = true;
      knowUsWayClient
        .delete(knowUsWay.id)
        .then((response) => {
          data.loading = false;
          data.knowUsWays.splice(index, 1);
          if (data.knowUsWays.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getKnowUsWays(data.page);
          }
          Swal.fire({
            icon: "success",
            title: `${t("global.DeletedSuccessfully")}`,
            showConfirmButton: false,
            timer: 1500,
          });
        })
        .catch((error) => {
          data.loading = false;
          Swal.fire({
            icon: "error",
            title: `${t("global.ThereIsAnErrorInTheSystem")}`,
            text: `${t("global.YouCanNotDelete")}`,
          });
        });
    }
    function created() {
      getKnowUsWays();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getKnowUsWays,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deleteKnowUsWay,
      search,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.know-us-way-container {
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
