<template>
    <div
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
                        <h3 class="page-title">{{ $t("global.Suppliers Dues") }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link
                                    :to="{ name: 'dashboard', params: { lang: locale || 'ar' } }"
                                >
                                    {{ $t("dashboard.Dashboard") }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t("global.Reports") }}</li>
                            <li class="breadcrumb-item active">
                                {{ $t("global.Suppliers Dues") }}
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
                        <loader v-if="loading"/>
                        <div class="card-body">
                            <div class="card-header pt-0">
                                <div class="row justify-content-between">
                                    <div class="col-12 row justify-content-end">
                                        <form @submit.prevent="getIncome" class="needs-validation">
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label>{{ $t("global.FromDate") }}</label>
                                                    <input
                                                        type="date"
                                                        class="form-control date-input"
                                                        v-model="fromDate"
                                                    />
                                                </div>

                                                <div class="col-md-3">
                                                    <label>{{ $t("global.ToDate") }}</label>
                                                    <input
                                                        type="date"
                                                        class="form-control date-input"
                                                        v-model="toDate"
                                                    />
                                                </div>


                                                <div class="col-md-4">
                                                    <label>{{ $t("global.Status") }} </label>
                                                    <select
                                                        v-model="paid"
                                                        class="form-select select-input"
                                                        required
                                                    >
                                                        <option value="1">
                                                            {{ $t("global.PaymentDone") }}
                                                        </option>
                                                        <option value="0">
                                                            {{ $t("global.NotPaid") }}
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <button class="btn btn-primary" type="submit">
                                                        {{ $t("global.Submit") }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-12 row mt-3">
                                        <div class="col-6">
                                            {{ $t("global.Search") }}:
                                            <input type="search" v-model="search" class="custom"/>
                                        </div>
                                        <div class="col-6 d-flex justify-content-end">
                                            <button
                                                @click="printExpense"
                                                class="btn btn-success print-button"
                                            >
                                                {{ $t("global.Print") }}
                                                <i class="fa fa-print"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" id="printExpense">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t("global.Supplier Name") }}</th>
                                        <th>{{ $t("global.Phone") }}</th>
                                        <th>{{ $t("global.Address") }}</th>
                                        <th>{{ $t("global.Number of Orders") }}</th>
                                        <th>{{ $t("global.Amount") }}</th>
                                        <th>{{ $t("global.Action") }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr
                                        v-if="Object.keys(suppliers.data ?? {}).length"
                                        v-for="(item, index) in suppliers.data"
                                        :key="item.id"
                                    >
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.name_supplier }}</td>
                                        <td>{{ item.phone_supplier }}</td>
                                        <td>{{ item.address }}</td>
                                        <td>{{ Object.keys(item.orders ?? []).length }}</td>
                                        <td>{{sumOrders(item.orders) + " " + $t('global.LE') }}</td>

                                        <td>
                                            <a
                                                href="javascript:void(0);"
                                                class="btn btn-sm btn-info me-2"
                                                data-bs-toggle="modal"
                                                @click.prevent="showOrders = item.orders"
                                                data-bs-target="#showOrders"
                                            >
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
            <Pagination :limit="2" :data="suppliers" @pagination-change-page="getIncome">
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
                            {{ $t("global.Supplier Dues") }}
                        </h4>
                        <button
                            id="close-showOrders"
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
                                        <th>{{ $t("global.Order Number") }}</th>
                                        <th>{{ $t("global.Amount") }}</th>
                                        <th>{{ $t("global.Action") }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item, index) in showOrders" :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{Math.round((item.pivot.sub_total)*100) / 100 + " " + $t('global.LE')}}</td>
                                        <td>
                                            {{ item.pivot.dues == 0 ? $t('global.unpaid') : $t('global.paid') }}
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
    </div>
</template>

<script>
import {onMounted, inject, watch, ref} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup() {
        const emitter = inject("emitter");
        const {t} = useI18n({});

        // get packages
        let showOrders = ref({});
        let suppliers = ref({});
        let paid = ref(0);
        let fromDate = ref("");
        let toDate = ref("");
        let loading = ref(false);
        const search = ref("");

        let getIncome = (page = 1) => {
            loading.value = true;

            adminApi
                .get(
                    `/v1/dashboard/supplierDuesPlatformReport?page=${page}&search=${search.value}&paid=${paid.value}&from_date=${fromDate.value}&to_date=${toDate.value}`
                )
                .then((res) => {
                    suppliers.value = res.data.data.suppliers;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        };


        emitter.on("get_lang", () => {
            getIncome(search.value);
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getIncome();
            }
        });

        let printExpense = () => {
            var printContents = document.getElementById("printExpense").innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        };

        const sumOrders = (orders) => {
        let sum = 0 ;
        orders.forEach(element => {
            sum += element.pivot.sub_total
        });
        return  Math.round(sum * 100) / 100;
    }
        let dateFormat = (item) => {
            return new Date(item).toDateString();
        };

        return {
            sumOrders,
            suppliers,
            printExpense,
            fromDate,
            toDate,
            showOrders,
            paid,
            loading,
            getIncome,
            dateFormat,
            search,
        };
    },
    data() {
        return {
            locale: localStorage.getItem("langAdmin"),
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

.date-input{
    width: 135px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}
.select-input{
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
