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
            <h3 class="page-title">{{ $t("global.Category") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Category") }}</li>
            </ul>
          </div>
        </div>
      </div>
      <!-- /Page Header -->


      <!--Start Statistics-->
      <div class="row">
        <div class="col-md-12">
            <!--/Wizard-->
            <div class="row">
                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body text-center">
                            <p class="text-primary mt-0 mb-2">{{ $t("global.Categories") }}</p>
                            <h5>{{categories.length}}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body text-center">
                            <p class="text-primary mt-0 mb-2">{{ $t("global.activeCategories") }}</p>
                            <h5>{{activeCategories.length}}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body text-center">
                            <p class="text-primary mt-0 mb-2">{{ $t("global.notActiveCategories") }}</p>
                            <h5>{{notActiveCategories.length}}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body text-center">
                            <p class="text-primary mt-0 mb-2">{{ $t("global.Products") }}</p>
                            <h5>{{products.length}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <!--/Wizard-->
        </div>
    </div>
      <!--End Statistics-->


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
                      v-if="permission.includes('department create')"
                      :to="{ name: 'createCategory' }"
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
                      <th>{{ $t("global.Image") }}</th>
                      <th>{{ $t("global.Code Number") }}</th>
                      <th>{{ $t("global.Status") }}</th>
                      <th>{{ $t("global.Created_At") }}</th>
                      <th>{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="categories.length">
                    <tr v-for="(item, index) in categories" :key="item.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ item.name }}</td>
                      <td>
                        <img
                          :src="'/upload/category/' + item.media.file_name"
                          :alt="item.name"
                          class="custom-img"
                        />
                      </td>
                      <td>{{ item.code }}</td>
                      <td>
                        <a
                          href="#"
                          @click="activationCategory(item.id, item.status, index)"
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
                      <td>{{ item.added_at }}</td>
                      <td>
                        <router-link
                          :to="{
                            name: 'editCategory',
                            params: { id: item.id },
                          }"
                          v-if="permission.includes('department edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          <i class="far fa-edit"></i>
                        </router-link>
                        <a
                          href="#"
                          @click="deleteCategory(item.id, index)"
                          v-if="permission.includes('department delete')"
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
        :data="categoriesPaginate"
        @pagination-change-page="getCategory"
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
    let categories = ref([]);
    let activeCategories = ref([]);
    let notActiveCategories = ref([]);
    let products = ref([]);
    let categoriesPaginate = ref({});
    let loading = ref(false);
    const search = ref("");
    let store = useStore();

    let permission = computed(() => store.getters["authAdmin/permission"]);

    let getCategory = (page = 1) => {
      loading.value = true;

      adminApi
        .get(`/v1/dashboard/category?page=${page}&search=${search.value}`)
        .then((res) => {
          let l = res.data.data;
          categoriesPaginate.value = l.categories;
          categories.value = l.categories.data;
          activeCategories.value = l.activeCategories;
          notActiveCategories.value = l.notActiveCategories;
          products.value = l.products;
        })
        .catch((err) => {
          console.log(err.response.data);
          Swal.fire({
            icon: "error",
            title: `${t("global.ThereIsAnErrorInTheSystem")}`,
            text: `${t("global.YouCanNotDelete")}`,
          });
        })
        .finally(() => {
          loading.value = false;
        });
    };

    onMounted(() => {
      getCategory();
    });

    //
    emitter.on("get_lang", () => {
      getCategory(search.value);
    });
    //

    watch(search, (search, prevSearch) => {
      if (search.length >= 0) {
        getCategory();
      }
    });

    function deleteCategory(id, index) {
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
            .delete(`/v1/dashboard/category/${id}`)
            .then((res) => {
              categories.value.splice(index, index + 1);

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

    function activationCategory(id, active, index) {
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
            .get(`/v1/dashboard/activationCategory/${id}`)
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
              categories.value[index]["status"] = active ? 0 : 1;
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
      getCategory,
      loading,
      permission,
      search,
      deleteCategory,
      activationCategory,
      categoriesPaginate,
      categories,
      activeCategories,
      notActiveCategories,
      products,
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
