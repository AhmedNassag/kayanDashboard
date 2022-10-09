<template>
  <div
    class="simple-advertise-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <SimpleAdvertiseForm
      :selectedSimpleAdvertise="selectedSimpleAdvertise"
      :products="products"
      @created="onCreated"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.SimpleAdvertises") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.SimpleAdvertises") }}</li>
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
                      @click="onAddClicked"
                      data-toggle="modal"
                      data-target="#simpleAdvertiseFormModal"
                      v-if="permission.includes('simple-advertise create')"
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
                      <th>{{ $t("global.Image") }}</th>
                      <th>{{ $t("global.Title") }}</th>
                      <th>{{ $t("global.External") }}</th>
                      <th>{{ $t("global.Url") }}</th>
                      <th>{{ $t("global.ProductNameAr") }}</th>
                      <th>{{ $t("global.ProductNameEn") }}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="simpleAdvertises.length">
                    <tr
                      v-for="(simpleAdvertise, index) in simpleAdvertises"
                      :key="simpleAdvertise.id"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>
                        <img :src="`/upload/${simpleAdvertise.image}`" />
                      </td>
                      <td>{{ simpleAdvertise.title }}</td>
                      <td>
                        {{ $t(simpleAdvertise.external ? "global.Yeas" : "global.No") }}
                      </td>
                      <td>{{ simpleAdvertise.external ? simpleAdvertise.url : "" }}</td>
                      <td>
                        {{
                          !simpleAdvertise.external
                            ? simpleAdvertise.product.nameAr
                            : ""
                        }}
                      </td>
                      <td>
                        {{
                          !simpleAdvertise.external
                            ? simpleAdvertise.product.nameEn
                            : ""
                        }}
                      </td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(simpleAdvertise, index)"
                          data-toggle="modal"
                          data-target="#simpleAdvertiseFormModal"
                          v-if="permission.includes('simple-advertise edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                        <a
                          href="#"
                          @click="deleteSimpleAdvertise(simpleAdvertise, index)"
                          data-bs-target="#staticBackdrop"
                          v-if="permission.includes('simple-advertise delete')"
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
      <Pagination :data="paginate" @pagination-change-page="getSimpleAdvertises">
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
import simpleAdvertiseStore from "./simple-advertise-store";
import simpleAdvertiseClient from "../../../http-clients/simple-advertise-client";
import SimpleAdvertiseForm from "./form";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
export default {
  components: {
    SimpleAdvertiseForm,
  },
  setup() {
    const data = reactive({
      paginate: {},
      simpleAdvertises: [],
      text: "",
      timeout: null,
      selectedSimpleAdvertise: null,
      selectedSimpleAdvertiseIndex: 0,
      page: 1,
      pageSize: 5,
      loading: false,
      products: [],
    });
    const { t, locale } = useI18n({});
    provide("simple_advertise_store", simpleAdvertiseStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedSimpleAdvertise = null;
      //Make little delay to ensure that watcher that found in simpleAdvertise form component
      // catch selectedSimpleAdvertise input prop
      setTimeout(() => {
        simpleAdvertiseStore.onFormShow = !simpleAdvertiseStore.onFormShow;
      }, 1);
    }
    function onEditClicked(simpleAdvertise, index) {
      data.selectedSimpleAdvertise = simpleAdvertise;
      data.selectedSimpleAdvertiseIndex = index;
      //Make little delay to ensure that watcher catch selectedSimpleAdvertise input prop
      //in simpleAdvertise form component
      setTimeout(() => {
        simpleAdvertiseStore.onFormShow = !simpleAdvertiseStore.onFormShow;
      }, 1);
    }
    function getSimpleAdvertises(page = 1) {
      data.page = page;
      data.loading = true;
      simpleAdvertiseClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.simpleAdvertises = response.data.data;
          data.paginate = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function getProducts() {
      data.loading = true;
      simpleAdvertiseClient.getProducts().then((response) => {
        data.products = response.data;
      });
    }
    function onCreated(event) {
      data.simpleAdvertises.unshift(event);
    }
    function onUpdated(event) {
      data.simpleAdvertises[data.selectedSimpleAdvertiseIndex] = event;
      data.selectedSimpleAdvertise = null;
    }
    function deleteSimpleAdvertise(simpleAdvertise, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${simpleAdvertise.title})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(simpleAdvertise, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getSimpleAdvertises();
      }, 500);
    }
    watch(
      () => data.text,
      () => {
        search();
      }
    );

    //Commons
    function httpDeleteRequest(simpleAdvertise, index) {
      data.loading = true;
      simpleAdvertiseClient
        .delete(simpleAdvertise.id)
        .then((response) => {
          data.loading = false;
          data.simpleAdvertises.splice(index, 1);
          if (data.simpleAdvertises.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getSimpleAdvertises(data.page);
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
      getSimpleAdvertises();
      getProducts();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getSimpleAdvertises,
      onCreated,
      onUpdated,
      deleteSimpleAdvertise,
      search,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.simple-advertise-container {
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
    table {
      td {
        img {
          width: 100px;
          height: 60px;
        }
      }
    }
  }
}
</style>
