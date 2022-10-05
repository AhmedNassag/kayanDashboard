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
            <h3 class="page-title">{{ $t("global.Complaints") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.Complaints") }}</li>
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
                      v-if="permission.includes('complaint create')"
                      :to="{ name: 'createComplaint' }"
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
                      <th class="text-center">{{ $t("global.Type") }}</th>
                      <th class="text-center">{{ $t("global.Content") }}</th>
                      <th class="text-center">{{ $t("global.Reply") }}</th>
                      <th class="text-center">{{ $t("global.Action") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="complaints.length">
                    <tr v-for="(item, index) in complaints" :key="item.id">
                      <td class="text-center">{{ index + 1 }}</td>
                      <td class="text-center">{{ item.user.name }}</td>
                      <td class="text-center" v-if="item.user.phone">{{ item.user.phone }}</td>
                      <td class="text-center" v-else>{{ $t("global.Not Found") }}</td>
                      <td class="text-center" v-if="item.type">{{ item.type }}</td>
                      <td class="text-center" v-else>{{ $t("global.Not Found") }}</td>
                      <td class="text-center">{{ item.content }}</td>
                      <td class="text-center" v-if="item.reply">{{ item.reply }}</td>
                      <td class="text-center" v-else>{{ $t("global.Not Found") }}</td>
                      <td class="text-center" v-if="item.reply">{{ item.responser.name }}</td>
                      <td class="text-center" v-else>{{ $t("global.Not Found") }}</td>
                      <td class="text-center">
                        <router-link
                          :to="{
                            name: 'editComplaint',
                            params: { id: item.id },
                          }"
                          v-if="permission.includes('complaint edit')"
                          class="btn btn-sm btn-success me-2"
                        >
                          {{ $t("global.Edit") }}
                          <i class="far fa-edit"></i>
                        </router-link>
                        <a
                          href="#"
                          @click="deleteComplaint(item.id, index)"
                          v-if="permission.includes('complaint delete')"
                          data-bs-target="#staticBackdrop"
                          class="btn btn-sm btn-danger me-2"
                        >
                            {{ $t("global.Delete") }}
                          <i class="far fa-trash-alt"></i>
                        </a>
                        <!-- Start Reply -->
                        <router-link
                            :to="{
                                name: 'showComplaint',
                                params: { id: item.id },
                            }"
                            v-if="item.reply"
                            class="btn btn-sm btn-secondary me-2"
                        >
                            {{ $t("global.Reply Added") }}
                            <i class="far fa-check-square" aria-hidden="true"></i>
                        </router-link>

                        <router-link
                            :to="{
                                name: 'replyComplaint',
                                params: { id: item.id },
                            }"
                            v-else
                            class="btn btn-sm btn-info me-2"
                        >
                            {{ $t("global.Add Reply") }}
                            <i class="far fa-comment"></i>
                        </router-link>
                        <!-- End Reply -->
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
        :data="complaintsPaginate"
        @pagination-change-page="getComplaint"
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
    let complaints = ref([]);
    let complaintsPaginate = ref({});
    let loading = ref(false);
    const search = ref("");
    let store = useStore();

    let permission = computed(() => store.getters["authAdmin/permission"]);

    let getComplaint = (page = 1) => {
      loading.value = true;

      adminApi
        .get(`/v1/dashboard/complaint?page=${page}&search=${search.value}`)
        .then((res) => {
          let l = res.data.data;
          complaintsPaginate.value = l.complaints;
          complaints.value = l.complaints.data;
        })
        .catch((err) => {
          console.log(err.response.data);
        })
        .finally(() => {
          loading.value = false;
        });
    };

    onMounted(() => {
      getComplaint();
    });

    emitter.on("get_lang", () => {
      getComplaint(search.value);
    });

    watch(search, (search, prevSearch) => {
        if (search.length >= 0) {
            getComplaint();
        }
    });

    function deleteComplaint(id, index) {
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
            .delete(`/v1/dashboard/complaint/${id}`)
            .then((res) => {
                complaints.value.splice(index, index + 1);

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
      getComplaint,
      loading,
      permission,
      search,
      deleteComplaint,
      complaintsPaginate,
      complaints,
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
