<template>
  <div
    class="supplier-container"
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
            <h3 class="page-title">{{ $t("global.Supplier Profile") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">
                {{ $t("global.Supplier Profile") }}
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
                    <div class="col-md-2 col-sm-12">
                      {{ $t("global.Products") }}
                    </div>
                    <div
                      class="col-md-10 col-sm-12 row justify-content-between"
                    >
                      <div class="form-group col-md-4 col-sm-12">
                        <label for="search">{{ $t("global.Search") }}</label>
                        <input
                          id="search"
                          type="text"
                          class="form-control"
                          :placeholder="$t('global.Search')"
                          v-model="search_products"
                        />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table mb-0">
                    <thead>
                      <tr>
                        <th>{{ $t("global.Product Name") }}</th>
                        <th>{{ $t("global.Public Price") }}</th>
                        <th>{{ $t("global.Pharmacy Price") }}</th>
                        <th>{{ $t("global.Client Percentage") }}</th>
                        <th>{{ $t("global.Kayan Discount") }}</th>
                        <th>{{ $t("global.Quantity") }}</th>
                        <th>{{ $t("global.Action") }}</th>
                      </tr>
                    </thead>
                    <tbody v-if="Object.keys(products.data ?? []).length">
                      <tr v-for="(item, index) in products.data" :key="item.id">
                        <td>{{ item.product.nameAr + " / " + item.product.nameEn }}</td>
                        <td>{{ item.publicPrice }} {{ $t("global.LE") }}</td>
                        <td>{{ item.pharmacyPrice }} {{ $t("global.LE") }}</td>
                        <td>{{ item.clientDiscount }} %</td>
                        <td>{{ item.kayanDiscount }} %</td>
                        <td>{{ item.quantity }}</td>
                        <td>
                          <a
                            href="javascript:void(0);"
                            class="btn btn-sm btn-info me-2"
                            data-bs-toggle="modal"
                            @click.prevent="setProductDetails(item.logs)"
                            data-bs-target="#showProductDetails"
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
            <Pagination
              :data="products"
              @pagination-change-page="getSupplierProducts"
            >
              <template #prev-nav>
                <span>&lt; {{ $t("global.Previous") }}</span>
              </template>
              <template #next-nav>
                <span>{{ $t("global.Next") }} &gt;</span>
              </template>
            </Pagination>
            <!-- end Pagination -->
          </div>
        <!-- /Table -->

        <div class="col-lg-4 col-sm-12">
          <div class="text-center card-box">
            <div class="text-left">
              <h4 class="header-title mb-4">{{ $t("global.Supplier") }}</h4>
            </div>
            <div class="member-card">
              <div class="avatar-xl member-thumb mb-2 mx-auto d-block star-div">
                <img
                  :src="
                    supplier.image ??
                    'https://ui-avatars.com/api/?name=' +
                      supplier.name +
                      '&amp;color=7F9CF5&amp;background=EBF4FF'
                  "
                  :onerror="
                    'https://ui-avatars.com/api/?name=' +
                    supplier.name +
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
                <h5 class="font-18 mb-1">{{ supplier.name }}</h5>
              </div>

              <div class="mt-20">
                <ul class="list-inline row">
                  <li class="list-inline-item col-12 mx-0">
                    <h5>{{ $t("global.Email") }}</h5>
                    <p>{{ supplier.email }}</p>
                  </li>
                  <li class="list-inline-item col-6 mx-0">
                    <h5>{{ $t("global.Number of Orders") }}</h5>
                    <p>{{ supplier.order_numbers }}</p>
                  </li>

                  <li class="list-inline-item col-6 mx-0">
                    <h5>{{ $t("global.Phone") }}</h5>
                    <p>{{ supplier.phone }}</p>
                  </li>
                </ul>
              </div>
            </div>
            <!-- end membar card -->
          </div>
        </div>
      </div>
      <div class="row">

      <div class="col-lg-12">
        <div class="card">
          <loader v-if="loading" />
          <div class="card-body">
            <div class="card-header pt-0">
              <div class="row justify-content-between">
                <div class="col-md-2 col-sm-12">
                  {{ $t("global.Sold Products") }}
                </div>
                <div class="col-md-10 col-sm-12 row justify-content-between">
                  <div class="form-group col-md-4 col-sm-12">
                    <label for="search">{{ $t("global.Search") }}</label>
                    <input
                      id="search"
                      type="text"
                      class="form-control"
                      :placeholder="$t('global.Search')"
                      v-model="search_items"
                    />
                  </div>
                  <div class="form-group col-md-3 col-sm-12">
                    <label for="from">{{ $t("global.From") }}</label>
                    <input
                      id="from"
                      type="date"
                      class="form-control"
                      v-model="date_from"
                    />
                  </div>
                  <div class="form-group col-md-3 col-sm-12">
                    <label for="to">{{ $t("global.To") }}</label>
                    <input
                      id="to"
                      type="date"
                      class="form-control"
                      v-model="date_to"
                    />
                  </div>
                  <div class="form-group col-md-2 col-sm-12">
                    <label>.</label>
                    <button
                      class="btn btn-info d-block"
                      @click.prevent="getSupplierOrders()"
                    >
                      {{ $t("global.Search") }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th>{{ $t("global.Product Name") }}</th>
                    <th>{{ $t("global.Public Price") }}</th>
                    <th>{{ $t("global.Pharmacy Price") }}</th>
                    <th>{{ $t("global.Client Percentage") }}</th>
                    <th>{{ $t("global.Kayan Discount") }}</th>
                    <th>{{ $t("global.Quantity") }}</th>
                    <th>{{ $t("global.Total Amount") }}</th>
                  </tr>
                </thead>
                <tbody v-if="Object.keys(orders.data ?? []).length">
                  <tr v-for="(item, index) in orders.data" :key="item.id">
                    <td>{{ item.nameAr + " / " + item.nameEn }}</td>
                    <td>
                      {{ item.unit_price_for_client }} {{ $t("global.LE") }}
                    </td>
                    <td>
                      {{ item.unit_price_for_pharmacist }} {{ $t("global.LE") }}
                    </td>
                    <td>{{ item.discount_percentage }} %</td>
                    <td>{{ item.kayan_discount }} %</td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.total_amount }} {{ $t("global.LE") }}</td>
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
        <Pagination :data="orders" @pagination-change-page="getSupplierOrders">
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

      <!-- Edit Modal -->
      <div class="modal fade custom-modal" id="showProductDetails">
        <div class="modal-dialog modal-dialog-centered " style="max-width:1300px!important">
          <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">
                {{ $t("global.Product details") }}
              </h4>
              <button
                id="close-showProductDetails"
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
                  <table class="table table-center table-hover mb-0 datatable">
                    <thead>
                      <tr>
                        <th>{{ $t("global.Client Percentage") }}</th>
                        <th>{{ $t("global.Kayan Discount") }}</th>
                        <th>{{ $t("global.Pharmacy Price") }}</th>
                        <th>{{ $t("global.Public Price") }}</th>
                        <th>{{ $t("global.diff quantity") }}</th>
                        <th>{{ $t("global.sold quantity") }}</th>
                        <th>{{ $t("global.Total Quantity") }}</th>
                        <th>{{ $t("global.Date") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="product in product_logs" :key="product.id">
                        <td>
                          {{
                            product.clientDiscount
                          }} %
                        </td>
                        <td>{{ product.kayanDiscount }} %</td>
                        <td>{{ product.pharmacyPrice }} {{$t('global.LE')}}</td>
                        <td>{{ product.publicPrice }} {{$t('global.LE')}}</td>
                        <td>{{ product.diff_qty }}</td>
                        <td>{{ product.sold_quantity }}</td>
                        <td>{{ product.total_qty }}</td>
                        <td>{{dateFormat(product.created_at) }}</td>
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
    const product_logs = ref({});
    const supplier = ref({});
    const orders = ref({});
    const debounce = ref({});
    const search_items = ref("");
    const search_products = ref("");
    const date_from = ref("");
    const date_to = ref("");
    const getSupplier = async (id) => {
      adminApi.get(`/v1/dashboard/supplier_profile/${id}`).then((response) => {
        supplier.value = response.data.supplier;
      });
    };
    const getSupplierOrders = async (page = 1) => {
      adminApi
        .get(
          `/v1/dashboard/supplier_orders?page=${page}&user_id=${props.id}&search=${search_items.value}&date_from=${date_from.value}&date_to=${date_to.value}`
        )
        .then((response) => {
          orders.value = response.data.items;
        });
    };
    const getSupplierProducts = async (page = 1) => {
      adminApi
        .get(
          `/v1/dashboard/supplier_products?page=${page}&user_id=${props.id}&search=${search_products.value}`
        )
        .then((response) => {
          products.value = response.data.products;
        });
    };

    onMounted(() => {
      getSupplier(props.id);
      getSupplierOrders();
      getSupplierProducts();
    });

    watch(search_products, () => {
      clearTimeout(debounce.value);
      debounce.value = setTimeout(() => {
        getSupplierProducts();
      }, 500);
    });

    const setProductDetails = (data) => {
      product_logs.value = data;
    };

    let dateFormat = (item) => {
            let now = new Date(item);
            let st = `
                 ${now.getUTCHours()}:${now.getUTCMinutes()}:${now.getUTCSeconds()}
                ${now.getUTCFullYear().toString()}
                 /${(now.getUTCMonth() + 1).toString()}
                 /${now.getUTCDate()}
            `;
            return st;
        };

    return {
      supplier,
      dateFormat,
      getSupplierOrders,
      setProductDetails,
      search_items,
      date_from,
      date_to,
      search_products,
      getSupplierProducts,
      products,
      orders,
      product_logs,
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

.supplier-container {
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
