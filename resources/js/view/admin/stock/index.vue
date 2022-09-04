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
            <h3 class="page-title">{{ $t("global.Stocks") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Stocks") }}</li>
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
                    <input type="search" v-model="search" class="custom" />
                  </div>
                  <div class="col-5 row justify-content-end">
                    <router-link
                      v-if="permission.includes('stock create')"
                      :to="{ name: 'createStock' }"
                      class="btn btn-custom btn-warning"
                    >
                      {{ $t("global.Add") }}
                    </router-link>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">{{ $t("global.Name") }}</th>
                      <th class="text-center">{{ $t("global.Governorate") }}</th>
                      <th class="text-center">{{ $t("global.Region") }}</th>
                      <th class="text-center">{{ $t("global.Title") }}</th>
                      <!-- <th class="text-center">{{ $t("global.Location") }}</th> -->
                      <th class="text-center">{{ $t("global.Phone") }}</th>
                      <th class="text-center">{{ $t("global.Email") }}</th>
                      <th class="text-center">{{ $t("global.Empolyee Name") }}</th>
                      <th class="text-center">{{ $t("global.Empolyee Phone") }}</th>
                      <th class="text-center">{{ $t("global.Shift Name") }}</th>
                      <th class="text-center">{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="stocks.length">
                    <tr v-for="(item, index) in stocks" :key="item.id">
                      <td class="text-center">{{ index + 1 }}</td>
                      <td class="text-center">{{ item.name }}</td>
                      <td class="text-center">{{ item.governorate }}</td>
                      <td class="text-center">{{ item.region }}</td>
                      <td class="text-center">{{ item.title }}</td>
                      <!-- <td class="text-center">{{ item.location }}</td> -->
                      <td class="text-center">{{ item.phone }}</td>
                      <td class="text-center">{{ item.email }}</td>
                      <td class="text-center">{{ item.employee.user.name }}</td>
                      <td class="text-center">{{ item.employee.user.phone}}</td>
                      <td class="text-center">{{ item.shift.name }}</td>
                      <!-- <td>
                        <a
                          href="#"
                          @click="activationShift(item.id, item.status, index)"
                        >
                          <span
                            :class="[
                              parseInt(item.status)
                                ? 'text-success hover'
                                : 'text-danger hover',
                            ]"
                            >{{
                              parseInt(item.status)
                                ? $t("global.Active")
                                : $t("global.Inactive")
                            }}</span
                          >
                        </a>
                      </td> -->
                      <td class="text-center">
                        <router-link
                          :to="{
                            name: 'editStock',
                            params: { id: item.id },
                          }"
                          v-if="permission.includes('stock edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </router-link>
                        <a
                          href="#"
                          @click="deleteStock(item.id, index)"
                          v-if="permission.includes('stock delete')"
                          data-bs-target="#staticBackdrop"
                          class="btn btn-sm btn-danger me-2"
                        >
                          <i class="far fa-trash-alt"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <th class="text-center" colspan="15">
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
      <Pagination
        :data="stocksPaginate"
        @pagination-change-page="getStock"
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
    <!-- /Page Wrapper -->
  </div>
</template>

<script>
import { onMounted, inject, watch, ref, computed } from "vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
  name: "index",
  setup() {
    const emitter = inject("emitter");
    const { t } = useI18n({});

    // get packages
    let stocks = ref([]);
    let stocksPaginate = ref({});
    let employee = ref([]);
    let loading = ref(false);
    const search = ref("");
    let store = useStore();

    let permission = computed(() => store.getters["authAdmin/permission"]);

    let getStock = (page = 1) => {
      loading.value = true;

      adminApi
        .get(`/v1/dashboard/stock?page=${page}&search=${search.value}`)
        .then((res) => {
          let l = res.data.data;
          stocksPaginate.value = l.stocks;
          stocks.value = l.stocks.data;
        })
        .catch((err) => {
          console.log(err.response.data);
        })
        .finally(() => {
          loading.value = false;
        });
    };

    onMounted(() => {
      getStock();
    });

    //
    emitter.on("get_lang", () => {
      getStock(search.value);
    });
    //

    watch(search, (search, prevSearch) => {
      if (search.length >= 0) {
        getStock();
      }
    });

    function deleteStock(id, index) {
      Swal.fire({
        title: `${t("global.AreYouSureDelete")}`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          adminApi
            .delete(`/v1/dashboard/stock/${id}`)
            .then((res) => {
              stocks.value.splice(index, index + 1);

              Swal.fire({
                icon: "success",
                title: `${t("global.DeletedSuccessfully")}`,
                showConfirmButton: false,
                timer: 1500,
              });
            })
            .catch((err) => {
              Swal.fire({
                icon: "error",
                title: `${t("global.ThereIsAnErrorInTheSystem")}`,
                text: `${t("global.YouCanNotDelete")}`,
              });
            });
        }
      });
    }

    // function activationStock(id, active, index) {
    //   Swal.fire({
    //     title: `${
    //       active
    //       ? t("global.AreYouSureInactive")
    //       : t("global.AreYouSureActive")
    //     }`,
    //     text: `${t("global.YouWontBeAbleToRevertThis")}`,
    //     icon: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#3085d6",
    //     cancelButtonColor: "#d33",
    //     confirmButtonText: t("global.Yeas"),
    //     cancelButtonText: t("global.No"),
    //   }).then((result) => {
    //     if (result.isConfirmed) {
    //       adminApi
    //         .get(`/v1/dashboard/activationStock/${id}`)
    //         .then((res) => {
    //           Swal.fire({
    //             icon: "success",
    //             title: `${
    //               active
    //                 ? t("global.InactiveSuccessfully")
    //                 : t("global.ActiveSuccessfully")
    //             }`,
    //             showConfirmButton: false,
    //             timer: 1500,
    //           });
    //           stocks.value[index]["status"] = active ? 0 : 1;
    //         })
    //         .catch((err) => {
    //           Swal.fire({
    //             icon: "error",
    //             title: `${t("global.ThereIsAnErrorInTheSystem")}`,
    //             text: `${t("global.YouCanNotModifyThisSafe")}`,
    //           });
    //         });
    //     }
    //   });
    // }

    return {
      getStock,
      loading,
      permission,
      search,
      deleteStock,
    //   activationStock,
      stocksPaginate,
      stocks,
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

.custom-img {
  width: 100px;
}
</style>
