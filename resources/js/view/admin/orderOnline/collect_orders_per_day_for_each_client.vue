<template>
    <div :class="[
        'page-wrapper',
        this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
    ]">
        <notifications :position="this.$i18n.locale == 'ar' ? 'top left' : 'top right'" />

        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t("global.collect_orders_per_day_for_each_client") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{ name: 'dashboard', params: { lang: 'ar' } }">
                                    {{ $t("dashboard.Dashboard") }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t("global.collect_orders_per_day_for_each_client") }}
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
                                    <div class="col-12 row justify-content-end">
                                        <div class="form-group row col-md-6">
                                            <label>{{ $t("global.Date") }}</label>
                                            <input type="date" class="form-control date-input" v-model="date"
                                                @change="collect_orders_per_day_for_each_client" />
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end">
                                            <button @click.prevent="printSection('#printUsers')"
                                                class="btn btn-success print-button  w-25 h-50 mt-4">
                                                {{ $t("global.Print") }}
                                                <i class="fa fa-print"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- <div class="col-12 row mt-3">
                                        <div class="col-6">
                                            {{ $t("global.Search") }}:
                                            <input type="search" v-model="search" class="custom"/>
                                        </div>

                                    </div> -->
                                </div>
                            </div>
                            <div class="table-responsive" id="printUsers">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ $t("global.Image") }}</th>
                                            <th>{{ $t("global.Name") }}</th>
                                            <th>{{ $t("global.Email") }}</th>
                                            <th>{{ $t("global.Phone") }}</th>
                                            <th>{{ $t("global.Action") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="Object.keys(users.data ?? {}).length"
                                            v-for="(item, index) in users.data" :key="item.id">
                                            <td>{{ index + 1 }}</td>
                                            <td>
                                                <img :src="item.image ? '/upload/' + item.image : '/admin/img/Logo Dashboard.png'"
                                                    :alt="item.name" class="custom-img"
                                                    style="width:auto;height: 50px;" />
                                            </td>
                                            <td style="white-space:normal">{{ item.name }}</td>
                                            <td>{{ item.email }}</td>
                                            <td>{{ item.phone }}</td>

                                            <td>

                                                <a href="javascript:void(0);" class="btn btn-sm btn-info me-2"
                                                    data-bs-toggle="modal"
                                                    @click="setUserDetails(item.orders, item.name)"
                                                    data-bs-target="#showOrders">
                                                    <i class="fa fa-eye"></i>
                                                </a>
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
            <Pagination :limit="2" :data="users" @pagination-change-page="collect_orders_per_day_for_each_client">
                <template #prev-nav>
                    <span>&lt; {{ $t("global.Previous") }}</span>
                </template>
                <template #next-nav>
                    <span>{{ $t("global.Next") }} &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
        </div>
        <!-- /Page Wrapper -->
        <!-- Edit Modal -->
        <div class="modal fade custom-modal" id="showOrders">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" id="print">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ client_name }}
                        </h4>
                        <button id="close-showOrders" type="button" class="close print-button" data-bs-dismiss="modal">
                            <span>&times;</span>
                        </button>
                        <button @click.prevent="printSection('#printOrders', client_name)"
                            class="btn btn-success print-button mt-4" style="position:absolute;left:90px;top:10px">
                            {{ $t("global.Print") }}
                            <i class="fa fa-print"></i>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body row" id="printOrders">
                        <div class="card bg-white projects-card">
                            <div class="card-body pt-0">
                                <table class="table table-center table-hover mb-0 datatable">
                                    <thead>
                                        <tr>
                                            <th>{{ $t("global.Order Number") }}</th>
                                            <th>{{ $t("global.Order status") }}</th>
                                            <th>{{ $t("global.Total Amount") }}</th>
                                            <th>{{ $t("global.Date") }}</th>
                                            <th>{{ $t("global.Address") }}</th>
                                            <th>{{ $t("global.Action") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in showOrders" :key="item.id">
                                            <td>{{ item.id }}</td>
                                            <td>{{ $t('global.'+ item.order_status) }}</td>
                                            <td>{{ item.total_amount }}</td>
                                            <td>{{ item.created_at }}</td>
                                            <td>{{ item.city_name + " / " + item.area_name }}</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-info me-2"
                                                     @click="setOrderDetails(item.products,item.id)"
                                                    >
                                                    <i class="fa fa-eye"></i>
                                                </a>
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
        <!-- /Edit Modal -->
        <!-- Edit Modal -->
        <div class="modal fade custom-modal" id="showProducts">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ client_name }} ({{ order_id }})
                        </h4>
                        <button id="close-showProducts" type="button" class="close print-button"
                            data-bs-dismiss="modal">
                            <span>&times;</span>
                        </button>
                        <button @click.prevent="printSection('#printProducts', client_name)"
                            class="btn btn-success print-button mt-4" style="position:absolute;left:90px;top:10px">
                            {{ $t("global.Print") }}
                            <i class="fa fa-print"></i>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body row" id="printProducts">
                        <div class="card bg-white projects-card">
                            <div class="card-body pt-0">
                                <table class="table table-center table-hover mb-0 datatable">
                                    <thead>
                                        <tr>
                                            <th>{{ $t("global.Product Name") }}</th>
                                            <th>{{ $t("global.Supplier Name") }}</th>
                                            <th>{{ $t("global.Pharmacy Price") }}</th>
                                            <th>{{ $t("global.Public Price") }}</th>
                                            <!-- <th>{{ $t("global.Discount Percentage") }}</th> -->
                                            <th>{{ $t("global.Quantity") }}</th>
                                            <th>{{ $t("global.Subtotal") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="product in showProducts" :key="product.id">
                                            <td>{{ product.product_name_ar + "/" + product.product_name_en }}</td>
                                            <td>{{ product.supplier_name }}</td>
                                            <td>{{ product.unit_price_for_pharmacist }}</td>
                                            <td>{{ product.unit_price_for_client }}</td>
                                            <!-- <td>{{ product.discount_percentage }}</td> -->
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
</template>

<script>
import { onMounted, inject, watch, ref, computed } from "vue";
import { useStore } from "vuex";
import adminApi from "../../../api/adminAxios";
import { useI18n } from "vue-i18n";

export default {
    name: "index",
    setup() {
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);

        // get packages
        let showOrders = ref({});
        let showProducts = ref({});
        let users = ref({});
        let date = ref(new Date().toJSON().slice(0, 10));
        let loading = ref(false);
        let client_name = ref("");
        let order_id = ref("");
        const search = ref("");

        let collect_orders_per_day_for_each_client = (page = 1) => {
            loading.value = true;
            adminApi
                .get(`/v1/dashboard/collect_orders_per_day_for_each_client?page=${page}&search=${search.value}&date=${date.value}`)
                .then((res) => {
                    users.value = res.data.users;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        };

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                collect_orders_per_day_for_each_client();
            }
        });

        let printSection = (id, client_name = '') => {
            $(id).printThis({
                'header': `${client_name}`
            });
        }

        onMounted(() => {
            collect_orders_per_day_for_each_client();
        })


        let dateFormat = (item) => {
            return new Date(item).toDateString();
        };

        let setUserDetails = (item, clientName) => {
            client_name.value = clientName
            showOrders.value = item;
        };

        let setOrderDetails = (item,orderId) => {
            showProducts.value = item;
            order_id.value = orderId;
            $('#showProducts').modal('toggle')
        };


        return {
            users,
            printSection,
            date,
            showOrders,
            showProducts,
            loading,
            collect_orders_per_day_for_each_client,
            setUserDetails,
            dateFormat,
            search,
            order_id,
            client_name,
            setOrderDetails,
            permission
        };
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

.hover:hover {
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}

.amount {
    background-color: #fcb00c;
    color: #000;
    padding: 10px;
}

.amount h5 {
    font-weight: 700;
    text-align: center;
}

.submit-margin {
    margin-top: 38px !important;
}

.date-input {
    width: 50% !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}

.select-input {
    width: 235px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}


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

.custom-modal .close span {
    top: 0 !important;
}

.modal-dialog {
    max-width: 1000px !important;
}
</style>
