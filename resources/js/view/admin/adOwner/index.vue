<template>
  <div :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.Ad Owners") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Ad Owners") }}</li>
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
                      v-if="permission.includes('adOwner create')"
                      :to="{ name: 'createAdOwner' }"
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
                      <th class="text-center">{{ $t("global.Phone") }}</th>
                      <th class="text-center">{{ $t("global.Commercial Record") }}</th>
                      <th class="text-center">{{ $t("global.Status") }}</th>
                      <th class="text-center">{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="adOwners.length">
                    <tr v-for="(item, index) in adOwners" :key="item.id">
                      <td class="text-center">{{ index + 1 }}</td>
                      <td class="text-center">{{ item.name }}</td>
                      <td class="text-center">{{ item.phone }}</td>
                      <td class="text-center">
                        <img
                          :src="'/upload/adOwnerCommercialRecord/' + item.media.file_name"
                          :alt="item.name"
                          class="custom-img"
                          v-if="item.media"
                        />
                        <img
                          :src="`/admin/img/company/img-1.png`"
                          :alt="item.name"
                          class="custom-img"
                          v-else
                        />
                      </td>
                      <!-- <td class="text-center">
                        <img
                          :src="'/upload/adOwnerTaxCard/' + item.media.file_name"
                          :alt="item.name"
                          class="custom-img"
                        />
                      </td> -->
                      <td class="text-center">
                        <a
                          href="#"
                          @click="activationAdOwner(item.id, item.status, index)"
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
                      </td>
                      <td class="text-center">
                        <router-link
                          :to="{
                            name: 'editAdOwner',
                            params: { id: item.id },
                          }"
                          v-if="permission.includes('adOwner edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </router-link>
                        <a
                          href="#"
                          @click="deleteAdOwner(item.id, index)"
                          v-if="permission.includes('adOwner delete')"
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
                      <th class="text-center" colspan="5">
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
        :data="adOwnersPaginate"
        @pagination-change-page="getAdOwner"
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
    let adOwners = ref([]);
    let adOwnersPaginate = ref({});
    let loading = ref(false);
    const search = ref("");
    let store = useStore();

    let permission = computed(() => store.getters["authAdmin/permission"]);

    let getAdOwner = (page = 1) => {
      loading.value = true;

      adminApi
        .get(`/v1/dashboard/adOwner?page=${page}&search=${search.value}`)
        .then((res) => {
          let l = res.data.data;
          adOwnersPaginate.value = l.adOwners;
          adOwners.value = l.adOwners.data;
        })
        .catch((err) => {
          console.log(err.response.data);
        })
        .finally(() => {
          loading.value = false;
        });
    };

    onMounted(() => {
      getAdOwner();
    });

    emitter.on("get_lang", () => {
      getAdOwner(search.value);
    });

    watch(search, (search, prevSearch) => {
      if (search.length >= 0) {
        getAdOwner();
      }
    });

    function deleteAdOwner(id, index) {
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
            .delete(`/v1/dashboard/adOwner/${id}`)
            .then((res) => {
              adOwners.value.splice(index, index + 1);

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

    function activationAdOwner(id, active, index) {
      Swal.fire({
        title: `${
          active
          ? t("global.AreYouSureInactive")
          : t("global.AreYouSureActive")
        }`,
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
            .get(`/v1/dashboard/activationAdOwner/${id}`)
            .then((res) => {
              Swal.fire({
                icon: "success",
                title: `${
                  active
                    ? t("global.InactiveSuccessfully")
                    : t("global.ActiveSuccessfully")
                }`,
                showConfirmButton: false,
                timer: 1500,
              });
              adOwners.value[index]["status"] = active ? 0 : 1;
            })
            .catch((err) => {
              Swal.fire({
                icon: "error",
                title: `${t("global.ThereIsAnErrorInTheSystem")}`,
                text: `${t("global.YouCanNotModifyThisSafe")}`,
              });
            });
        }
      });
    }

    return {
      getAdOwner,
      loading,
      permission,
      search,
      deleteAdOwner,
      activationAdOwner,
      adOwnersPaginate,
      adOwners,
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
