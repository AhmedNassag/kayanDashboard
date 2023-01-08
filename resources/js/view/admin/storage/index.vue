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
            <h3 class="page-title">{{ $t("global.Storages") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Storages") }}</li>
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
                      v-if="permission.includes('storage create')"
                      :to="{ name: 'createStorage' }"
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
                      <th>#</th>
                      <th>{{ $t("global.Name") }}</th>
                      <th>{{ $t("global.Code Number") }}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="storages.length">
                    <tr v-for="(item, index) in storages" :key="item.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ item.name }}</td>
                      <td>{{item.code}}</td>
                      <td>
                        <router-link
                          :to="{
                            name: 'editStorage',
                            params: { id: item.id },
                          }"
                          v-if="permission.includes('storage edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </router-link>
                        <a
                          href="#"
                          @click="deleteStorage(item.id, index)"
                          v-if="permission.includes('storage delete')"
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
        :data="storagesPaginate"
        @pagination-change-page="getStorage"
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
    let storages = ref([]);
    let storagesPaginate = ref({});
    let loading = ref(false);
    const search = ref("");
    let store = useStore();

    let permission = computed(() => store.getters["authAdmin/permission"]);

    let getStorage = (page = 1) => {
      loading.value = true;

      adminApi
        .get(`/v1/dashboard/storage?page=${page}&search=${search.value}`)
        .then((res) => {
          let l = res.data.data;
          storagesPaginate.value = l.storages;
          storages.value = l.storages.data;
        })
        .catch((err) => {
          console.log(err.response.data);
        })
        .finally(() => {
          loading.value = false;
        });
    };

    onMounted(() => {
      getStorage();
    });

    emitter.on("get_lang", () => {
      getStorage(search.value);
    });

    watch(search, (search, prevSearch) => {
        if (search.length >= 0) {
            getStorage();
        }
    });

    function deleteStorage(id, index) {
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
            .delete(`/v1/dashboard/storage/${id}`)
            .then((res) => {
              storages.value.splice(index,1);

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

    return {
      getStorage,
      loading,
      permission,
      search,
      deleteStorage,
      storagesPaginate,
      storages,
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
  color: #FFF;
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
