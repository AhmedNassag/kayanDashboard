<template>
  <div
    class="slider-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <SliderForm
      :selectedSlider="selectedSlider"
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
            <h3 class="page-title">{{ $t("global.Sliders") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Sliders") }}</li>
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
                      data-target="#sliderFormModal"
                      v-if="permission.includes('slider create')"
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
                  <tbody v-if="sliders.length">
                    <tr v-for="(slider, index) in sliders" :key="slider.id">
                      <td>{{ index + 1 }}</td>
                      <td>
                        <img :src="`/upload/${slider.image}`" />
                      </td>
                      <td>{{ slider.title }}</td>
                      <td>{{ $t(slider.external ? "global.Yeas" : "global.No") }}</td>
                      <td>{{ slider.external ? slider.url : "" }}</td>
                      <td>
                        {{ !slider.external ? slider.product.nameAr : "" }}
                      </td>
                      <td>
                        {{ !slider.external ? slider.product.nameEn : "" }}
                      </td>
                      <td>
                        <a
                          href="#"
                          @click="onEditClicked(slider, index)"
                          data-toggle="modal"
                          data-target="#sliderFormModal"
                          v-if="permission.includes('slider edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </a>
                        <a
                          href="#"
                          @click="deleteSlider(slider, index)"
                          data-bs-target="#staticBackdrop"
                          v-if="permission.includes('slider delete')"
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
      <Pagination :data="paginate" @pagination-change-page="getSliders">
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
import sliderStore from "./slider-store";
import sliderClient from "../../../http-clients/slider-client";
import SliderForm from "./form";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
export default {
  components: {
    SliderForm,
  },
  setup() {
    const data = reactive({
      paginate: {},
      sliders: [],
      text: "",
      timeout: null,
      selectedSlider: null,
      selectedSliderIndex: 0,
      page: 1,
      pageSize: 5,
      loading: false,
      products: [],
    });
    const { t, locale } = useI18n({});
    provide("slider_store", sliderStore);
    let store = useStore();
    let permission = computed(() => store.getters["authAdmin/permission"]);
    created();
    //Methods
    function onAddClicked() {
      data.selectedSlider = null;
      //Make little delay to ensure that watcher that found in slider form component
      // catch selectedSlider input prop
      setTimeout(() => {
        sliderStore.onFormShow = !sliderStore.onFormShow;
      }, 1);
    }
    function onEditClicked(slider, index) {
      data.selectedSlider = slider;
      data.selectedSliderIndex = index;
      //Make little delay to ensure that watcher catch selectedSlider input prop
      //in slider form component
      setTimeout(() => {
        sliderStore.onFormShow = !sliderStore.onFormShow;
      }, 1);
    }
    function getSliders(page = 1) {
      data.page = page;
      data.loading = true;
      sliderClient
        .getPage(data.page, data.pageSize, data.text)
        .then((response) => {
          data.loading = false;
          data.sliders = response.data.data;
          data.paginate = response.data;
        })
        .catch((error) => {
          console.log(error.response);
        });
    }
    function getProducts() {
      data.loading = true;
      sliderClient.getProducts().then((response) => {
        data.products = response.data;
        data.loading = false;
      });
    }
    function onCreated(event) {
      data.sliders.unshift(event);
    }
    function onUpdated(event) {
      data.sliders[data.selectedSliderIndex] = event;
      data.selectedSlider = null;
    }
    function deleteSlider(slider, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")}`,// (${slider.title})
        // text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpDeleteRequest(slider, index);
        }
      });
    }
    function search() {
      // clear timeout variable
      clearTimeout(data.timeout);
      data.timeout = setTimeout(() => {
        getSliders();
      }, 500);
    }
    watch(
      () => data.text,
      () => {
        search();
      }
    );

    //Commons
    function httpDeleteRequest(slider, index) {
      data.loading = true;
      sliderClient
        .delete(slider.id)
        .then((response) => {
          data.loading = false;
          data.sliders.splice(index, 1);
          if (data.sliders.length == 0) {
            if (data.page > 1) {
              data.page--;
            }
            getSliders(data.page);
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
      getSliders();
      getProducts();
    }
    return {
      ...toRefs(data),
      onAddClicked,
      onEditClicked,
      getSliders,
      onCreated,
      onUpdated,
      deleteSlider,
      search,
      permission,
    };
  },
};
</script>

<style lang="scss" scoped>
.slider-container {
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
