<template>
  <div
    class="client-container"
    :class="['page-wrapper', this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '']"
  >
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("global.WebClients") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link :to="{ name: 'dashboard' }">
                  {{ $t("dashboard.Dashboard") }}
                </router-link>
              </li>
              <li class="breadcrumb-item active">{{ $t("global.WebClients") }}</li>
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
                  <div class="col-md-5 col-sm-12">
                    {{ $t("global.Search") }}:
                    <input type="search" v-model="search" class="custom" />
                  </div>
                  <div class="col-md-7 col-sm-12">
                    <a
                        href="javascript:void(0);"
                        class="btn btn-sm btn-info me-2"
                        data-bs-toggle="modal"
                        @click.prevent="notification_type='toAll'"
                        data-bs-target='#sendNotification'
                      >
                        {{$t('global.Send Notification to All Clients')}}<i class="fa fa-comment-dots"></i>
                      </a>
                  </div>

                </div>
              </div>
              <div class="table-responsive">
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>{{ $t("global.Name") }}</th>
                      <th>{{ $t("global.Phone") }}</th>
                      <th>{{ $t("global.Email") }}</th>
                      <th>{{ $t("global.Status") }}</th>
                      <th>{{ $t("global.Show") }}</th>
                    </tr>
                  </thead>
                  <tbody v-if="Object.keys(clients.data ?? []).length">
                    <tr v-for="(client, index) in clients.data" :key="client.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ client.name }}</td>
                      <td>{{ client.phone }}</td>
                      <td>{{ client.email }}</td>
                      <td>
                        <button
                          class="active"
                          href="#"
                          @click="
                            toggleActivation(
                              client.id,
                              client.name,
                              client.status,
                              index
                            )
                          "
                        >
                          <span
                            :class="[
                              parseInt(client.status)
                                ? 'text-success hover'
                                : 'text-danger hover',
                            ]"
                            >{{
                              parseInt(client.status)
                                ? $t("global.Active")
                                : $t("global.Inactive")
                            }}</span
                          >
                        </button>
                      </td>
                      <td>
                        <router-link :to="{name:'client_profile',params:{id:client.id,lang:this.$i18n.locale}}" class="btn btn-success btn-sm">
                            <i class="fa fa-eye"></i>
                        </router-link>
                        <a
                              href="javascript:void(0);"
                              class="btn btn-sm btn-info me-2"
                              data-bs-toggle="modal"
                              @click.prevent="client_notification_id = client.id;notification_type='client'"
                              data-bs-target='#sendNotification'
                            >
                              <i class="fa fa-comment-dots"></i>
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
      <Pagination :data="clients" @pagination-change-page="getClients">
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



     <!-- Edit Modal -->
     <div class="modal fade custom-modal" id="sendNotification">
          <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">
                  {{ $t("global.Send Notification") }}
                </h4>
                <button
                  id="close-sendNotification"
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

                      <div class="form-group col-12">
                        <label for="title">{{$t('global.Title')}}</label>
                        <input type="text" :class="['form-control',errors.title?'is-invalid':'']" v-model="title">
                        <span v-if="errors.title" class="text-danger">{{errors.title}}</span>
                      </div>
                      <div class="form-group col-12">
                        <label for="body">{{$t('global.Message')}}</label>
                        <textarea :class="['form-control' ,errors.notification ? 'is-invalid':'' ]" id="body" cols="30" rows="10" v-model="notification"></textarea>
                        <span v-if="errors.notification" class="text-danger">{{errors.notification}}</span>
                      </div>
                      <div class="form-group col-12">
                        <button class="btn btn-warning" @click.prevent="sendNotification">
                            {{$t('global.Submit')}}
                        </button>
                      </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /Edit Modal -->
</template>

<script>
import { computed, onMounted, provide, watch,ref } from "@vue/runtime-core";
import adminApi from "../../../api/adminAxios";
import clientClient from "../../../http-clients/client-client";
import { useI18n } from "vue-i18n";
import { useRouter } from 'vue-router'
import { notify } from "@kyvg/vue3-notification";

export default {
  setup() {
    const {t,locale} = useI18n()
    const errors = ref({})
    const clients = ref({})
    const router = useRouter()
    const debounce = ref('')
    const title = ref('')
    const notification = ref('')
    const notification_type = ref('')
    const client_notification_id = ref(0)
    const search = ref('')
    const getClients = async(page = 1 ) => {
      adminApi.get(`/v1/dashboard/web_clients?page=${page}&search=${search.value}`).then((response) => {
        clients.value = response.data.clients;
      })
    }

    onMounted(() => {
      getClients()
    })

    function toggleActivation(id, name, active, index) {
      Swal.fire({
        title: `${
          active ? t("global.AreYouSureInactive") : t("global.AreYouSureActive")
        }  (${name})`,
        text: `${t("global.YouWontBeAbleToRevertThis")}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: t("global.Yeas"),
        cancelButtonText: t("global.No"),
      }).then((result) => {
        if (result.isConfirmed) {
          httpToggleActivation(id, active, index);
        }
      });
    }

    function httpToggleActivation(id, active, index) {
      clientClient
        .toggleActivation(id)
        .then((res) => {
          Swal.fire({
            icon: "success",
            title: `${
              active ? t("global.InactiveSuccessfully") : t("global.ActiveSuccessfully")
            }`,
            showConfirmButton: false,
            timer: 1500,
          });
          clients.value['data'][index]["status"] = active == 0 ? 1 : 0;
        })
        .catch((err) => {
          console.log(err.response);
          Swal.fire({
            icon: "error",
            title: `${t("global.ThereIsAnErrorInTheSystem")}`,
            text: `${t("global.YouCanNotModifyThisSafe")}`,
          });
        });
    }
    watch(search,()=>{
      // clear timeout variable
      clearTimeout(debounce.value);
      debounce.value = setTimeout(() => {
          getClients();
        }, 500);
    })


    const sendNotification = async () => {
      errors.value ={}
      let uri = notification_type.value == 'client' ? '/v1/dashboard/sendNotificationToClient' : '/v1/dashboard/sendNotificationToAllClients'
        adminApi.post(uri,{notification:notification.value,title:title.value,user_id:client_notification_id.value}).then((response) => {
            $('#close-sendNotification').click()
            Swal.fire({
                icon: "success",
                title: `تم الارسال بنجاح <i class="fas fa-check-circle"></i>`,
                showConfirmButton: false,
                timer: 1500,
              });

        }).catch((e) => {
          if(e.response &&e.response.data.title&& e.response.data.title[0])
            errors.value['title']= e.response.data.title[0]
          if(e.response &&e.response.data.notification&& e.response.data.notification[0])
            errors.value['notification']= e.response.data.notification[0]
          if(e.response && e.response.data.user_id && e.response.data.user_id[0])
            errors.value['user_id']= e.response.data.user_id[0]

        })
    }



    return {
      getClients,
      toggleActivation,
      sendNotification,
      client_notification_id,title,notification,errors,
      clients,notification_type,search
    };
  },
};
</script>

<style lang="scss" scoped>
.client-container {
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
      color: #fff;
    }
    .active {
      background: none;
      border: none;
    }
  }
}
</style>
