<template>
  <div
    class="also-bought-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <SettingsForm :alsoBoughtSettings="alsoBoughtSettings" @loading="loading = $event" />
    <AlsoBoughtForm
      :selectedAlsoBoughtProduct="selectedAlsoBoughtProduct"
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
            <h3 class="page-title">{{ $t("global.CustomerAlsoBought") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">
                {{ $t("global.CustomerAlsoBought") }}
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
                      data-toggle="modal"
                      data-target="#settingsFormModal"
                      v-if="permission.includes('also-bought create')"
                      class="btn btn-custom btn-warning"
                    >
                      {{ $t("global.Settings") }}
                    </button>
                    <button
                      @click="onAddClicked()"
                      data-toggle="modal"
                      data-target="#alsoBoughtFormModal"
                      v-if="permission.includes('also-bought create')"
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
                  <tbody v-if="alsoBoughtProducts.length">
                    <tr
                      v-for="(alsoBoughtProduct, index) in alsoBoughtProducts"
                      :key="alsoBoughtProduct.id"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>{{ alsoBoughtProduct.product.nameAr }}</td>
                      <td>{{ alsoBoughtProduct.product.nameEn }}</td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(alsoBoughtProduct, index)"
                          data-toggle="modal"
                          data-target="#alsoBoughtFormModal"
                          v-if="permission.includes('also-bought edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                        <a
                          href="#"
                          @click="deleteAlsoBoughtProduct(alsoBoughtProduct, index)"
                          data-bs-target="#staticBackdrop"
                          v-if="permission.includes('also-bought delete')"
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
      <Pagination :data="paginate" @pagination-change-page="getAlsoBoughtProducts">
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
import AlsoBoughtForm from "./alsoBoughtForm";
import { useStore } from "vuex";
import { computed, provide, watch } from "@vue/runtime-core";
import alsoBoughtClient from "../../../http-clients/also-bought-client";
import alsoBoughtStore from "./also-bought-store";
import { useI18n } from "vue-i18n";
export default {
  components: {
    SettingsForm,
    AlsoBoughtForm,
  },
  setup() {
    const { t, locale } = useI18n({});
    let data = reactive({
      loading: false,
      alsoBoughtSettings: null,
      alsoBoughtProducts: [],
      products: [],
      selectedAlsoBoughtIndex: 0,
      selectedAlsoBoughtProduct: null,
      page: 1,
      pageSize: 5,
      paginate: {},
      text: "",
      timeout: null,
    });
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    provide("also_bought_store", alsoBoughtStore);
    //OnCreated
    getAlsoBoughtProducts();
    getProducts();
    //OnCreated

    //Methods
    function onCreated(event) {
      data.alsoBoughtProducts.unshift(event);
    }
    function onUpdated(event) {
      data.alsoBoughtProducts[data.selectedAlsoBoughtIndex] = event;
      data.selectedAlsoBoughtProduct = null;
    }
    function onAddClicked() {
      data.selectedAlsoBoughtProduct = null;
      //Make little delay to ensure that watcher that found in alsoBought form component
      // catch selectedAlsoBoughtProduct input prop
      setTimeout(() => {
        alsoBoughtStore.onFormShow = !alsoBoughtStore.onFormShow;
      }, 1);
    }
    function onEditClicked(alsoBought, index) {
      data.selectedAlsoBoughtProduct = alsoBought;
      data.selectedAlsoBoughtIndex = index;
      //Make little delay to ensure that watcher catch selectedAlsoBoughtProduct input prop
      //in alsoBought form component
      setTimeout(() => {
        alsoBoughtStore.onFormShow = !alsoBoughtStore.onFormShow;
      }, 1);
    }
    function deleteAlsoBoughtProduct(alsoBoughtProduct, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${alsoBoughtProduct.product.nameAr})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(alsoBoughtProduct, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getAlsoBoughtProducts();
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
    function httpDeleteRequest(alsoBoughtProduct, index) {
      data.loading = true;
      alsoBoughtClient
        .delete(alsoBoughtProduct.id)
        .then((response) => {
          data.loading = false;
          data.alsoBoughtProducts.splice(index, 1);
          if (data.alsoBoughtProducts.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getAlsoBoughtProducts(data.page);
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
      alsoBoughtClient.getProducts().then((response) => {
        data.loading = false;
        data.products = response.data;
      });
    }
    function getAlsoBoughtProducts(page = 1) {
      data.page = page;
      data.loading = true;
      alsoBoughtClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.paginate = response.data;
          data.alsoBoughtProducts = response.data.data;
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
      getAlsoBoughtProducts,
      deleteAlsoBoughtProduct,
    };
  },
};
</script>

<style lang="scss" scoped>
.also-bought-container {
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
