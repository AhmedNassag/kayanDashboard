<template>
  <div
    class="most-popular-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <SettingsForm :mostPopularSettings="mostPopularSettings" @loading="loading = $event" />
    <MostPopularForm
      :selectedMostPopularProduct="selectedMostPopularProduct"
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
            <h3 class="page-title">{{ $t("global.MostPopular") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.MostPopular") }}</li>
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
                      data-toggle="modal"
                      data-target="#settingsFormModal"
                      v-if="permission.includes('most-popular create')"
                      class="btn btn-custom btn-warning"
                    >
                      {{ $t("global.Settings") }}
                    </button>
                    <button
                      @click="onAddClicked()"
                      data-toggle="modal"
                      data-target="#mostPopularFormModal"
                      v-if="permission.includes('most-popular create')"
                      class="btn btn-custom btn-warning ml-3"
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
                      <th>{{ $t("global.ProductNameAr") }}</th>
                      <th>{{ $t("global.ProductNameEn") }}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="mostPopularProducts.length">
                    <tr
                      v-for="(mostPopularProduct, index) in mostPopularProducts"
                      :key="mostPopularProduct.id"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>{{ mostPopularProduct.product.nameAr }}</td>
                      <td>{{ mostPopularProduct.product.nameEn }}</td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(mostPopularProduct, index)"
                          data-toggle="modal"
                          data-target="#mostPopularFormModal"
                          v-if="permission.includes('most-popular edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                        <a
                          href="#"
                          @click="deleteMostPopularProduct(mostPopularProduct, index)"
                          data-bs-target="#staticBackdrop"
                          v-if="permission.includes('most-popular delete')"
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
      <Pagination :data="paginate" @pagination-change-page="getMostPopularProducts">
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
import SettingsForm from "./settingsForm";
import MostPopularForm from "./mostPopularForm";
import { useStore } from "vuex";
import { computed, provide, watch } from "@vue/runtime-core";
import mostPopularClient from "../../../http-clients/most-popular-client";
import mostPopularStore from "./most-popular-store";
import { useI18n } from "vue-i18n";
export default {
  components: {
    SettingsForm,
    MostPopularForm,
  },
  setup() {
    const { t, locale } = useI18n({});
    let data = reactive({
      loading: false,
      mostPopularSettings: null,
      mostPopularProducts: [],
      products: [],
      selectedMostPopularIndex: 0,
      selectedMostPopularProduct: null,
      page: 1,
      pageSize: 5,
      paginate: {},
      text: "",
      timeout: null,
    });
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    provide("most_popular_store", mostPopularStore);
    //OnCreated
    getMostPopularProducts();
    getProducts();
    //OnCreated

    //Methods
    function onCreated(event) {
      data.mostPopularProducts.unshift(event);
    }
    function onUpdated(event) {
      data.mostPopularProducts[data.selectedMostPopularIndex] = event;
      data.selectedMostPopularProduct = null;
    }
    function onAddClicked() {
      data.selectedMostPopularProduct = null;
      //Make little delay to ensure that watcher that found in mostPopular form component
      // catch selectedMostPopularProduct input prop
      setTimeout(() => {
        mostPopularStore.onFormShow = !mostPopularStore.onFormShow;
      }, 1);
    }
    function onEditClicked(mostPopular, index) {
      data.selectedMostPopularProduct = mostPopular;
      data.selectedMostPopularIndex = index;
      //Make little delay to ensure that watcher catch selectedMostPopularProduct input prop
      //in mostPopular form component
      setTimeout(() => {
        mostPopularStore.onFormShow = !mostPopularStore.onFormShow;
      }, 1);
    }
    function deleteMostPopularProduct(mostPopularProduct, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${mostPopularProduct.product.nameAr})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(mostPopularProduct, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getMostPopularProducts();
      }, 500);
    }
    //Watchers
    watch(
      () => data.text,
      () => {
        search();
      }
    );
    //Commons
    function httpDeleteRequest(mostPopularProduct, index) {
      data.loading = true;
      mostPopularClient
        .delete(mostPopularProduct.id)
        .then((response) => {
          data.loading = false;
          data.mostPopularProducts.splice(index, 1);
          if (data.mostPopularProducts.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getMostPopularProducts(data.page);
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

    function getProducts() {
      data.loading = true;
      mostPopularClient.getProducts().then((response) => {
        data.loading = false;
        data.products = response.data;
      });
    }
    function getMostPopularProducts(page = 1) {
      data.page = page;
      data.loading = true;
      mostPopularClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.paginate = response.data;
          data.mostPopularProducts = response.data.data;
          data.loading = false;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }

    return {
      ...toRefs(data),
      permission,
      onCreated,
      onUpdated,
      onAddClicked,
      onEditClicked,
      getMostPopularProducts,
      deleteMostPopularProduct,
    };
  },
};
</script>

<style lang="scss" scoped>
.most-popular-container {
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
