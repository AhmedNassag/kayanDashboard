<template>
  <div
    class="supplier-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <SupplierForm
      :selectedSupplier="selectedSupplier"
      @created="onCreated"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.Suppliers") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">
                {{ $t("global.Suppliers") }}
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
                      data-target="#supplierFormModal"
                      v-if="permission.includes('supplier create')"
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
                      <th class="text-center">#</th>
                      <th class="text-center">{{ $t("global.Name") }}</th>
                      <th class="text-center">{{ $t("global.Type") }}</th>
                      <th class="text-center">{{ $t("global.Address") }}</th>
                      <th class="text-center">{{ $t("global.Phone") }}</th>
                      <th class="text-center">{{ $t("global.IsKayanSupplier") }}</th>
                      <th class="text-center">{{ $t("global.Status") }}</th>
                      <th class="text-center">{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="suppliers.length">
                    <tr v-for="(supplier, index) in suppliers" :key="supplier.id">
                      <td class="text-center">{{ index + 1 }}</td>
                      <td class="text-center">{{ supplier.name }}</td>
                      <td class="text-center" v-if="supplier.type == 'STORE'">{{ $t("global.store") }}</td>
                      <td class="text-center" v-else>{{ $t("global.company") }}</td>
                      <td class="text-center">{{ supplier.address }}</td>
                      <td class="text-center">{{ supplier.phone }}</td>
                      <td class="text-center">
                        <span class="badge badge-primary" v-if="supplier.is_our_supplier">
                          {{ $t("global.IsKayanSupplier") }}
                        </span>
                      </td>
                      <td class="text-center">
                        <button
                          class="active"
                          :disabled="!permission.includes('supplier edit')"
                          href="#"
                          @click="
                            toggleActivation(
                              supplier.id,
                              supplier.name,
                              supplier.active,
                              index
                            )
                          "
                        >
                          <span
                            :class="[
                              parseInt(supplier.active)
                                ? 'text-success hover'
                                : 'text-danger hover',
                            ]"
                            >{{
                              parseInt(supplier.active)
                                ? $t("global.Active")
                                : $t("global.Inactive")
                            }}</span
                          >
                        </button>
                      </td>
                      <td class="text-center">
                        <router-link :to="{name:'supplier_profile',params:{id:supplier.id,lang:this.$i18n.locale}}"
                          data-toggle="modal"

                          class="btn btn-sm btn-info me-2"
                        >
                          <i class="far fa-eye"></i>
                        </router-link>
                        <a
                          href="#"
                          @click="onEditClicked(supplier, index)"
                          data-toggle="modal"
                          data-target="#supplierFormModal"
                          v-if="permission.includes('supplier edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                        <a
                          href="#"
                          @click="deleteSupplier(supplier, index)"
                          data-bs-target="#staticBackdrop"
                          v-if="permission.includes('supplier delete')"
                          class="btn btn-sm btn-danger me-2"
                        >
                          <i class="far fa-trash-alt"></i>
                        </a>
                        <!---->
                        <!-- <router-link
                          v-if="permission.includes('virtualStock create')"
                          :to="{
                            name: 'createVirtualStock',
                            params: { id: supplier.id },
                          }"
                          class="btn btn-sm btn-info me-2"
                        >
                          <i class="far fa-edit"></i>
                          {{ $t("global.Create Virtual Stock") }}
                        </router-link> -->
                        <!---->
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
      <Pagination :data="paginate" @pagination-change-page="getSuppliers">
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
import supplierStore from "./supplier-store";
import supplierClient from "../../../http-clients/supplier-client";
import SupplierForm from "./form";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { useRouter } from 'vue-router'

export default {
  components: {
    SupplierForm,
  },
  setup() {
    const data = reactive({
      paginate: {},
      suppliers: [],
      text: "",
      timeout: null,
      selectedSupplier: null,
      selectedSupplierIndex: 0,
      page: 1,
      pageSize: 5,
      loading: false,
    });
    const { t, locale } = useI18n({});
    provide("supplier_store", supplierStore);
    let store = useStore();
    let router = useRouter();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedSupplier = null;
      //Make little delay to ensure that watcher that found in supplier form component
      // catch selectedSupplier input prop
      setTimeout(() => {
        supplierStore.onFormShow = !supplierStore.onFormShow;
      }, 1);
    }
    function onEditClicked(supplier, index) {
      data.selectedSupplier = supplier;
      data.selectedSupplierIndex = index;
      //Make little delay to ensure that watcher catch selectedSupplier input prop
      //in supplier form component
      setTimeout(() => {
        supplierStore.onFormShow = !supplierStore.onFormShow;
      }, 1);
    }
    function getSuppliers(page = 1) {
      data.page = page;
      data.loading = true;
      supplierClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.suppliers = response.data.data;
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
      const _data = data.suppliers;
      const fileName = "suppliers";
      const exportType = exportFromJSON.types.csv;
      if (_data) exportFromJSON({ _data, fileName, exportType });
    }
    function onCreated(event) {
      data.suppliers.unshift(event);
    }
    function onUpdated(event) {
      data.suppliers[data.selectedSupplierIndex] = event;
      data.selectedSupplier = null;
    }
    function deleteSupplier(supplier, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${supplier.name})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(supplier, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getSuppliers();
      }, 500);
    }
    watch(
      () => data.text,
      () => {
        search();
      }
    );
    //Commons
    function httpDeleteRequest(supplier, index) {
      data.loading = true;
      supplierClient
        .delete(supplier.id)
        .then((response) => {
          data.loading = false;
          data.suppliers.splice(index, 1);
          if (data.suppliers.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getSuppliers(data.page);
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
      supplierClient
        .toggleActivation(id)
        .then((res) => {
          Swal.fire({
            icon: "success",
            title: `${
              active ? t("global.InactiveSuccessfully") : t("global.ActiveSuccessfully")
            }`,
            showConfirmButton: false,
            timer: 1500,
          });
          data.suppliers[index]["active"] = active ? 0 : 1;
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
      getSuppliers();
    }


    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getSuppliers,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deleteSupplier,
      search,
      toggleActivation,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.supplier-container {
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
