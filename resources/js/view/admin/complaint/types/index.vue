<template>
    <div
      class="company-container"
      :class="[
        'page-wrapper',
        this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
      ]"
    >
      <typesOfComplaintsForm
        :selected_type_of_complaints_store="selected_type_of_complaints_store"
        @created="onCreated"
        @updated="onUpdated"
        @loading="loading = $event"
      />
      <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
          <div class="row align-items-center">
            <div class="col">
              <h3 class="page-title">{{ $t("global.Types of Complaints") }}</h3>
              <ul class="breadcrumb">
                <li class="breadcrumb-item">
                  <router-link :to="{ name: 'dashboard' }">
                    {{ $t("dashboard.Dashboard") }}
                  </router-link>
                </li>
                <li class="breadcrumb-item active">
                  {{ $t("global.Types of Complaints") }}
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
                      {{ $t("global.Search") }}:
                      <input type="search" v-model="text" class="custom" />
                    </div>
                    <div class="col-5 row justify-content-end">
                      <button
                        @click="onAddClicked()"
                        data-toggle="modal"
                        data-target="#typesOfComplaintsFormModal"
                        class="btn btn-custom btn-warning"
                      >
                        {{ $t("global.Add") }}
                      </button>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table mb-0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>{{ $t("global.NameAr") }}</th>
                        <th>{{ $t("global.NameEn") }}</th>
                        <th>{{ $t("global.Action") }}</th>
                      </tr>
                    </thead>
                    <tbody v-if="companies.length">
                      <tr v-for="(company, index) in companies" :key="company.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ company.name_ar }}</td>
                        <td>{{ company.name_en }}</td>

                        <td>
                          <a
                            href="#"
                            @click="onEditClicked(company, index)"
                            data-toggle="modal"
                            data-target="#typesOfComplaintsFormModal"
                            class="btn btn-sm btn-success me-2"
                          >
                            <i class="far fa-edit"></i>
                          </a>
                          <a
                            href="#"
                            @click="delete_selected_type_of_complaints_store(company, index)"
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
                        <th class="text-center" colspan="7">
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
        <Pagination :data="paginate" @pagination-change-page="get_selected_type_of_complaints_store">
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
  </template>

  <script>
  import { reactive, toRefs } from "@vue/reactivity";
  import { computed, provide, watch } from "@vue/runtime-core";
  import typesOfComplaintsStore from "./types-of-complaints-store";
  import typesOfComplaintsClient from "../../../../http-clients/types-of-complaints-client";
  import typesOfComplaintsForm from "./form";
  import { useI18n } from "vue-i18n";
  import { useStore } from "vuex";
  export default {
    components: {
      typesOfComplaintsForm,
    },
    setup() {
      const data = reactive({
        paginate: {},
        companies: [],
        text: "",
        timeout: null,
        selected_type_of_complaints_store: null,
        selected_type_of_complaints_storeIndex: 0,
        page: 1,
        pageSize: 5,
        loading: false,
      });
      const { t, locale } = useI18n({});
      provide("types_of_Complaints_store", typesOfComplaintsStore);
      let store = useStore();
      let permission = computed(() => store.getters["authAdmin/permission"]);
      created();
      //Methods
      function onAddClicked() {
        data.selected_type_of_complaints_store = null;
        //Make little delay to ensure that watcher that found in company form component
        // catch selected_type_of_complaints_store input prop
        setTimeout(() => {
          typesOfComplaintsStore.onFormShow = !typesOfComplaintsStore.onFormShow;
        }, 1);
      }
      function onEditClicked(company, index) {
        data.selected_type_of_complaints_store = company;
        data.selected_type_of_complaints_storeIndex = index;
        //Make little delay to ensure that watcher catch selected_type_of_complaints_store input prop
        //in company form component
        setTimeout(() => {
          typesOfComplaintsStore.onFormShow = !typesOfComplaintsStore.onFormShow;
        }, 1);
      }
      function get_selected_type_of_complaints_store(page = 1) {
        data.page = page;
        data.loading = true;
        typesOfComplaintsClient
          .getPage(data.page, data.pageSize, data.text)
          .then((response) => {
            data.loading = false;
            data.companies = response.data.companies.data;
            data.paginate = response.data.companies;
          })
          .catch((error) => {
            console.log(error.response);
          });
      }

      function onCreated(event) {
        get_selected_type_of_complaints_store()
      }
      function onUpdated(event) {
        get_selected_type_of_complaints_store()
      }
      function delete_selected_type_of_complaints_store(company, index) {
        Swal.fire({
          title: `${t("global.AreYouSureDelete")} (${company.name_ar})`,
          text: `${t("global.YouWontBeAbleToRevertThis")}`,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: t("global.Yeas"),
          cancelButtonText: t("global.No"),
        }).then((result) => {
          if (result.isConfirmed) {
            httpDeleteRequest(company, index);
          }
        });
      }
      function search() {
        // clear timeout variable
        clearTimeout(data.timeout);
        data.timeout = setTimeout(() => {
          get_selected_type_of_complaints_store();
        }, 500);
      }
      watch(
        () => data.text,
        () => {
          search();
        }
      );

      //Commons
      function httpDeleteRequest(company, index) {
        data.loading = true;
        typesOfComplaintsClient
          .delete(company.id)
          .then((response) => {
            data.loading = false;
            data.companies.splice(index, 1);
            if (data.companies.length == 0) {
              if (data.page > 1) {
                data.page--;
              }
              get_selected_type_of_complaints_store(data.page);
            }
            Swal.fire({
              icon: "success",
              title: `${t("global.DeletedSuccessfully")}`,
              showConfirmButton: false,
              timer: 1500,
            });
          })
          .catch((error) => {
            data.loading = false;
            Swal.fire({
              icon: "error",
              title: `${t("global.ThereIsAnErrorInTheSystem")}`,
              text: `${t("global.YouCanNotDelete")}`,
            });
          });
      }

      function created() {
        get_selected_type_of_complaints_store();
      }
      return {
        ...toRefs(data),
        onAddClicked,
        onEditClicked,
        get_selected_type_of_complaints_store,
        onCreated,
        onUpdated,
        delete_selected_type_of_complaints_store,
        search,
        permission,
      };
    },
  };
  </script>

  <style lang="scss" scoped>
  .company-container {
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
        color: #FFF;
      }
      .active {
        background: none;
        border: none;
      }
    }
  }
  </style>
