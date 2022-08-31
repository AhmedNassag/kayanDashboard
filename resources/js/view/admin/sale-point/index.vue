<template>
  <div
    class="sale-point-container"
    :class="[
      'page-wrapper',
      this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
    ]"
  >
    <SalePointForm
      :mainCategories="mainCategories"
      :selectedSalePoint="selectedSalePoint"
      :clientGroups="clientGroups"
      @created="onCreated"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.SalePoints") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">
                {{ $t("global.SalePoints") }}
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
                      data-target="#salePointFormModal"
                      v-if="permission.includes('sale-point create')"
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
                      <th>{{ $t("global.Counter") }}</th>
                      <th>{{ $t("global.Price") }}</th>
                      <th>{{ $t("global.Status") }}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="salePoints.length">
                    <tr
                      v-for="(salePoint, index) in salePoints"
                      :key="salePoint.id"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>{{ salePoint.name }}</td>
                      <td>{{ salePoint.counter }}</td>
                      <td>{{ salePoint.price }}</td>
                      <td>
                        <button
                          class="active"
                          :disabled="!permission.includes('sale-point edit')"
                          href="#"
                          @click="
                            toggleActivation(
                              salePoint.id,
                              salePoint.name,
                              salePoint.active,
                              index
                            )
                          "
                        >
                          <span
                            :class="[
                              parseInt(salePoint.active)
                                ? 'text-success hover'
                                : 'text-danger hover',
                            ]"
                            >{{
                              parseInt(salePoint.active)
                                ? $t("global.Active")
                                : $t("global.Inactive")
                            }}</span
                          >
                        </button>
                      </td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(salePoint, index)"
                          data-toggle="modal"
                          data-target="#salePointFormModal"
                          v-if="permission.includes('sale-point edit')"
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
      <Pagination :data="paginate" @pagination-change-page="getSalePoints">
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
import salePointStore from "./sale-point-store";
import salePointClient from "../../../http-clients/sale-point-client";
import SalePointForm from "./form";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
export default {
  components: {
    SalePointForm,
  },
  setup() {
    const data = reactive({
      paginate: {},
      salePoints: [],
      text: "",
      timeout: null,
      selectedSalePoint: null,
      selectedSalePointIndex: 0,
      page: 1,
      pageSize: 5,
      loading: false,
      mainCategories: [],
      clientGroups:[]
    });
    const { t, locale } = useI18n({});
    provide("sale_point_store", salePointStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedSalePoint = null;
      //Make little delay to ensure that watcher that found in salePoint form component
      // catch selectedSalePoint input prop
      setTimeout(() => {
        salePointStore.onFormShow = !salePointStore.onFormShow;
      }, 1);
    }
    function onEditClicked(salePoint, index) {
      data.selectedSalePoint = salePoint;
      data.selectedSalePointIndex = index;
      //Make little delay to ensure that watcher catch selectedSalePoint input prop
      //in salePoint form component
      setTimeout(() => {
        salePointStore.onFormShow = !salePointStore.onFormShow;
      }, 1);
    }
    function getSalePoints(page = 1) {
      data.page = page;
      data.loading = true;
      salePointClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.salePoints = response.data.data;
          data.paginate = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function toggleActivation(id, name, active, index) {
      Swal.fire({
        title: `${
          active ? t("global.AreYouSureInactive") : t("global.AreYouSureActive")
        }  (${name})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpToggleActivation(id, active, index);
        }
      });
    }
    function downloadExcelFile() {
      const _data = data.salePoints;
      const fileName = "salePoints";
      const exportType = exportFromJSON.types.csv;
      if (_data) exportFromJSON({ _data, fileName, exportType });
    }
    function onCreated(event) {
      data.salePoints.unshift(event);
    }
    function onUpdated(event) {
      data.salePoints[data.selectedSalePointIndex] = event;
      data.selectedSalePoint = null;
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getSalePoints();
      }, 500);
    }
    watch(
      () => data.text,
      () => {
        search();
      }
    );
    //Commons
    function getMainCategories() {
      data.loading = true;
      salePointClient
        .getMainCategories()
        .then((response) => {
          data.loading = false;
          data.mainCategories = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function getClientGroups() {
      data.loading = true;
      salePointClient
        .getClientGroups()
        .then((response) => {
          data.loading = false;
          data.clientGroups = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function httpToggleActivation(id, active, index) {
      salePointClient
        .toggleActivation(id)
        .then((res) => {
          Swal.fire({
            icon: "success",
            title: `${
              active
                ? t("global.InactiveSuccessfully")
                : t("global.ActiveSuccessfully")
            }`,
            showConfirmButton: false,
            timer: 1500,
          });
          data.salePoints[index]["active"] = active ? 0 : 1;
        })
        .catch((err) => {
          console.log(err.response);
          Swal.fire({
            icon: "error",
            title: `${t("global.ThereIsAnErrorInTheSystem")}`,
            text: `${t("global.YouCanNotModifyThisSafe")}`,
          });
        });
    }
    function created() {
      getSalePoints();
      getMainCategories();
      getClientGroups();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getSalePoints,
      downloadExcelFile,
      onCreated,
      onUpdated,
      search,
      toggleActivation,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.sale-point-container {
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