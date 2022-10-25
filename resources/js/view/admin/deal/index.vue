<template>
  <div
    class="deal-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <SettingsForm :dealSettings="dealSettings" @loading="loading = $event" />
    <DealPriceForm
      :selectedDealPrice="selectedDealPrice"
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
            <h3 class="page-title">{{ $t("global.Deals") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Deals") }}</li>
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
                      v-if="permission.includes('deal create')"
                      class="btn btn-custom btn-warning"
                    >
                      {{ $t("global.Settings") }}
                    </button>
                    <button
                      @click="onAddClicked()"
                      data-toggle="modal"
                      data-target="#dealPriceFormModal"
                      v-if="permission.includes('deal create')"
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
                      <th>{{ $t("global.Public Price") }}</th>
                      <th>{{ $t("global.Discount") }}</th>
                      <th>{{ $t("global.Pharmacy Price") }}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="dealPrices.length">
                    <tr v-for="(dealPrice, index) in dealPrices" :key="dealPrice.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ dealPrice.product.nameAr }}</td>
                      <td>{{ dealPrice.product.nameEn }}</td>
                      <td>{{ dealPrice.publicPrice }}</td>
                      <td>{{ dealPrice.clientDiscount }}%</td>
                      <td>{{ dealPrice.pharmacyPrice }}</td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(dealPrice, index)"
                          data-toggle="modal"
                          data-target="#dealPriceFormModal"
                          v-if="permission.includes('deal edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                        <a
                          href="#"
                          @click="deleteDealPrice(dealPrice, index)"
                          data-bs-target="#staticBackdrop"
                          v-if="permission.includes('deal delete')"
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
      <Pagination :data="paginate" @pagination-change-page="getDealPrices">
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
import DealPriceForm from "./dealPriceForm";
import { useStore } from "vuex";
import { computed, onMounted, provide, watch } from "@vue/runtime-core";
import dealClient from "../../../http-clients/deal-client";
import dealStore from "./deal-store";
import { useI18n } from "vue-i18n";
export default {
  components: {
    SettingsForm,
    DealPriceForm,
  },
  setup() {
    const { t, locale } = useI18n({});
    let data = reactive({
      loading: false,
      dealSettings: null,
      dealPrices: [],
      products: [],
      selectedDealPriceIndex: 0,
      selectedDealPrice: null,
      page: 1,
      pageSize: 5,
      paginate: {},
      text: "",
      timeout: null,
    });
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    provide("deal_store", dealStore);
    //OnCreated
    getDealPrices();
    getProducts();
    //OnCreated

    //Methods
    function onCreated(event) {
      data.dealPrices.unshift(event);
    }
    function onUpdated(event) {
      data.dealPrices[data.selectedDealPriceIndex] = event;
      data.selectedDealPrice = null;
    }
    function onAddClicked() {
      data.selectedDealPrice = null;
      //Make little delay to ensure that watcher that found in deal form component
      // catch selectedDealPrice input prop
      setTimeout(() => {
        dealStore.onFormShow = !dealStore.onFormShow;
      }, 1);
    }
    function onEditClicked(deal, index) {
      data.selectedDealPrice = deal;
      data.selectedDealPriceIndex = index;
      //Make little delay to ensure that watcher catch selectedDealPrice input prop
      //in deal form component
      setTimeout(() => {
        dealStore.onFormShow = !dealStore.onFormShow;
      }, 1);
    }
    function deleteDealPrice(dealPrice, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${dealPrice.product.nameAr})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(dealPrice, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getDealPrices();
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
    function httpDeleteRequest(dealPrice, index) {
      data.loading = true;
      dealClient
        .delete(dealPrice.id)
        .then((response) => {
          data.loading = false;
          data.dealPrices.splice(index, 1);
          if (data.dealPrices.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getDealPrices(data.page);
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
      dealClient.getProducts().then((response) => {
        data.loading = false;
        data.products = response.data;
      });
    }
    function getDealPrices(page = 1) {
      data.page = page;
      data.loading = true;
      dealClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.paginate = response.data;
          data.dealPrices = response.data.data;
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
      getDealPrices,
      deleteDealPrice,
    };
  },
};
</script>

<style lang="scss" scoped>
.deal-container {
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
