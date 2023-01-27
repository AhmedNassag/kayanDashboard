<template>
  <div
    class="supplier-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <notifications
      :position="this.$i18n.locale == 'ar' ? 'top left' : 'top right'"
    />

    <div class="content container-fluid">
           <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.Suppliers Dues") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
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
            <loader v-if="loading" />
            <div class="card-body">
              <div class="card-header pt-0">
                <div class="row justify-content-between">
                  <div class="col-5">
                      {{ $t('global.Search') }}:
                    <input type="search" v-model="searchSuppliers" class="custom w-75" />
                  </div>
                </div>
              </div>
              <div class="table-responsive">
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
                      <td>{{ item.phone }}</td>
                      <td>{{ item.address }}</td>
                      <td>{{ Object.keys(item.orders ?? []).length }}</td>
                      <td>{{sumOrders(item.orders) + " " + $t('global.LE') }}</td>
                      <td>
                        <a
                          href="javascript:void(0);"
                          class="btn btn-sm btn-info me-2"
                          data-bs-toggle="modal"
                          v-if="permission.includes('SupplierDues edit')"
                          @click.prevent="showOrders = item.orders"
                          data-bs-target='#showOrders'
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
      <Pagination  :limit="2" :data="suppliers" @pagination-change-page="getSuppliers">
        <template #prev-nav>
          <span>&lt; {{$t('global.Previous')}}</span>
        </template>
        <template #next-nav>
          <span>{{$t('global.Next')}} &gt;</span>
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
                <table
                        class="table table-center table-hover mb-0 datatable"
                      >
                        <thead>
                          <tr>
                            <th>{{ $t("global.Order Number") }}</th>
                            <th>{{ $t("global.Amount") }}</th>
                            <th>{{ $t("global.Action") }}</th>

                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item,index) in showOrders"
                            :key="item.id"
                          >

                          <td>{{item.id}}</td>
                          <td>{{Math.round((item.pivot.sub_total )*100) / 100 + " " + $t('global.LE')}}</td>
                          <td><button  v-if="permission.includes('SupplierDues edit')" class="btn btn-success btn-sm" @click.prevent="SupplierDues(item.pivot.supplier_id,item.pivot.order_id,index)">{{$t('global.Pay now')}}</button></td>

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
import { onMounted, computed ,watch ,ref } from "vue";
import { useStore } from "vuex";
import adminApi from "../../../api/adminAxios";
import { useI18n } from "vue-i18n";
export default {
  name: "index",

  setup() {
    let store = useStore();
    let {t} = useI18n();
    const showOrders = ref({});
    const loading = ref(false); //loading for button

    let suppliers = ref({}); //data for index page
    let debounce = ref({}); // for search
    const searchSuppliers = ref(""); //search in index page
    let permission = computed(() => store.getters["authAdmin/permission"]);
      //get all suppliers
      const getSuppliers = async (page = 1) => {
        loading.value = true;

        adminApi
            .get(`/v1/dashboard/dues_suppliers?page=${page}&search=${searchSuppliers.value}`)
            .then((res) => {
                suppliers.value = res.data.suppliers;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
    };

    watch(searchSuppliers, (search, prevSearch) => {
        clearTimeout(debounce.value);
        debounce.value = setTimeout(() => {
            getSuppliers();
        }, 500);
    });
     const sumOrders = (orders) => {
        let sum = 0 ;
        orders.forEach(element => {
            sum += element.pivot.sub_total
        });
        return  Math.round(sum * 100) / 100;
    }

     //suppliers doDues
     const SupplierDues = async(supplier_id,order_id,index)=>{
        Swal.fire({
            title: t(`global.AreYouSurePay`),
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
        }).then((result) => {
            if (result.isConfirmed) {
                adminApi
                    .post(`/v1/dashboard/doSupplierDues`,{supplier_id,order_id})
                    .then((res) => {
                        showOrders.value.splice(index, 1);
                        Swal.fire({
                            icon: "success",
                            title: t("global.Paid Successfully"),
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        if(Object.keys(showOrders.value ?? []).length == 0){
                            $('#showOrders').modal('hide')
                        }
                        getSuppliers();
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: 'error',
                            title: t('global.Unkown error'),
                        });
                    });
            }
        });
    }
    onMounted(() => {
      getSuppliers();
    });

    return { loading, getSuppliers, searchSuppliers, suppliers, permission, showOrders,SupplierDues,sumOrders };
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
.custom-modal .close span {
  top: 0 !important;
}
.modal-dialog {
  max-width: 1000px !important;
}
</style>
