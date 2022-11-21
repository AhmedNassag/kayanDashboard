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
            <h3 class="page-title">{{ $t("global.Alternative") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Alternative") }}</li>
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
                      v-if="permission.includes('alternative create')"
                      :to="{ name: 'createAlternative' }"
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
                      <th class="text-center">{{ $t("global.NameAr") }}</th>
                      <th class="text-center">{{ $t("global.NameEn") }}</th>
                      <th class="text-center">{{ $t("global.Category") }}</th>
                      <th class="text-center">{{ $t("global.SubCategory") }}</th>
                      <th class="text-center">{{ $t("global.Image") }}</th>
                      <th class="text-center">{{ $t("global.Status") }}</th>
                      <th class="text-center">{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="alternatives.length">
                    <tr v-for="(item, index) in alternatives" :key="item.id">
                        <td class="text-center">{{ index + 1 }}</td>
                        <td class="text-center">{{ item.nameAr }}</td>
                        <td class="text-center">{{ item.nameEn }}</td>
                        <td class="text-center">{{ item.category.name }}</td>
                        <td class="text-center">{{ item.sub_category.name }}</td>
                        <td class="text-center">
                        <img
                          :src="'/upload/alternative/' + item.media.file_name"
                          :alt="item.name"
                          class="custom-img"
                        />
                      </td>
                      <td class="text-center">
                        <a
                          href="#"
                          @click="activationAlternative(item.id, item.status, index)"
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
                            name: 'editAlternative',
                            params: { id: item.id },
                          }"
                          v-if="permission.includes('alternative edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </router-link>
                        <a
                          href="#"
                          @click="deleteAlternative(item.id, index)"
                          v-if="permission.includes('alternative delete')"
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
        :data="alternativesPaginate"
        @pagination-change-page="getAlternative"
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
//import { onMounted, watch, ref, computed } from "vue";
import { useStore } from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
  name: "index",
  setup() {
    //
    const emitter = inject("emitter");
    const { t } = useI18n({});
    //

    // get packages
    let alternatives = ref([]);
    let products = ref([]);
    let alternativesPaginate = ref({});
    let loading = ref(false);
    const search = ref("");
    let store = useStore();

    let permission = computed(() => store.getters["authAdmin/permission"]);

    let getAlternative = (page = 1) => {
      loading.value = true;

      adminApi
        .get(`/v1/dashboard/alternative?page=${page}&search=${search.value}`)
        .then((res) => {
          let l = res.data.data;
          alternativesPaginate.value = l.alternatives;
          alternatives.value = l.alternatives.data;
        })
        .catch((err) => {
          console.log(err.response.data);
        //   Swal.fire({
        //     icon: "error",
        //     title: `${t("global.ThereIsAnErrorInTheSystem")}`,
        //     text: `${t("global.YouCanNotDelete")}`,
        //   });
        })
        .finally(() => {
          loading.value = false;
        });
    };

    onMounted(() => {
      getAlternative();
    });

    emitter.on("get_lang", () => {
      getAlternative(search.value);
    });

    watch(search, (search, prevSearch) => {
      if (search.length >= 0) {
        getAlternative();
      }
    });

    function deleteAlternative(id, index) {
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
            .delete(`/v1/dashboard/alternative/${id}`)
            .then((res) => {
              alternatives.value.splice(index, index + 1);

              Swal.fire({
                icon: "success",
                title: `${t("global.DeletedSuccessfully")}`,
                showConfirmButton: false,
                timer: 1500,
              });
            })
            .catch((err) => {
            //   Swal.fire({
            //     icon: "error",
            //     title: `${t("global.ThereIsAnErrorInTheSystem")}`,
            //     text: `${t("global.YouCanNotDelete")}`,
            //   });
            });
        }
      });
    }

    function activationAlternative(id, active, index) {
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
            .get(`/v1/dashboard/activationAlternative/${id}`)
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
              alternatives.value[index]["status"] = active ? 0 : 1;
            })
            .catch((err) => {
            //   Swal.fire({
            //     icon: "error",
            //     title: `${t("global.ThereIsAnErrorInTheSystem")}`,
            //     text: `${t("global.YouCanNotModifyThisSafe")}`,
            //   });
            });
        }
      });
    }

    return {
      getAlternative,
      loading,
      permission,
      search,
      deleteAlternative,
      activationAlternative,
      alternativesPaginate,
      alternatives,
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
