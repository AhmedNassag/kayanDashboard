<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.ExpenseTreasuries') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.Reports') }} </li>
                            <li class="breadcrumb-item active">{{ $t('global.ExpenseTreasuries') }}</li>
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
                        <div class="card-body">
                            <div class="card-header pt-0">
                                <div class="row justify-content-between">
                                    <div class="col-12 row justify-content-end">
                                        <form @submit.prevent="getByDate" class="needs-validation">
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

                                                    <select v-model="treasury_id" class="form-select select-input" required>
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
                                        <div class="col-6">
                                            {{ $t('global.Search') }}:
                                            <input type="search" v-model="search" class="custom"/>
                                        </div>
                                        <div class="col-6 d-flex justify-content-end">
                                        <button @click="printExpense" class="btn btn-success print-button">
                                            {{$t('global.Print')}}
                                            <i class="fa fa-print"></i>
                                        </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" id="printExpense">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.Band Name') }}</th>
                                        <th>{{ $t('global.Amount') }}</th>
                                        <th>{{ $t('global.RecipientsName') }}</th>
                                        <th>{{ $t('global.For') }}</th>

                                        <th>{{ $t('global.PaymentDate') }}</th>
                                        <th>{{ $t('global.TransactionDate') }}</th>
                                        <th>{{ $t('global.ProcessWriter') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="incomes.length">
                                        <tr v-for="(item,index) in incomes" :key="item.id">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ item.expense.name }}</td>
                                            <td>{{ item.amount }}</td>
                                            <td>{{ item.payer }}</td>
                                            <td>{{ item.notes }}</td>
                                            <td>{{ item.payment_date }}</td>
                                            <td>{{ dateFormat(item.created_at) }}</td>
                                            <td>{{ item.user ? item.user.name : "-----" }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="8">{{ $t('global.NoDataFound') }}</th>
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
            <Pagination :limit="2" :data="incomesPaginate" @pagination-change-page="getIncome">
                <template #prev-nav>
                    <span>&lt; {{$t('global.Previous')}}</span>
                </template>
                <template #next-nav>
                    <span>{{$t('global.Next')}} &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, inject, watch, ref} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup() {
        const emitter = inject('emitter');
        const {t} = useI18n({});

        // get packages
        let incomes = ref([]);
        let treasuries = ref([]);
        let incomesPaginate = ref({});
        let fromDate = ref('');
        let toDate = ref('');
        let treasury_id = ref('');
        let loading = ref(false);
        const search = ref('');

        let getIncome = (page = 1) => {

           loading.value = true;

           adminApi.get(`/v1/dashboard/expenseTreasuryPlatformReport?page=${page}&search=${search.value}&treasury_id=${treasury_id.value}&from_date=${fromDate.value}&to_date=${toDate.value}`)
               .then((res) => {
                   let l = res.data.data;
                   incomesPaginate.value = l.incomes;
                   incomes.value = l.incomes.data;
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

        let getByDate = (page = 1) => {

            loading.value = true;

            adminApi.get(`/v1/dashboard/expenseTreasuryPlatformReport?page=${page}&treasury_id=${treasury_id.value}&from_date=${fromDate.value}&to_date=${toDate.value}`)
                .then((res) => {
                    let l = res.data.data;
                    incomesPaginate.value = l.incomes;
                    incomes.value = l.incomes.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getTreasuries();
        });

        emitter.on('get_lang', () => {
            getIncome(search.value);
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getIncome();
            }
        });

        let printExpense = () => {
            var printContents = document.getElementById('printExpense').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }


        let dateFormat = (item) => {
            return new Date(item).toDateString();
        }

        return {printExpense,treasury_id,fromDate,toDate,getByDate,incomes,treasuries, loading, getIncome,dateFormat, search, incomesPaginate};

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

</style>
