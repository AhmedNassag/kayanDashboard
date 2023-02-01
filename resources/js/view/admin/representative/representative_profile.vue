<template>
    <div
      class="representative-container"
      :class="[
        'page-wrapper',
        this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
      ]"
    >
      <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
          <div class="row align-items-center">
            <div class="col">
              <h3 class="page-title">{{ $t("global.Representative Profile") }}</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item">
                  <router-link :to="{ name: 'dashboard' }">
                    {{ $t("dashboard.Dashboard") }}
                  </router-link>
                </li>
                <li class="breadcrumb-item active">
                  {{ $t("global.Representative Profile") }}
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- /Page Header -->
        <!-- Table -->
        <div class="row">
          <div class="col-lg-8">
            <div class="card">
              <loader v-if="loading" />
              <div class="card-body">
                <div class="card-header pt-0">
                  <div class="row justify-content-between">
                    <div class="col-5">
                      {{$t('global.Representative Orders')}}
                    </div>
                    <div class="col-5">
                        <router-link
                            :to="{name: 'indexRepresentative'}"
                            class="btn btn-custom btn-dark w-50 bg-dark"
                        >
                            {{ $t('global.back') }}
                        </router-link>
                        <a
                            class="btn btn-sm btn-secondary mx-2"
                            @click.prevent="printSection(representative.name,'#printOrders')"
                            ><i class="fa fa-print"></i> </a
                        >
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table mb-0" id="printOrders">
                    <thead>
                      <tr>
                        <th>{{$t('global.Order Number')}}</th>
                        <th>{{ $t("global.Order Status") }}</th>
                        <th>{{ $t("global.Receiver Name") }}</th>
                        <th>{{ $t("global.Receiver Phone") }}</th>
                        <th>{{ $t("global.Date") }}</th>
                        <th>{{ $t("global.Address") }}</th>
                        <th>{{ $t("global.Total Amount") }}</th>
                        <th>{{ $t("global.Show") }}</th>
                      </tr>
                    </thead>
                    <tbody v-if="Object.keys(orders.data ?? []).length">
                      <tr
                        v-for="(order, index) in orders.data"
                        :key="order.id"
                      >
                        <td>{{ order.id }}</td>
                        <td>{{$t('global.' + order.order_status) }} </td>
                        <td>{{order.receiver_name }} </td>
                        <td>{{order.receiver_phone }} </td>
                        <td>{{ order.created_at }} </td>
                        <td>{{order.city_name + '/'+ order.area_name }} </td>

                        <td>
                          {{ order.total_amount }} {{ $t("global.LE") }}
                        </td>
                        <td>
                          <a
                                href="javascript:void(0);"
                                class="btn btn-sm btn-info me-2"
                                data-bs-toggle="modal"
                                @click.prevent="setOrderDetails(order.products)"
                                data-bs-target='#showOrderDetails'
                              >
                              <i class="fa fa-eye"></i>
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
            <!-- /Table -->
            <!-- start Pagination -->
            <Pagination :data="orders" @pagination-change-page="getClienOrders">
              <template #prev-nav>
                <span>&lt; {{ $t("global.Previous") }}</span>
              </template>
              <template #next-nav>
                <span>{{ $t("global.Next") }} &gt;</span>
              </template>
            </Pagination>
            <!-- end Pagination -->
          </div>

          <div class="col-lg-4 col-sm-12">
            <div class="text-center card-box">
              <div class="text-left">
                <h4 class="header-title mb-4">{{ $t("global.Representative") }}</h4>
              </div>
              <div class="member-card">
                <div class="avatar-xl member-thumb mb-2 mx-auto d-block star-div">
                  <img
                    :src="
                      representative.image ? `/upload/representative_profile/${representative.image}`:
                      'https://ui-avatars.com/api/?name=' +
                        representative.name +
                        '&amp;color=7F9CF5&amp;background=EBF4FF'
                    "
                    :onerror="
                      'https://ui-avatars.com/api/?name=' +
                      representative.name +
                      '&amp;color=7F9CF5&amp;background=EBF4FF'
                    "
                    class="rounded-circle img-thumbnail"
                    alt="profile-image"
                  />
                  <i
                    class="fas fa-certificate text-primary small star-icon"
                    title="Featured Agent"
                  ></i>
                </div>

                <div class="">
                  <h5 class="font-18 mb-1">{{ representative.name }}</h5>
                </div>

                <div class="mt-20">
                  <ul class="list-inline row">
                    <li class="list-inline-item col-12 mx-0">
                      <h5>{{ $t("global.Email") }}</h5>
                      <p>{{ representative.email }}</p>
                    </li>
                    <li class="list-inline-item col-6 mx-0">
                      <h5>{{ $t("global.Number of Orders") }}</h5>
                      <p>{{ representative.order_numbers }}</p>
                    </li>

                    <li class="list-inline-item col-6 mx-0">
                      <h5>{{ $t("global.Phone") }}</h5>
                      <p>{{ representative.phone }}</p>
                    </li>
                  </ul>
                </div>
              </div>
              <!-- end membar card -->
            </div>
          </div>
        </div>
        <!-- /Table -->



            <!-- Edit Modal -->
            <div class="modal fade custom-modal" id="showOrderDetails">
            <div class="modal-dialog modal-dialog-centered modal-xl">
              <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">
                    {{ $t("global.Order details") }}
                  </h4>
                  <button
                    id="close-showOrderDetails"
                    type="button"
                    class="close print-button"
                    data-bs-dismiss="modal"
                  >
                    <span>&times;</span>
                  </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body row">
                  <div class="card bg-white projects-card">
                    <div class="card-body pt-0">
                      <table
                              class="table table-center table-hover mb-0 datatable"
                            >
                              <thead>
                                <tr>
                                  <th>{{ $t("global.Product Name") }}</th>
                                <th>{{ $t("global.Supplier Name") }}</th>
                                <th>{{ $t("global.Quantity") }}</th>
                                <th>{{ $t("global.Subtotal") }}</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="product in products" :key="product.id">
                                <td>{{ product.product_name_ar +"/"+ product.product_name_en }}</td>
                                <td>{{ product.supplier_name }}</td>
                                <td>{{ product.quantity }}</td>
                                <td>{{ product.total_amount }}</td>
                              </tr>
                              </tbody>
                            </table>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /Edit Modal -->
      </div>
    </div>
  </template>

  <script>
  import { computed, onMounted, provide, watch, ref } from "@vue/runtime-core";
  import adminApi from "../../../api/adminAxios";
  import { useI18n } from "vue-i18n";
  export default {
    props: ["id"],
    setup(props) {
      const { t } = useI18n();
      const products = ref({});
      const representative = ref({});
      const orders = ref({});
      const getRepresentative = async (id) => {
        adminApi.get(`/v1/dashboard/representative_profile/${id}`).then((response) => {
          representative.value = response.data.representative;
        });
      };
      const getClienOrders = async (page=1) => {
        adminApi.get(`/v1/dashboard/representative_orders?page=${page}&user_id=${props.id}`).then((response) => {
          orders.value = response.data.orders;
        });
      };

      onMounted(() => {
        getRepresentative(props.id);
        getClienOrders()
      });

      const setOrderDetails = (data) => {
        products.value = data
      }

      const printSection =async (rep_name , id) => {
          $(id).printThis({
              header: `<h1 class="text-center">${rep_name}</h1>`
          });
      }
      return {
        representative,
        getClienOrders,
        setOrderDetails,
        orders,products,printSection
      };
    },
  };
  </script>

  <style lang="scss" scoped>
    .card-box {
      background-color: #a1ccff;
      padding: 10px;
      border-radius: 10px;
      margin-bottom: 20px;
    }

  .representative-container {
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

    .star-div {
      position: relative;
    }
    .star-icon {
      position: absolute;
      top: 4px;
      right: 2px;
      font-size: 16px;
      background-color: #f3f3f3;
      height: 20px;
      width: 20px;
      border-radius: 50%;
      line-height: 20px;
      text-align: center;
    }
  }
  </style>
