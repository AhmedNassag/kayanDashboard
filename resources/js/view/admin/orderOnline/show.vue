<template>
    <div
      :class="[
        'page-wrapper',
        this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
      ]"
    >
      <div class="content container-fluid">
        <notifications
          :position="this.$i18n.locale == 'ar' ? 'top left' : 'top right'"
        />

        <!-- Page Header -->
        <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.Order details') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexOrderOnline', params: {lang: locale || 'ar'}}">
                                    {{ $t('global.Orders') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.Order details') }}</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->
            <loader v-if="loading" />

        <div class="row">
          <div class="col-lg-8 col-sm-12">
            <div
              id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel"
            >
              <div class="carousel-inner" style="height: 442px; width: 100%">
                <div
                  :class="['carousel-item' , key == 0 ?'active' : ''] "
                  :style="[
                    this.$i18n.locale == 'ar' ? 'float:left!important' : '',
                    'margin-left: unset!important;',
                  ]"
                  v-for="(product,key) in products"
                  :key="product.id"
                >
                  <img
                    class="d-block w-100"  style="height: 442px; width: 100%"
                    :src="product.product.image ? '/upload/product/'+product.product.image:'/admin/img/Logo Dashboard.png'"
                    alt="First slide"
                  />
                </div>
              </div>
              <a
                :class="
                  this.$i18n.locale == 'ar'
                    ? 'carousel-control-next'
                    : 'carousel-control-prev'
                "
                :style="this.$i18n.locale == 'ar' ? 'right:unset!important' : ''"
                href="#carouselExampleControls"
                role="button"
                data-slide="prev"
              >
                <span
                  :class="
                    this.$i18n.locale == 'ar'
                      ? 'carousel-control-next-icon'
                      : 'carousel-control-prev-icon'
                  "
                  aria-hidden="true"
                ></span>
                <span class="sr-only">Previous</span>
              </a>
              <a
                class="carousel-control-next"
                :class="
                  this.$i18n.locale == 'ar'
                    ? 'carousel-control-prev'
                    : 'carousel-control-next'
                "
                href="#carouselExampleControls"
                role="button"
                data-slide="next"
              >
                <span
                  :class="
                    this.$i18n.locale == 'ar'
                      ? 'carousel-control-prev-icon'
                      : 'carousel-control-next-icon'
                  "
                  aria-hidden="true"
                ></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
            <div id="printDiv">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title px-2">
                    {{ $t("global.Receiver Information") }}
                  </h5>
                </div>
                <div class="card-body">
                  <div class="row justify-content-between">
                    <h5 class="w-auto">{{ $t("global.Receiver Name") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      order.receiver_name
                    }}</span>
                  </div>
                  <div class="row justify-content-between">
                    <h5 class="w-auto">{{ $t("global.Receiver Phone") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      order.receiver_phone
                    }}</span>
                  </div>
                  <div class="row justify-content-between">
                    <h5 class="w-auto">
                      {{ $t("global.State / Area") }}
                    </h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      state + " / " + area
                    }}</span>
                  </div>
                  <div class="row justify-content-between">
                    <h5 class="w-auto">{{ $t("global.Receiver Adress") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      order.receiver_address
                    }}</span>
                  </div>
                  <div class="row justify-content-between">
                    <h5 class="w-auto">{{ $t("global.Date") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      order.created_at
                    }}</span>
                  </div>
                </div>
              </div>
              <div class="card" v-if="order.representative_id">
                <div class="card-header">
                  <h5 class="card-title px-2">
                    {{ $t("global.Representative Information") }}
                  </h5>
                </div>
                <div class="card-body">
                  <div class="row justify-content-between">
                    <h5 class="w-auto">{{ $t("global.Representative Name") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      order.representative.name
                    }}</span>
                  </div>
                  <div class="row justify-content-between">
                    <h5 class="w-auto">{{ $t("global.Representative Phone") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      order.representative.phone
                    }}</span>
                  </div>

                  <div class="row justify-content-between">
                    <h5 class="w-auto">{{ $t("global.Representative Email") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      order.representative.email
                    }}</span>
                  </div>

                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title px-2">
                    {{ $t("global.Order Information") }}
                  </h5>
                </div>
                <div class="card-body">
                  <div class="row justify-content-between">
                    <h5 class="w-auto">{{ $t("global.Order Number") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      order.id
                    }}</span>
                  </div>
                  <div class="row justify-content-between" v-if="order.invoice_id != 0">
                    <h5 class="w-auto">{{ $t("global.Invoice id") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      order.invoice_id
                    }}</span>
                  </div>
                  <div class="row justify-content-between" v-if="order.coupon">
                    <h5 class="w-auto">{{ $t("global.Coupon") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      order.coupon
                    }}</span>
                  </div>
                  <div class="row justify-content-between">
                    <h5 class="w-auto">{{ $t("global.Order Status") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">
                      <i
                        class="far fa-pause-circle"
                        v-if="order.order_status == 'Pending'"
                      ></i>
                      <i
                        class="text-info fas fa-truck"
                        v-if="order.order_status == 'Shipping'"
                      ></i>
                      <i
                        class="text-success fas fa-check-circle"
                        v-if="order.order_status == 'Completed'"
                      ></i>
                      <i
                        class="text-info fa fa-cogs"
                        v-if="order.order_status == 'Processing'"
                      ></i>
                      <i
                        class="fas fa-reply text-danger"
                        v-if="order.order_status == 'Refund'"
                      ></i>
                      <i
                        class="fa fa-times-circle text-danger"
                        v-if="order.order_status == 'Canceled'"
                      ></i>
                      {{ order.order_status }}</span
                    >
                  </div>
                  <div class="row justify-content-between">
                    <h5 class="w-auto">{{ $t("global.Payment Status") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">
                      <i
                        class="text-success fas fa-check-circle"
                        v-if="order.payment_status == 'Paid'"
                      ></i>
                      <i
                        class="fa fa-times-circle text-danger"
                        v-if="order.payment_status == 'Failed'"
                      ></i>
                      <i
                        class="text-dark far fa-pause-circle"
                        v-if="order.payment_status == 'Unpaid'"
                      ></i>
                      {{ $t("global." + order.payment_status) }}</span
                    >
                  </div>
                  <div class="row justify-content-between">
                    <h5 class="w-auto">{{ $t("global.Payment Way") }}</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      $t("global." + order.payment_method)
                    }}</span>
                  </div>
                  <div
                    class="row justify-content-between"
                    v-if="order.payment_method == 'Online' &&  order.transaction_id != 0"
                  >
                    <h5 class="w-auto">Transaction id</h5>
                    <span class="w-auto" style="color: #fcb00c">{{
                      order.transaction_id
                    }}</span>
                  </div>
                </div>
              </div>

              <div class="d-flex">
                <div class="card w-100">
                  <div class="card-body pt-0">
                    <div class="card-header mb-4">
                      <h5 class="card-title">{{ $t("global.Order details") }}</h5>
                    </div>

                    <!-- {{-- items details --}}                     style="max-height: 1000px; overflow-y: scroll" -->
                    <div
                      class="card-box"

                    >
                      <div class="table-responsive">
                        <table class="table table-bordered table-secondary mb-4">
                          <tbody>
                            <tr>
                              <th>{{ $t("global.Product Name") }}</th>
                              <th>{{ $t("global.Supplier Name") }}</th>
                              <th>{{ $t("global.Pharmacy Price") }}</th>
                              <th>{{ $t("global.Public Price") }}</th>
                              <!-- <th>{{ $t("global.Discount Percentage") }}</th> -->
                              <th>{{ $t("global.Quantity") }}</th>
                              <th>{{ $t("global.Subtotal") }}</th>
                            </tr>
                            <tr v-for="product in products" :key="product.id">
                              <td>{{ product.product_name_ar +"/"+ product.product_name_en }}</td>
                              <td>{{ product.supplier_name }}</td>
                              <td>{{ product.unit_price_for_pharmacist }}</td>
                              <td>{{ product.unit_price_for_client }}</td>
                              <!-- <td>{{ product.discount_percentage }}</td> -->
                              <td>{{ product.quantity }}</td>
                              <td>{{ product.total_amount }}</td>
                            </tr>
                          </tbody>
                        </table>

                        <hr style="" />
                        <br />
                        <table class="table table-bordered mb-4">
                          <tbody class="table-secondary">
                            <tr>
                              <th>{{ $t("global.Taxes") }}</th>
                              <th v-if="order.coupon_discount">{{ $t("global.Coupon Discount") }}</th>
                              <th>{{ $t("global.Shipping cost") }}</th>
                              <th>{{ $t("global.Sub total") }}</th>
                              <th>{{ $t("global.Total Amount") }}</th>
                            </tr>
                            <tr>
                              <td>{{ order.taxes }} {{ $t("global.LE") }}</td>
                              <td v-if="order.coupon_discount">{{ order.coupon_discount }} {{ $t("global.LE") }}</td>
                              <td>
                                {{ order.shipping_cost }} {{ $t("global.LE") }}
                              </td>
                              <td>{{ order.subtotal }} {{ $t("global.LE") }}</td>
                              <td>
                                {{ order.total_amount }} {{ $t("global.LE") }}
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-12">
            <div class="text-center card-box">
              <div class="text-left">
                <h4 class="header-title mb-4">{{ $t("global.Client") }}</h4>
              </div>
              <div class="member-card">
                <div class="avatar-xl member-thumb mb-2 mx-auto d-block star-div">
                  <img
                    :src="client.image??'https://ui-avatars.com/api/?name=' +
                      client.name +
                      '&amp;color=7F9CF5&amp;background=EBF4FF'"
                    :onerror="
                      'https://ui-avatars.com/api/?name=' +
                      client.name +
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
                  <h5 class="font-18 mb-1">{{ client.name }}</h5>
                </div>

                <div class="mt-20">
                  <ul class="list-inline row">
                    <li class="list-inline-item col-12 mx-0">
                      <h5>{{ $t("global.Email") }}</h5>
                      <p>{{ client.email }}</p>
                    </li>
                    <li class="list-inline-item col-6 mx-0">
                      <h5>{{ $t("global.Number of Orders") }}</h5>
                      <p>{{ order_numbers }}</p>
                    </li>

                    <li class="list-inline-item col-6 mx-0">
                      <h5>{{ $t("global.Phone") }}</h5>
                      <p>{{ client.phone }}</p>
                    </li>
                  </ul>
                </div>
              </div>
              <!-- end membar card -->
            </div>
            <div class="text-center card-box">
              <div class="text-left">
                <h4 class="header-title mb-4 d-flex justify-content-between">
                  <span>{{ $t("global.Action") }}</span>
                  <span
                    ><a
                      class="btn btn-sm btn-secondary"
                      @click.prevent="printPolica"
                      ><i class="fa fa-print"></i> </a
                  ></span>
                </h4>
              </div>
              <div class="member-card">
                <div
                  v-if="
                    (order.payment_status != 'Failed'  && order.order_status != 'Canceled' &&order.order_status != 'Refund') ||
                    (order.order_status == 'Completed'&& refund_allowed)
                  "
                  class="col-sm-12 py-4 d-flex flex-row justify-content-center"
                >

                <div class="col-sm-12 py-4 d-flex flex-row justify-content-center" >
                      <button
                      v-if="
                          order.hold == 0 &&
                          order.order_status != 'Completed' &&
                          order.order_status != 'Canceled' &&
                          order.order_status != 'Modified' &&
                          order.order_status != 'Pending' &&
                          (order.order_status == 'Processing' ||
                          order.order_status == 'Shipping')
                      "
                      class="btn btn-warning btn-sm mx-1 text-dark"
                      @click="holdOrder(order.id)"
                      style="height: 60px"
                      >
                      {{ $t("global.Hold") }}
                      <i class="text-dark far fa-pause-circle"></i>
                      </button>

                      <button
                      v-if="order.hold != 0"
                      class="btn btn-primary btn-sm mx-1"
                      @click="holdOrder(order.id)"
                      style="height: 60px"
                      >
                      {{ $t("global.Continue") }}<i class="far fa-play-circle"></i>
                      </button>

                      <button
                      v-if="order.hold == 0 && order.order_status == 'Pending'"
                      class="btn btn-primary btn-sm mx-1"
                      @click="updateOrderStatus(order.id)"
                      style="height: 60px"
                      >
                      {{ $t("global.Processing") }} <i class="fas fa-cogs"></i>
                      </button>

                      <button
                      v-if="order.hold == 0 && order.order_status == 'Processing'"
                      class="btn btn-info btn-sm mx-1"
                      @click="updateOrderStatus(order.id)"
                      style="height: 60px"
                      >
                      {{ $t("global.ShippingNow") }} <i class="fas fa-truck"></i>
                      </button>

                      <button
                      v-if="order.hold == 0 && order.order_status == 'Shipping'"
                      class="btn btn-success btn-sm mx-1"
                      @click="updateOrderStatus(order.id)"
                      style="height: 60px"
                      >
                      {{ $t("global.Completed") }}
                      <i class="fas fa-check-circle"></i>
                      </button>





                      <button
                        v-if="
                        (order.payment_method == 'Online' &&order.payment_status == 'Unpaid') ||((order.order_status == 'Pending' ||order.order_status == 'Processing') && order.payment_method == 'Cash')
                        "
                        class="btn btn-danger btn-sm mx-1"
                        @click="cancelOrder(order.id)"
                        style="height: 60px"
                    >
                        {{ $t("global.Cancel the order") }}
                        <i class="fas fa-power-off"></i>
                    </button>
                      <div
                      v-else
                      >
                      <p
                          class="text-danger"
                          v-if="order.order_status == 'Completed' && refund_allowed"
                      >
                          <i class="text-danger mdi mdi-close-circle"></i
                          >{{
                          $t("global.You can not return order after") +
                          " ( " +
                          refund_date +
                          " )"
                          }}
                      </p>

                      <button
                          class="btn btn-danger btn-sm mx-1"
                          v-if="order.order_status == 'Completed' && refund_allowed || order.order_status == 'Shipping'"
                          @click="cancelOrder(order.id)"
                          style="height: 60px"
                      >
                          <i class="fas fa-reply"></i> {{ $t("global.Refund order") }}
                      </button>
                      </div>


                </div>

                </div>
                <div v-else>
                  {{ $t("global.The actions finished") }}
                </div>


              </div>
              <!-- end membar card -->
            </div>
          </div>
        </div>
      </div>
    </div>


  </template>

  <script>
  import { onMounted, onUpdated } from "vue";
  import { ordersComposable } from "./composables/order";
  export default {
    props: ["id"],
    setup(props) {
      const {
        getOrder,
        loading,
        refund_allowed,
        products,
        order_numbers,
        refund_date,
        order,
        client,
        area,
        state,
        updateOrderStatus,
        holdOrder,
        cancelOrder,
      } = ordersComposable();

      onMounted(() => {
        getOrder(props.id);
      });

      const printPolica =async () => {
          $("#printDiv").printThis({
              header: "<img src='/admin/img/Logo Dashboard.png' onerror='logo' style='width:100%;height:400px' > "
          });
      }


      return {
        products,
        order,
        loading,
        client,
        area,
        state,
        printPolica,
        holdOrder,
        updateOrderStatus,
        cancelOrder,
        order_numbers,
        refund_date,
        refund_allowed,
      };
    },
  };
  </script>


  <style scoped>
  .card-box {
    background-color: #a1ccff;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 20px;
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
  .phase {
    display: inline-block;
    padding: 5px;
  }
  </style>
