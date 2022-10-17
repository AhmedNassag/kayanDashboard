<template>
  <div
    class="shipping-container"
    :class="[
      'page-wrapper',
      this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
    ]"
  >
    <shippingForm
      :selectedShipping="selectedShipping"
      @created="onCreated"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.Shipping") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">
                {{ $t("global.Shipping") }}
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
                      data-target="#shippingFormModal"
                      v-if="permission.includes('shipping create')"
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
                      <th>{{ $t("global.Status") }}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="shippings.length">
                    <tr v-for="(shipping, index) in shippings" :key="shipping.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ shipping.name }}</td>
                      <td>
                        <button
                          class="active"
                          :disabled="!permission.includes('shipping edit')"
                          href="#"
                          @click="
                            toggleActivation(
                              shipping.id,
                              shipping.name,
                              shipping.active,
                              index
                            )
                          "
                        >
                          <span
                            :class="[
                              parseInt(shipping.active)
                                ? 'text-success hover'
                                : 'text-danger hover',
                            ]"
                            >{{
                              parseInt(shipping.active)
                                ? $t("global.Active")
                                : $t("global.Inactive")
                            }}</span
                          >
                        </button>
                      </td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(shipping, index)"
                          data-toggle="modal"
                          data-target="#shippingFormModal"
                          v-if="permission.includes('shipping edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                        <a
                          href="#"
                          @click="deleteShipping(shipping, index)"
                          data-bs-target="#staticBackdrop"
                          v-if="permission.includes('shipping delete')"
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
      <Pagination :data="paginate" @pagination-change-page="getShippings">
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
import shippingStore from "./shipping-store";
import shippingClient from "../../../http-clients/shipping-client";
import shippingForm from "./form";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
export default {
  components: {
    shippingForm,
  },
  setup() {
    const data = reactive({
      paginate: {},
      shippings: [],
      text: "",
      timeout: null,
      selectedShipping: null,
      selectedShippingIndex: 0,
      page: 1,
      pageSize: 5,
      loading: false,
    });
    const { t, locale } = useI18n({});
    provide("shipping_store", shippingStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedShipping = null;
      //Make little delay to ensure that watcher that found in shipping form component
      // catch selectedShipping input prop
      setTimeout(() => {
        shippingStore.onFormShow = !shippingStore.onFormShow;
      }, 1);
    }
    function onEditClicked(shipping, index) {
      data.selectedShipping = shipping;
      data.selectedShippingIndex = index;
      //Make little delay to ensure that watcher catch selectedShipping input prop
      //in shipping form component
      setTimeout(() => {
        shippingStore.onFormShow = !shippingStore.onFormShow;
      }, 1);
    }
    function getShippings(page = 1) {
      data.page = page;
      data.loading = true;
      shippingClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.shippings = response.data.data;
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
      const _data = data.shippings;
      const fileName = "shippings";
      const exportType = exportFromJSON.types.csv;
      if (_data) exportFromJSON({ _data, fileName, exportType });
    }
    function onCreated(event) {
      data.shippings.unshift(event);
    }
    function onUpdated(event) {
      data.shippings[data.selectedShippingIndex] = event;
      data.selectedShipping = null;
    }
    function deleteShipping(shipping, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${shipping.name})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(shipping, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getShippings();
      }, 500);
    }
    watch(
      () => data.text,
      () => {
        search();
      }
    );

    //Commons
    function httpDeleteRequest(shipping, index) {
      data.loading = true;
      shippingClient
        .delete(shipping.id)
        .then((response) => {
          data.loading = false;
          data.shippings.splice(index, 1);
          if (data.shippings.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getShippings(data.page);
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
    function httpToggleActivation(id, active, index) {
      shippingClient
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
          data.shippings[index]["active"] = active ? 0 : 1;
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
      getShippings();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getShippings,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deleteShipping,
      search,
      toggleActivation,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.shipping-container {
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
    .active {
      background: none;
      border: none;
    }
  }
}
</style>
