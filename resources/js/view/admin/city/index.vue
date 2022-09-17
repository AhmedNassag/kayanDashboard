<template>
  <div
    class="city-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <CityForm
      :selectedCity="selectedCity"
      @created="onCreated"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.Cities") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Cities") }}</li>
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
                      data-target="#cityFormModal"
                      v-if="permission.includes('city create')"
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
                      <th>{{ $t("global.Available") }}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="citys.length">
                    <tr v-for="(city, index) in citys" :key="city.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ city.name }}</td>
                      <td>{{ $t(city.available ? "global.Yeas" : "global.No") }}</td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(city, index)"
                          data-toggle="modal"
                          data-target="#cityFormModal"
                          v-if="permission.includes('city edit')"
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
      <Pagination :data="paginate" @pagination-change-page="getCities">
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
import cityStore from "./city-store";
import cityClient from "../../../http-clients/city-client";
import CityForm from "./form";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
export default {
  components: {
    CityForm,
  },
  setup() {
    const data = reactive({
      paginate: {},
      citys: [],
      text: "",
      timeout: null,
      selectedCity: null,
      selectedCityIndex: 0,
      page: 1,
      pageSize: 5,
      loading: false,
    });
    const { t, locale } = useI18n({});
    provide("city_store", cityStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedCity = null;
      //Make little delay to ensure that watcher that found in city form component
      // catch selectedCity input prop
      setTimeout(() => {
        cityStore.onFormShow = !cityStore.onFormShow;
      }, 1);
    }
    function onEditClicked(city, index) {
      data.selectedCity = city;
      data.selectedCityIndex = index;
      //Make little delay to ensure that watcher catch selectedCity input prop
      //in city form component
      setTimeout(() => {
        cityStore.onFormShow = !cityStore.onFormShow;
      }, 1);
    }
    function getCities(page = 1) {
      data.page = page;
      data.loading = true;
      cityClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.citys = response.data.data;
          data.paginate = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      const _data = data.citys;
      const fileName = "citys";
      const exportType = exportFromJSON.types.csv;
      if (_data) exportFromJSON({ _data, fileName, exportType });
    }
    function onCreated(event) {
      data.citys.unshift(event);
    }
    function onUpdated(event) {
      data.citys[data.selectedCityIndex] = event;
      data.selectedCity = null;
    }
    function deletecity(city, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${city.name})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(city, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getCities();
      }, 500);
    }
    watch(
      () => data.text,
      () => {
        search();
      }
    );

    //Commons
    function httpDeleteRequest(city, index) {
      data.loading = true;
      cityClient
        .delete(city.id)
        .then((response) => {
          data.loading = false;
          data.citys.splice(index, 1);
          if (data.citys.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getCities(data.page);
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
      getCities();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getCities,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deletecity,
      search,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.city-container {
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
