<template>
  <div
    class="area-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <AreaForm
      :selectedArea="selectedArea"
      :cities="cities"
      @created="onCreated"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.Areas") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Areas") }}</li>
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
                  <div class="col-3">
                    {{ $t("global.Search") }}:
                    <input type="search" v-model="text" class="custom" />
                  </div>
                  <div class="col-3">
                    {{ $t("global.City") }}:
                    <input type="search" v-model="city" class="custom" />
                  </div>
                  <div class="col-3">
                        <select v-model="area_filter" class="form-control" @change="getAreas">
                            <option value="">{{ $t('global.All Areas') }}</option>
                            <option value="most_ordered">{{ $t('global.Most Ordered') }}</option>
                            <option value="least_ordered">{{ $t('global.Least Ordered') }}</option>
                        </select>
                    </div>
                  <div class="col-3 text-center">
                    <button
                      @click="onAddClicked()"
                      data-toggle="modal"
                      data-target="#areaFormModal"
                      v-if="permission.includes('area create')"
                      class="btn btn-custom btn-warning"
                    >
                      {{ $t("global.Add") }}
                    </button>
                    <a
                        class="btn btn-sm btn-secondary mx-2"
                        @click.prevent="printSection()"
                        ><i class="fa fa-print"></i> </a
                    >
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table mb-0" id="div">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>{{ $t("global.Name") }}</th>
                      <th>{{ $t("global.City") }}</th>
                      <th>{{ $t("global.Number of Orders") }}</th>
                      <th>{{ $t("global.shipping_price")}}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="areas.length">
                    <tr v-for="(area, index) in areas" :key="area.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ area.name }}</td>
                      <td>{{ area.city.name }}</td>
                      <td>{{ area.number_of_orders }}</td>
                      <td>{{ area.shipping_price }}</td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(area, index)"
                          data-toggle="modal"
                          data-target="#areaFormModal"
                          v-if="permission.includes('area edit')"
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
      <Pagination :data="paginate" @pagination-change-page="getAreas">
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
import { reactive, toRefs ,ref } from "@vue/reactivity";
import { computed, provide, watch } from "@vue/runtime-core";
import areaStore from "./area-store";
import areaClient from "../../../http-clients/area-client";
import AreaForm from "./form";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
  components: {
    AreaForm,
  },
  setup() {
    const area_filter = ref('')
    const data = reactive({
      paginate: {},
      areas: [],
      cities: [],
      text: "",
      city: "",
      timeout: null,
      selectedArea: null,
      selectedAreaIndex: 0,
      page: 1,
      pageSize: 5,
      loading: false,
    });
    const { t, locale } = useI18n({});
    provide("area_store", areaStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedArea = null;
      //Make little delay to ensure that watcher that found in area form component
      // catch selectedArea input prop
      setTimeout(() => {
        areaStore.onFormShow = !areaStore.onFormShow;
      }, 1);
    }
    function onEditClicked(area, index) {
      data.selectedArea = area;
      data.selectedAreaIndex = index;
      //Make little delay to ensure that watcher catch selectedArea input prop
      //in area form component
      setTimeout(() => {
        areaStore.onFormShow = !areaStore.onFormShow;
      }, 1);
    }
    function getAreas(page = 1) {
      data.page = page;
      data.loading = true;
      adminApi
        .get(`/v1/dashboard/areas?page=${data.page}&page_size=${data.pageSize}&text=${data.text}&city=${data.city}&area_filter=${area_filter.value}`)
        .then((response) => {
          data.loading = false;
          data.areas = response.data.data;
          data.paginate = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      const _data = data.areas;
      const fileName = "areas";
      const exportType = exportFromJSON.types.csv;
      if (_data) exportFromJSON({ _data, fileName, exportType });
    }
    function onCreated(event) {
      data.areas.unshift(event);
    }
    function onUpdated(event) {
      data.areas[data.selectedAreaIndex] = event;
      data.selectedArea = null;
    }
    function deleteArea(area, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${area.name})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(area, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getAreas();
      }, 500);
    }
    watch(
      () => data.text,
      () => {
        search();
      }
    );
    function searchCity() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getAreas();
      }, 500);
    }
    watch(
      () => data.city,
      () => {
        searchCity();
      }
    );

    //Commons
    function getCities() {
      data.loading = true;
      areaClient.getCities().then((response) => {
        data.cities = response.data;
        data.loading = false;
      });
    }
    function httpDeleteRequest(area, index) {
      data.loading = true;
      areaClient
        .delete(area.id)
        .then((response) => {
          data.loading = false;
          data.areas.splice(index, 1);
          if (data.areas.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getAreas(data.page);
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
      getAreas();
      getCities();
    }

    let printSection = () => {
          $("#div").printThis({});
        }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getAreas,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deleteArea,
      search,
      printSection,
      area_filter,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.area-container {
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
