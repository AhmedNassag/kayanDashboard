<template>
    <div
      :class="[
        'page-wrapper',
        this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
      ]"
    >
     <notifications
          :position="this.$i18n.locale == 'ar' ? 'top left' : 'top right'"
        />

      <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.Orders') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>

                            <li class="breadcrumb-item active">{{ $t('global.Orders') }}</li>
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
                  <form action="#" method="post">
                    <div class="row filter-row">
                      <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                          <label for="search">{{ $t("global.Search") }}</label>
                          <input
                            id="search"
                            type="text"
                            v-model="search"
                            class="form-control"
                            :placeholder="$t('global.Search')"
                          />
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                          <label for="status">{{
                            $t("global.Order status")
                          }}</label>
                          <select class="form-control"  v-model="filter_order_status" @change="getOrders">
                            <option value="">
                              {{ $t("global.All Orders") }}
                            </option>
                            <option value="Pending">
                              {{ $t("global.Pending") }}
                            </option>
                            <option value="Processing">
                              {{ $t("global.Processing") }}
                            </option>
                            <option value="Shipping">
                              {{ $t("global.ShippingNow") }}
                            </option>
                            <option value="Completed">
                              {{ $t("global.Completed") }}
                            </option>
                            <option value="Canceled">
                              {{ $t("global.Canceled") }}
                            </option>
                            <option value="Refund">
                              {{ $t("global.Refund") }}
                            </option>
                            <option value="Hold">{{ $t("global.Hold") }}</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                          <label for="status">{{
                            $t("global.Payment Status")
                          }}</label>
                          <select class="form-control"  v-model="filter_payment_status" @change="getOrders">
                            <option value="">
                              {{ $t("global.All Orders") }}
                            </option>
                            <option value="Paid">
                              {{ $t("global.Paid") }}
                            </option>
                            <option value="Unpaid">
                              {{ $t("global.Unpaid") }}
                            </option>
                            <option value="Failed">
                              {{ $t("global.Failed") }}
                            </option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-3">
                        <div class="form-group">
                          <label for="status">{{
                            $t("global.Payment Way")
                          }}</label>
                          <select class="form-control"  v-model="filter_payment_method" @change="getOrders">
                            <option value="">
                              {{ $t("global.All Orders") }}
                            </option>
                            <option value="Cash">
                              {{ $t("global.Cash on delivery") }}
                            </option>
                            <option value="Online">
                              {{ $t("global.Online Payment") }}
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>

                <div class="table-responsive">
                  <table class="table mb-0">
                    <thead>
                      <tr>
                        <th>{{ $t("global.Order Number") }}</th>
                        <th>{{ $t("global.Address") }}</th>
                        <th>{{ $t("global.Receiver Name") }}</th>
                        <th>{{ $t("global.representative") }}</th>
                        <th>{{ $t("global.Order status") }}</th>
                        <th>{{ $t("global.Payment Way") }}</th>
                        <th>{{ $t("global.Payment Status") }}</th>
                        <th>{{ $t("global.Total Amount") }}</th>
                        <th>{{ $t("global.Platform") }}</th>
                        <th>{{ $t("global.Action") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-if="Object.keys(orders.data ?? {}).length"
                        v-for="(item, index) in orders.data"
                        :key="item.id"
                      >
                        <td>{{ item.id }}</td>
                        <td>
                          {{
                            item.receiver_name
                          }}
                        </td>
                        <td>
                          {{
                            item.city_name + " / " + item.area_name
                          }}
                        </td>

                        <td>

                        <div
                        class="col-md-12 alternativeDetail-option"
                      >
                        <div class="row account">

                          <div
                            class="col-md-12 mb-12 body-account row"
                          >
                            <!--Start representative-->
                            <div class="col-md-3">
                              <div class="dropdown" v-if="item.order_status=='Pending' ||item.order_status=='Shipping' || item.order_status=='Processing'">
                                <button
                                  class="btn btn-secondary dropdown-toggle"
                                  type="button"
                                  id="dropdownMenuButton"
                                  data-toggle="dropdown"
                                  aria-haspopup="true"
                                  aria-expanded="false"
                                >
                                  <span v-if="item.representative_id">
                                    <img
                                      :src="'/upload/representative_profile/' + item.representative.image"
                                      alt="product-image"
                                      style="
                                        width: 50px;
                                        height: 50px;
                                        border-radius: 50%;
                                      "
                                    />
                                    {{ item.representative.name }}</span
                                  >
                                  <span v-else>{{
                                    $t("global.representative")
                                  }}</span>
                                </button>
                                <div
                                  :class="[
                                    'dropdown-menu',
                                    this.$i18n.locale == 'en' ? 'drop_ltr' : '',
                                  ]"
                                  style="
                                    height: 400px;
                                    overflow-y: scroll;
                                    width: 400px;
                                    z-index: 999999;
                                  "
                                  aria-labelledby="dropdownMenuButton"
                                >
                                  <input
                                    type="text"
                                    :placeholder="$t('global.Search')"
                                    v-model="rep_search"
                                    :class="['form-control ' , item.representative_id ? 'w-75 d-inline' : 'w-100']"
                                    onchange="event.stopPropagation()"
                                  />
                                  <button class="btn btn-danger mx-4"  v-if="item.representative_id" @click="assignRepresentativeToOrder(item.id,0,'cancel')">{{$t('global.Cancel')}}</button>
                                  <loader v-if="loading2" />

                                  <div
                                    v-for="rep in representatives"
                                    :key="rep.id"
                                    :class="[
                                      'dropdown-item px-2 d-flex justify-content-between',
                                      rep.id == item.representative_id
                                        ? 'bg-secondary'
                                        : '',
                                    ]"
                                    @click="assignRepresentativeToOrder(item.id,rep.id,'assign')"
                                  >
                                    <img
                                      :src="'/upload/representative_profile/' + rep.image"
                                      alt="product-image"
                                      style="width: 50px; height: 50px"
                                    />
                                    <span
                                      style="
                                        overflow: hidden;
                                        height: 34px;
                                        font-size: 24px;
                                        word-break: break-word;
                                      "
                                      >{{ rep.name }}</span
                                    >
                                  </div>

                                  <h5
                                    v-if="
                                      Object.keys(representatives ?? []).length ==
                                      0
                                    "
                                    class="text-center"
                                  >
                                    {{ $t("global.No Data Found") }}
                                  </h5>
                                </div>
                              </div>
                              <div v-else>
                                <span v-if="item.representative_id">
                                    <img
                                      :src="'/upload/representative_profile/' + item.representative.image"
                                      alt="product-image"
                                      style="
                                        width: 50px;
                                        height: 50px;
                                        border-radius: 50%;
                                      "
                                    />
                                    {{ item.representative.name }}</span
                                  >
                                  <span v-else>-</span>
                              </div>
                            </div>
                            <!--End representative-->


                          </div>
                        </div>
                      </div>
                        </td>
                        <td>
                          <span v-if="item.hold == 0">
                              <i class="far fa-pause-circle" v-if="item.order_status =='Pending'"></i>
                              <i class="text-info fas fa-truck" v-if="item.order_status =='Shipping'"></i>
                              <i class="text-success fas fa-check-circle" v-if="item.order_status =='Completed'"></i>
                              <i class="text-info fa fa-cogs" v-if="item.order_status =='Processing'"></i>
                              <i class="fas fa-reply text-danger" v-if="item.order_status =='Refund'"></i>
                              <i class="fa fa-times-circle text-danger" v-if="item.order_status =='Canceled'"></i>
                          {{
                            $t("global." + item.order_status)
                          }}</span>
                          <span v-else
                            ><i class="text-danger far fa-pause-circle"></i>
                            {{ $t("global.Hold") }}</span
                          >
                        </td>
                        <td>{{ $t("global." + item.payment_method) }}</td>
                        <td>
                              <i class="text-success fas fa-check-circle" v-if="item.payment_status =='Paid'"></i>
                              <i class="fa fa-times-circle text-danger" v-if="item.payment_status =='Failed'"></i>
                              <i class="text-dark far fa-pause-circle" v-if="item.payment_status =='Unpaid'"></i>
                          {{ $t("global." + item.payment_status) }}
                        </td>
                        <td>{{ item.total_amount }}</td>
                        <td>{{ $t('global.'+item.order_from) }}</td>
                        <td>
                          <router-link
                            :to="{
                              name: 'showOrderOnline',
                              params: {
                                lang: this.$i18n.locale || 'ar',
                                id: item.id,
                              },
                            }"
                            v-if="permission.includes('orderOnline read')"
                            class="btn btn-sm btn-success me-2"
                          >
                            <i class="far fa-eye"></i>
                          </router-link>
                        </td>
                      </tr>
                      <tr v-else>
                        <th class="text-center" colspan="7">
                            {{ $t("treasury.NoDataFound") }}
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
        <Pagination  :limit="2"  :data="orders" @pagination-change-page="getOrders">
          <template #prev-nav>
            <span>&lt; Previous</span>
          </template>
          <template #next-nav>
            <span>Next &gt;</span>
          </template>
        </Pagination>
        <!-- end Pagination -->
      </div>
      <!-- /Page Wrapper -->
    </div>
  </template>

  <script>
  import { onMounted , computed } from "vue";
  import { ordersComposable } from "./composables/order";
  import {useStore} from "vuex";


  export default {
    name: "index",
    setup() {
      const { orders, search, loading, getOrders,assignRepresentativeToOrder,filter_order_status,filter_payment_method,filter_payment_status,getRepresentatives,
        representatives,
        rep_search,
        loading2, } = ordersComposable();

      let store = useStore();

      let permission = computed(() => store.getters['authAdmin/permission']);

      onMounted(() => {
        getOrders();
        getRepresentatives();
      });

      return {permission, loading, getOrders, search, orders,assignRepresentativeToOrder,filter_order_status,filter_payment_method,filter_payment_status,representatives,loading2,rep_search };
    },
  };
  </script>

  <style scoped>
  .card {
    position: relative;
  }
  .btn-custom {
    width: 30%;
  }
  .custom {
    border: 1px solid #d7d7d7;
    padding: 2px;
    border-radius: 5px;
    width: 35%;
  }
  .btn {
    color: #fff;
  }
  </style>
