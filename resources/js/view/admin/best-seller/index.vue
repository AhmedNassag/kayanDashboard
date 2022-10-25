<template>
  <div
    class="best-seller-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <SettingsForm :bestSellerSettings="bestSellerSettings" @loading="loading = $event" />
    <BestSellerProductForm
      :selectedBestSellerProduct="selectedBestSellerProduct"
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
            <h3 class="page-title">{{ $t("global.BestSellers") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.BestSellers") }}</li>
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
                      v-if="permission.includes('best-seller create')"
                      class="btn btn-custom btn-warning"
                    >
                      {{ $t("global.Settings") }}
                    </button>
                    <button
                      @click="onAddClicked()"
                      data-toggle="modal"
                      data-target="#bestSellerProductFormModal"
                      v-if="permission.includes('best-seller create')"
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
                  <tbody v-if="bestSellerProducts.length">
                    <tr
                      v-for="(bestSellerProduct, index) in bestSellerProducts"
                      :key="bestSellerProduct.id"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>{{ bestSellerProduct.product.nameAr }}</td>
                      <td>{{ bestSellerProduct.product.nameEn }}</td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(bestSellerProduct, index)"
                          data-toggle="modal"
                          data-target="#bestSellerProductFormModal"
                          v-if="permission.includes('best-seller edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                        <a
                          href="#"
                          @click="deleteBestSellerProduct(bestSellerProduct, index)"
                          data-bs-target="#staticBackdrop"
                          v-if="permission.includes('best-seller delete')"
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
      <Pagination :data="paginate" @pagination-change-page="getBestSellerProducts">
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
import BestSellerProductForm from "./bestSellerProductForm";
import { useStore } from "vuex";
import { computed, provide, watch } from "@vue/runtime-core";
import bestSellerClient from "../../../http-clients/best-seller-client";
import bestSellerStore from "./best-seller-store";
import { useI18n } from "vue-i18n";
export default {
  components: {
    SettingsForm,
    BestSellerProductForm,
  },
  setup() {
    const { t, locale } = useI18n({});
    let data = reactive({
      loading: false,
      bestSellerSettings: null,
      bestSellerProducts: [],
      products: [],
      selectedBestSellerIndex: 0,
      selectedBestSellerProduct: null,
      page: 1,
      pageSize: 5,
      paginate: {},
      text: "",
      timeout: null,
    });
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    provide("best_seller_store", bestSellerStore);
    //OnCreated
    getBestSellerProducts();
    getProducts();
    //OnCreated

    //Methods
    function onCreated(event) {
      data.bestSellerProducts.unshift(event);
    }
    function onUpdated(event) {
      data.bestSellerProducts[data.selectedBestSellerIndex] = event;
      data.selectedBestSellerProduct = null;
    }
    function onAddClicked() {
      data.selectedBestSellerProduct = null;
      //Make little delay to ensure that watcher that found in bestSeller form component
      // catch selectedBestSellerProduct input prop
      setTimeout(() => {
        bestSellerStore.onFormShow = !bestSellerStore.onFormShow;
      }, 1);
    }
    function onEditClicked(bestSeller, index) {
      data.selectedBestSellerProduct = bestSeller;
      data.selectedBestSellerIndex = index;
      //Make little delay to ensure that watcher catch selectedBestSellerProduct input prop
      //in bestSeller form component
      setTimeout(() => {
        bestSellerStore.onFormShow = !bestSellerStore.onFormShow;
      }, 1);
    }
    function deleteBestSellerProduct(bestSellerProduct, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${bestSellerProduct.product.nameAr})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(bestSellerProduct, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getBestSellerProducts();
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
    function httpDeleteRequest(bestSellerProduct, index) {
      data.loading = true;
      bestSellerClient
        .delete(bestSellerProduct.id)
        .then((response) => {
          data.loading = false;
          data.bestSellerProducts.splice(index, 1);
          if (data.bestSellerProducts.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getBestSellerProducts(data.page);
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
      bestSellerClient.getProducts().then((response) => {
        data.loading = false;
        data.products = response.data;
      });
    }
    function getBestSellerProducts(page = 1) {
      data.page = page;
      data.loading = true;
      bestSellerClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.paginate = response.data;
          data.bestSellerProducts = response.data.data;
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
      getBestSellerProducts,
      deleteBestSellerProduct,
    };
  },
};
</script>

<style lang="scss" scoped>
.best-seller-container {
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
