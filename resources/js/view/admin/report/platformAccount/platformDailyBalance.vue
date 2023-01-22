<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.DailyBalance') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.Reports') }} </li>
                            <li class="breadcrumb-item active">{{ $t('global.DailyBalance') }}</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->
            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <loader v-if="loading"/>
                        <div class="card-body" id="printExpense">
                            <div class="card-header pt-0">
                                <div class="row justify-content-between">
                                    <div class="col-12 row justify-content-end">
                                        <form @submit.prevent="getIncome" class="needs-validation">
                                            <div class="form-group row">

                                                <div class="col-md-3">
                                                    <label >{{$t('global.FromDate')}}</label>
                                                    <input type="date" class="form-control date-input"
                                                           v-model="fromDate">
                                                </div>

                                                <div class="col-md-3">
                                                    <label >{{$t('global.ToDate')}}</label>
                                                    <input type="date" class="form-control date-input"
                                                           v-model="toDate">
                                                </div>

                                                <div class="col-md-5">

                                                    <label>{{$t('treasury.ChooseTreasury')}} </label>

                                                    <select v-model="treasury_id" class="form-select select-input">
                                                        <option v-for="treasury in treasuries" :kay="treasury.id" :value="treasury.id">{{treasury.name}}</option>
                                                    </select>

                                                </div>

                                                <div class="col-md-1">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Submit')}}</button>
                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                    <div class="col-12 row mt-3">
                                        <div class="col-12 d-flex justify-content-center">
                                            <button @click="printExpense" class="btn btn-success print-button">
                                                {{$t('global.Print')}}
                                                <i class="fa fa-print"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row m-0">
                                        <div class="col-12 head-table">
                                            <h3>{{$t('global.TotalIncome')}}:{{parseFloat(Math.round(totalIncome))}}</h3>
                                        </div>
                                        <div class="col-12">
                                            <div class="table-responsive" >
                                                <table class="table mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ $t('global.Band Name') }}</th>
                                                        <th>{{ $t('global.Amount') }}</th>
                                                        <th>{{ $t('global.NameOfThePayer') }}</th>
                                                        <th>{{ $t('global.For') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody v-if="incomes.length">
                                                    <tr v-for="(item,index) in incomes" :key="item.id">
                                                        <td>{{ index + 1 }}</td>
                                                        <td>{{ item.income.name }}</td>
                                                        <td>{{ item.amount }}</td>
                                                        <td>{{ item.payer }}</td>
                                                        <td>{{ item.notes }}</td>

                                                    </tr>
                                                    </tbody>
                                                    <tbody v-else>
                                                        <tr >
                                                            <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="row m-0">
                                        <div class="col-12 head-table">
                                            <h3>{{$t('global.TotalExpenses')}}:{{parseFloat(Math.round(totalExpense))}}</h3>
                                        </div>
                                        <div class="col-12">
                                            <div class="table-responsive" >
                                                <table class="table mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ $t('global.Band Name') }}</th>
                                                        <th>{{ $t('global.Amount') }}</th>
                                                        <th>{{ $t('global.RecipientsName') }}</th>
                                                        <th>{{ $t('global.For') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody v-if="expenses.length">
                                                    <tr v-for="(item,index) in expenses" :key="item.id">
                                                        <td>{{ index + 1 }}</td>
                                                        <td>{{ item.expense.name }}</td>
                                                        <td>{{ item.amount }}</td>
                                                        <td>{{ item.payer }}</td>
                                                        <td>{{ item.notes }}</td>
                                                    </tr>

                                                    </tbody>
                                                    <tbody v-else>
                                                        <tr >
                                                            <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
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
            </div>
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, inject, watch, ref} from "vue";
import adminApi from "../../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup() {
        const emitter = inject('emitter');
        const {t} = useI18n({});

        // get packages
        let incomes = ref([]);
        let expenses = ref([]);
        let treasuries = ref([]);
        let fromDate = ref('');
        let toDate = ref('');
        let treasury_id = ref('');
        let loading = ref(false);
        let totalIncome = ref(0);
        let totalExpense = ref(0);

        let getIncome = () => {

            loading.value = true;
            if (!fromDate.value){
                fromDate.value = new Date().toISOString().split('T')[0];
            }
            if (!toDate.value){
                toDate.value = new Date().toISOString().split('T')[0];
            }
            totalIncome.value = 0;
            totalExpense.value = 0;

            adminApi.get(`/v1/dashboard/dailyBalancePlatformReport?&treasury_id=${treasury_id.value}&from_date=${fromDate.value}&to_date=${toDate.value}`)
                .then((res) => {

                    let l = res.data.data;

                    incomes.value = l.incomes.filter((el) => {
                        return el.income;
                    });

                    incomes.value.forEach(el=>{
                        totalIncome.value += parseFloat(el.amount);
                    });

                    expenses.value = l.incomes.filter((el) => {
                        return el.expense;
                    });

                    expenses.value.forEach(el=>{
                        totalExpense.value += parseFloat(el.amount);
                    });

                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });

        }

        let getTreasuries = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/activeTreasury`)
                .then((res) => {
                    let l = res.data.data;
                    treasuries.value= l.treasuries;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getIncome();
            getTreasuries();
        });

        let printExpense = () => {
            var printContents = document.getElementById('printExpense').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        return {printExpense,totalExpense,totalIncome,treasury_id,fromDate,toDate,expenses,incomes,treasuries, loading, getIncome};

    },
    data() {
        return {
            locale: localStorage.getItem("langAdmin")
        }
    }
}
</script>

<style scoped>
.card {
    position: relative;
}

.btn-custom {
    width: 30%;
}

.custom {
    border: 1px solid #D7D7D7;
    padding: 2px;
    border-radius: 5px;
    width: 35%;
}

.btn {
    color: #fff;
}
.hover:hover{
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}

@media print {
    .card-header{
        display: none;
    }
}

.amount{
    background-color: #fcb00c;
    color: #000;
    padding: 10px;
}
.amount h5{
    font-weight: 700;
    text-align: center;
}
.submit-margin{
    margin-top: 38px !important;
}
.date-input{
    width: 135px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}
.select-input{
    width: 235px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}

.head-table{
    background: #000;
}
.head-table h3{
    color: #ffc107;
    text-align: center;
}

</style>
