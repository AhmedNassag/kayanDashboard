<template>
  <div
    class="unit-container"
    :class="[
      'page-wrapper',
      this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
  ]"
  >
    <UnitForm
      :selectedUnit="selectedUnit"
      @created="onCreated"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.Units") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Units") }}</li>
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
                      data-target="#unitFormModal"
                      v-if="permission.includes('unit create')"
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
                      <th>{{ $t("global.Date") }}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="units.length">
                    <tr v-for="(unit, index) in units" :key="unit.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ unit.name }}</td>
                      <td>{{ unit.created_at }}</td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(unit, index)"
                          data-toggle="modal"
                          data-target="#unitFormModal"
                          v-if="permission.includes('unit edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                        <a
                          href="#"
                          @click="deleteUnit(unit, index)"
                          data-bs-target="#staticBackdrop"
                          v-if="permission.includes('unit delete')"
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
      <Pagination :data="paginate" @pagination-change-page="getUnits">
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
import unitStore from "./unit-store";
import unitClient from "../../../http-clients/unit-client";
import UnitForm from "./form";
import { useI18n } from "vue-i18n";
import { useStore } from 'vuex';
export default {
  components: {
    UnitForm,
  },
  setup() {
    const data = reactive({
      paginate: {},
      units: [],
      text: "",
      timeout: null,
      selectedUnit: null,
      selectedUnitIndex: 0,
      page: 1,
      pageSize: 5,
      loading: false,
    });
    const { t, locale } = useI18n({});
    provide("unit_store", unitStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedUnit = null;
      //Make little delay to ensure that watcher that found in unit form component
      // catch selectedUnit input prop
      setTimeout(() => {
        unitStore.onFormShow = !unitStore.onFormShow;
      }, 1);
    }
    function onEditClicked(unit, index) {
      data.selectedUnit = unit;
      data.selectedUnitIndex = index;
      //Make little delay to ensure that watcher catch selectedUnit input prop
      //in unit form component
      setTimeout(() => {
        unitStore.onFormShow = !unitStore.onFormShow;
      }, 1);
    }
    function getUnits(page = 1) {
      data.page = page;
      data.loading = true;
      unitClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.units = response.data.data;
          data.paginate = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function downloadExcelFile() {
      const _data = data.units;
      const fileName = "Units";
      const exportType = exportFromJSON.types.csv;
      if (_data) exportFromJSON({ _data, fileName, exportType });
    }
    function onCreated(event) {
      data.units.unshift(event);
    }
    function onUpdated(event) {
      data.units[data.selectedUnitIndex] = event;
      data.selectedUnit = null;
    }
    function deleteUnit(unit, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${unit.name})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(unit, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getUnits();
      }, 500);
    }
    watch(
      () => data.text,
      () => {
        search();
      }
    );

    //Commons
    function httpDeleteRequest(unit, index) {
      data.loading = true;
      unitClient
        .delete(unit.id)
        .then((response) => {
          data.loading = false;
          data.units.splice(index, 1);
          if (data.units.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getUnits(data.page);
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
      getUnits();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getUnits,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deleteUnit,
      search,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.unit-container {
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
