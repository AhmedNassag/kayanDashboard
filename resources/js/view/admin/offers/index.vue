<template>
  <div
    class="offer-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <OfferForm
      :selectedOffer="selectedOffer"
      @created="onCreated"
      @updated="onUpdated"
      @loading="loading = $event"
    />
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.Offers") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Offers") }}</li>
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
                      data-target="#offerFormModal"
                      v-if="permission.includes('offer create')"
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
                      <th>{{ $t("global.Discount") }}</th>
                      <th>{{ $t("global.Status") }}</th>
                      <th>{{ $t("global.StartDate") }}</th>
                      <th>{{ $t("global.EndDate") }}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="offers.length">
                    <tr v-for="(offer, index) in offers" :key="offer.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ offer.name }}</td>
                      <td>
                        {{ offer.discount + (offer.ratio ? "%" : "") }}
                      </td>
                      <td>
                        <button
                          class="active"
                          :disabled="!permission.includes('offer edit')"
                          href="#"
                          @click="
                            toggleActivation(offer.id, offer.name, offer.active, index)
                          "
                        >
                          <span
                            :class="[
                              parseInt(offer.active)
                                ? 'text-success hover'
                                : 'text-danger hover',
                            ]"
                            >{{
                              parseInt(offer.active)
                                ? $t("global.Active")
                                : $t("global.Inactive")
                            }}</span
                          >
                        </button>
                      </td>
                      <td>{{ offer.start_date }}</td>
                      <td>{{ offer.end_date }}</td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(offer, index)"
                          data-toggle="modal"
                          data-target="#offerFormModal"
                          v-if="permission.includes('offer edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                        <a
                          href="#"
                          @click="deleteOffer(offer, index)"
                          data-bs-target="#staticBackdrop"
                          v-if="permission.includes('offer delete')"
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
      <Pagination :data="paginate" @pagination-change-page="getOffers">
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
import offerStore from "./offer-store";
import offerClient from "../../../http-clients/offer-client";
import OfferForm from "./form";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
export default {
  components: {
    OfferForm,
  },
  setup() {
    const data = reactive({
      paginate: {},
      offers: [],
      text: "",
      timeout: null,
      selectedOffer: null,
      selectedOfferIndex: 0,
      page: 1,
      pageSize: 5,
      loading: false,
    });
    const { t, locale } = useI18n({});
    provide("offer_store", offerStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedOffer = null;
      //Make little delay to ensure that watcher that found in offer form component
      // catch selectedOffer input prop
      setTimeout(() => {
        offerStore.onFormShow = !offerStore.onFormShow;
      }, 1);
    }
    function onEditClicked(offer, index) {
      data.selectedOffer = offer;
      data.selectedOfferIndex = index;
      //Make little delay to ensure that watcher catch selectedOffer input prop
      //in offer form component
      setTimeout(() => {
        offerStore.onFormShow = !offerStore.onFormShow;
      }, 1);
    }
    function getOffers(page = 1) {
      data.page = page;
      data.loading = true;
      offerClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.offers = response.data.data;
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
      const _data = data.offers;
      const fileName = "Offers";
      const exportType = exportFromJSON.types.csv;
      if (_data) exportFromJSON({ _data, fileName, exportType });
    }
    function onCreated(event) {
      data.offers.unshift(event);
    }
    function onUpdated(event) {
      data.offers[data.selectedOfferIndex] = event;
      data.selectedOffer = null;
    }
    function deleteOffer(offer, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")} (${offer.name})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(offer, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getOffers();
      }, 500);
    }
    watch(
      () => data.text,
      () => {
        search();
      }
    );

    //Commons
    function httpDeleteRequest(offer, index) {
      data.loading = true;
      offerClient
        .delete(offer.id)
        .then((response) => {
          data.loading = false;
          data.offers.splice(index, 1);
          if (data.offers.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getOffers(data.page);
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
      offerClient
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
          data.offers[index]["active"] = active ? 0 : 1;
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
      getOffers();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getOffers,
      downloadExcelFile,
      onCreated,
      onUpdated,
      deleteOffer,
      search,
      toggleActivation,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.offer-container {
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
  }
}
</style>
