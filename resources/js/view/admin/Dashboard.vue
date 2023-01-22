<template>
  <div :class="['page-wrapper', 'page-wrapper-ar']">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="page-title">{{ $t("sidebar.Dashboard") }}</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item active">
                {{ $t("sidebar.Dashboard") }}
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- /Page Header -->

      <div class="row"  v-if="user.role_name.includes('SuperAdmin')">
        <div class="col-md-12">
          <!--/Wizard-->
          <div class="row">
            <div class="col-md-3 d-flex">
              <div class="card wizard-card flex-fill">
                <div class="card-body">
                  <p class="text-primary mt-0 mb-2">
                    {{ $t("global.WebClients") }}
                  </p>
                  <h5>{{ statistics_data.number_of_clients }}</h5>
                  <p>
                    <router-link :to="{ name: 'web_clients' }">
                      {{ $t("global.view details") }}
                    </router-link>
                  </p>
                  <span
                    :class="['dash-widget-icon bg-1', 'dash-widget-icon-ar']"
                  >
                    <i class="fas fa-users"></i>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-3 d-flex">
              <div class="card wizard-card flex-fill">
                <div class="card-body">
                  <p class="text-primary mt-0 mb-2">
                    {{ $t("global.Supplier Products") }}
                  </p>
                  <h5>{{ statistics_data.number_of_products }}</h5>
                  <p>
                    <router-link :to="{ name: 'indexPrice' }">
                      {{ $t("global.view details") }}
                    </router-link>
                  </p>
                  <span
                    :class="['dash-widget-icon bg-1', 'dash-widget-icon-ar']"
                  >
                    <i class="fas fa-th-large"></i>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-3 d-flex">
              <div class="card wizard-card flex-fill">
                <div class="card-body">
                  <p class="text-primary mt-0 mb-2">
                    {{ $t("global.Completed Orders") }}
                  </p>
                  <h5>{{ statistics_data.orders_count }}</h5>
                  <p>
                    <router-link :to="{ name: 'indexOrderOnline' }">{{
                      $t("global.view details")
                    }}</router-link>
                  </p>

                  <span
                    :class="['dash-widget-icon bg-1', 'dash-widget-icon-ar']"
                  >
                    <i class="fas fa-bezier-curve"></i>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-3 d-flex">
              <div class="card wizard-card flex-fill">
                <div class="card-body">
                  <p class="text-primary mt-0 mb-2">
                    {{ $t("global.Total Amount For Completed Orders") }}
                  </p>
                  <h5>{{ statistics_data.orders_total_amount }}</h5>

                  <span
                    :class="['dash-widget-icon bg-1', 'dash-widget-icon-ar']"
                  >
                    <i class="fas fa-money-bill-wave"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 row">
          <!--/Wizard-->
          <div class="col-lg-8 d-flex">
            <div class="card w-100">
              <div class="card-body pt-0 pb-2">
                <div class="card-header">
                  <h5 class="card-title">
                    {{ $t("global.Number of Orders Current Month and Last Month") }}
                  </h5>
                </div>
                <div id="chart" class="mt-4"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex">
            <div class="card w-100">
              <div class="card-body pt-0">
                <div class="mt-3">
                  <div class="table-responsive">
                    <table class="table table-center table-hover mb-0">
                      <thead>
                        <tr>
                          <th class="text-nowrap">الاسبوع</th>
                          <th>الشهر الحالي</th>
                          <th class="text-end">الشهر الماضي</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(week,key) in  weeks_names" :key="week">
                          <td class="text-nowrap">{{ week }}</td>
                          <td>{{ number_of_orders_current_month[key] }}</td>
                          <td class="text-end">{{ number_of_orders_last_month[key] }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>



        <div class="col-md-12 row">
          <!--/Wizard-->
          <div class="col-lg-8 d-flex">
            <div class="card w-100">
              <div class="card-body pt-0 pb-2">
                <div class="card-header">
                  <h5 class="card-title">
                    {{ $t("global.Total amount of Orders for Current Month and Last Month") }}
                  </h5>
                </div>
                <div id="chart2" class="mt-4"></div>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex">
            <div class="card w-100">
              <div class="card-body pt-0">
                <div class="mt-3">
                  <div class="table-responsive">
                    <table class="table table-center table-hover mb-0">
                      <thead>
                        <tr>
                          <th class="text-nowrap">الاسبوع</th>
                          <th>الشهر الحالي</th>
                          <th class="text-end">الشهر الماضي</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(week,key) in  weeks_names" :key="week">
                          <td class="text-nowrap">{{ week }}</td>
                          <td>{{ total_amount_of_orders_current_month[key] }}</td>
                          <td class="text-end">{{ total_amount_of_orders_last_month[key] }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /Page Wrapper -->
  </div>
</template>

<script>
import { onBeforeMount, onMounted, ref ,computed} from "@vue/runtime-core";
import adminApi from "../../api/adminAxios";
import { useStore } from "vuex";

export default {
  setup() {
    let store = useStore();

    let user = computed(() => store.getters["authAdmin/user"]);
    const statistics_data = ref({});
    const number_of_orders_current_month = ref([]);
    const number_of_orders_last_month = ref([]);
    const total_amount_of_orders_current_month = ref([]);
    const total_amount_of_orders_last_month = ref([]);
    // const statistics_data = ref({});
    const weeks_names = [
      "الاسبوع الاول",
      "الاسبوع الثاني",
      "الاسبوع الثالث",
      "الاسبوع الرابع",
      "الاسبوع الخامس",
    ];
    onMounted(() => {
        if(user.value.role_name.includes('SuperAdmin'))
            adminApi
            .get("v1/dashboard/dashboard-statistics")
            .then((response) => {
            statistics_data.value = response.data;
            })
            .finally(() => {
          number_of_orders_current_month.value = getLengthForOrdersPerWeek(
            statistics_data.value.orders_for_current_month
          );
          number_of_orders_last_month.value = getLengthForOrdersPerWeek(
            statistics_data.value.orders_for_last_month
          );
          total_amount_of_orders_current_month.value = getTotalAmountForOrdersPerWeek(
            statistics_data.value.orders_for_current_month
          );
          total_amount_of_orders_last_month.value = getTotalAmountForOrdersPerWeek(
            statistics_data.value.orders_for_last_month
          );
          var chart = new ApexCharts(
            document.querySelector("#chart"),
            chartOption(
              number_of_orders_current_month.value,
              number_of_orders_last_month.value
            )
          );
          chart.render();

          var chart2 = new ApexCharts(
            document.querySelector("#chart2"),
            chartOption(
                total_amount_of_orders_current_month.value,
                total_amount_of_orders_last_month.value
            )
          );
          chart2.render();
        });
    });

    function chartOption(data_current_month, data_last_month) {
      return {
        series: [
          {
            name: "الشهر الحالي",
            color: "#ff5b37",
            data: data_current_month,
          },
          {
            name: "الشهر الماضي",
            color: "#000",
            data: data_last_month,
          },
        ],
        chart: {
          height: 335,
          type: "area",
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: "smooth",
        },

        xaxis: {
          type: "text",
          categories: weeks_names,
        },
      };
    }

    function getLengthForOrdersPerWeek(object) {
      // let object_keys = Object.keys(object);
      let data = [];
      data.push(object["First Week"] ? object["First Week"].length : 0);
      data.push(object["Second Week"] ? object["Second Week"].length : 0);
      data.push(object["Third Week"] ? object["Third Week"].length : 0);
      data.push(object["Fourth Week"] ? object["Fourth Week"].length : 0);
      data.push(object["Fifth Week"] ? object["Fifth Week"].length : 0);

      return data;
    }
    function getTotalAmountForOrdersPerWeek(object) {
      // let object_keys = Object.keys(object);
      let data = [];
      data.push(object["First Week"] ? sumTotalAmountPerWeek(object["First Week"]) : 0);
      data.push(object["Second Week"] ? sumTotalAmountPerWeek(object["Second Week"]) : 0);
      data.push(object["Third Week"] ? sumTotalAmountPerWeek(object["Third Week"]) : 0);
      data.push(object["Fourth Week"] ? sumTotalAmountPerWeek(object["Fourth Week"]) : 0);
      data.push(object["Fifth Week"] ? sumTotalAmountPerWeek(object["Fifth Week"]) : 0);

      return data;
    }

    function sumTotalAmountPerWeek(object){
        let total_amount = 0
        for( var el in object ) {
            total_amount += parseFloat( object[el]['total_amount'] );
        }
        return total_amount;
    }

    return {
      statistics_data,
      number_of_orders_current_month,
      number_of_orders_last_month,
      total_amount_of_orders_current_month,
      total_amount_of_orders_last_month,
      weeks_names,
      user
    };
  },
};
</script>

<style scoped>
.bg-1 {
  /* background: #fcb00c !important; */
  background: #0e67d0 !important;
}
.bg-1 i {
  color: #fff !important;
}

.dash-widget-icon-ar {
  right: unset;
  left: -10px;
}
</style>
