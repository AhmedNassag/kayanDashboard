<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('sidebar.Schedule')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">{{$t('dashboard.Dashboard')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('sidebar.Schedule')}}</li>
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
                                        <router-link :to="{ name: 'scheduleGet' }" class="btn btn-custom btn-dark">
                                            {{ $t("global.back") }}
                                        </router-link>
                                    </div>
                                </div>
                            </div>

                            <!--Packages-->
                            <div class="row" v-if="Packages">
                                <div class="col-lg-4" v-for="(Package,index) in Packages" :key="Package.id">
                                    <div style="border: 2px solid grey; border-radius: 5px; padding:20px" class="package-detail">
                                        <h4 class="text-center">{{ $t('global.Plan') }} {{Package.name}}</h4>
                                        <h3 class="text-center package-price">${{parseFloat(Package.price,2)}}</h3>
                                        <div class="package-feature">
                                            <ul>
                                                <li>{{Package.visiter_num}} {{ $t('global.NumberofVisitors') }}</li>
                                                <li>{{Package.period}}  {{ $t('global.DaysVisibility') }}</li>
                                                <li>{{Package.period}} {{ $t('global.PackageExpiry') }}</li>
                                                <br>
                                                <h6>{{ $t('global.Adappearancepages') }}</h6>
                                                <span v-for="pageView in Package.page_view" :key="pageView.id">
                                                    <li>{{pageView.page.name + ' -- '+ pageView.view.type }}</li>
                                                </span>
                                                <br>
                                                <h6>{{ $t('global.Adappearancepagesmobile') }}</h6>
                                                <span v-for="pageViewMob in Package.page_view_mobile" :key="pageViewMob.id">
                                                    <li>{{pageViewMob.page_mobile.name + ' -- '+ pageViewMob.view.type }}</li>
                                                </span>
                                            </ul>
                                        </div>
                                        <div>
                                            <a  data-bs-toggle="modal" @click="model[index].view= true" :href="'#file' + Package.id" class="btn btn-primary text-center price-btn btn-block">
                                                {{ $t('global.payNow') }}
                                            </a>
                                            <a data-bs-toggle="modal" @click="getCalender(Package.id)" href="#calender" class="btn btn-primary text-center price-btn btn-block">
                                                {{ $t('global.AvailableDays') }}
                                            </a>
                                        </div>
                                    </div>

                                    <!-- The Modal File -->
                                    <div
                                        :id="'file' + Package.id"
                                        v-if="model[index].view"
                                        class="position-fixed overlay"
                                        @click.self="model[index].view= false"
                                    >
                                        <div class="modal-dialog-centered">
                                            <div  class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">{{ $t('global.Plan') }} {{Package.name}}</h4>
                                                    <span class="modal-close" @click="model[index].view= false">
                                                        <a href="#" :id="'modal-close-'+ Package.id" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="far fa-times-circle orange-text"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                                <div class="modal-body">
                                                    <form :id="'pushasePackage_' + Package.id">

                                                        <div class="modal-info">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="package-detail">
                                                                        <h4 class="text-center">{{ $t('global.Plan') }} {{Package.name}}</h4>
                                                                        <h3 class="text-center package-price">${{parseFloat(Package.price,2)}}</h3>
                                                                        <div class="text-center package-feature">
                                                                            <ul>
                                                                                <li>{{Package.visiter_num}} {{ $t('global.NumberofVisitors') }}</li>
                                                                                <li>{{Package.period}} {{ $t('global.DaysVisibility') }}</li>
                                                                                <li>{{Package.period}} {{ $t('global.PackageExpiry') }}</li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 row  align-items-center">
                                                                    <div class="row justify-content-center">
                                                                        <div class="col-3 checkCorrect">
                                                                            <i class="fas fa-check-circle custom-icon"></i><br>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-info">
                                                            <div class="row justify-content-between my-3">
                                                                <div class="col-lg-6" >
                                                                    <label for="exampleFormControlInput1">{{ $t('global.linkCompany') }}</label>
                                                                    <input
                                                                        type="text"
                                                                        class="form-control"
                                                                        id="exampleFormControlInput1"
                                                                        v-model="v$.pack[index].link.$model"
                                                                    >
                                                                    <div v-if="v$.pack[index].link.$error">
                                                                        <span class="text-danger" v-if="v$.pack[index].link.required.$invalid">{{$t('global.fieldRequired')}}</span>
                                                                        <span class="text-danger" v-if="v$.pack[index].link.url.$invalid">{{$t('global.mustBeURL')}}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label for="exampleFormControlInput2">{{ $t('global.Ad start date') }}</label>
                                                                    <input
                                                                        type="date"
                                                                        class="form-control"
                                                                        id="exampleFormControlInput2"
                                                                        v-model="v$.pack[index].date.$model"
                                                                    >
                                                                    <div v-if="v$.pack[index].date.$error ||  errors.date">
                                                                        <span class="text-danger" v-if="v$.pack[index].date.required.$invalid">{{$t('global.fieldRequired')}}</span>
                                                                        <span class="text-danger" v-if="errors.date">{{$t('global.dateStart')}}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row justify-content-between">
                                                                <div :class="['col-lg-12',step == 1?'active' : '']" >
                                                                    <h3 class="know-you">{{ $t('global.Websiteimagesizes') }}</h3>
                                                                    <div class="form-row justify-content-center">
                                                                        <div  class="col-lg-4 text-center" v-for="(pageView,ind) in Package.page_view" :key="pageView.id">
                                                                            <label class="text-center d-block" style="color: hsl(30, 100%, 50%);">
                                                                                {{ $t('global.height') }}:{{ pageView.size.height }}px | {{ $t('global.width') }}:{{ pageView.size.width }}px
                                                                            </label>
                                                                            <div class="btn btn-outline-primary waves-effect">
                                                                            <span>
                                                                                {{ $t('global.ChooseImage') }}
                                                                                <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                                            </span>
                                                                                <input
                                                                                    required
                                                                                    type="file"
                                                                                    id="mediaPackage1"
                                                                                    accept="image/*"
                                                                                    @change="preview($event,index,ind)"
                                                                                >
                                                                            </div>
                                                                            <div v-if="v$.pack[index].file[ind].file_name.$error">
                                                                                <span class="text-danger" v-if="v$.pack[index].file[ind].file_name.required.$invalid">{{$t('global.fieldRequired')}}</span>
                                                                                <span class="text-danger" v-if="errors.file">{{$t('global.fileMobile')}}</span>
                                                                            </div>
                                                                            <p class="num-of-files">{{model[index].image[ind].numberOfImage ? model[index].image[ind].numberOfImage + ' Files Selected' : 'No Files Chosen' }}</p>
                                                                            <div class="container-images" :id="`container-images-${ind}`" v-show="model[index].image[ind].numberOfImage"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row justify-content-between">
                                                                <div :class="['col-lg-12',step == 2?'active' : '']">
                                                                    <h3 class="know-you"> {{ $t('global.Mobilephoneimagesizes') }}</h3>
                                                                    <div class="form-row justify-content-center">
                                                                        <div v-for="(pageView,indMob) in Package.page_view_mobile" :key="pageView.id" class="col-lg-4 text-center">
                                                                            <label class="text-center d-block">
                                                                                {{ $t('global.height') }}:{{ pageView.size.height }}px | {{ $t('global.width') }}:{{ pageView.size.width }}px
                                                                            </label>
                                                                            <div class="btn btn-outline-primary waves-effect">
                                                                                <span>
                                                                                    {{ $t('global.Choosefiles') }}
                                                                                    <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                                                </span>
                                                                                <input
                                                                                    required
                                                                                    type="file"
                                                                                    id="mediaPackage4"
                                                                                    accept="image/*"
                                                                                    @change="preview2($event,index,indMob)"
                                                                                >
                                                                            </div>
                                                                            <div v-if="v$.pack[index].fileMobile[indMob].file_name.$error">
                                                                                <span class="text-danger" v-if="v$.pack[index].fileMobile[indMob].file_name.required.$invalid">{{$t('global.fieldRequired')}}</span>
                                                                                <span class="text-danger" v-if="errors.file">{{$t('global.fileMobile')}}</span>
                                                                            </div>
                                                                            <p class="num-of-files">{{model[index].mobileImage[indMob].numberOfImage ? model[index].mobileImage[indMob].numberOfImage + ' Files Selected' : 'No Files Chosen' }}</p>
                                                                            <div class="container-images" :id="`container-image-${indMob}`" v-show="model[index].mobileImage[indMob].numberOfImage"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row justify-content-between mt-4">
                                                                <button
                                                                    type="submit"
                                                                    class="btn btn-primary col-2 text-center"
                                                                    @click.prevent="pushasePackage(index)"
                                                                >
                                                                    {{ $t('global.Submit') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- The Modal File -->

                                </div>

                                <!-- The Modal Calender -->
                                <div class="modal fade" id="calender">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">{{ $t('global.AvailableDays') }}</h4>
                                                <span class="modal-close"><a href="#" id="modal-close-" data-bs-dismiss="modal" aria-label="Close"><i class="far fa-times-circle orange-text"></i></a></span>
                                            </div>
                                            <div class="modal-body">
                                                <FullCalendar
                                                    :options="options"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- The Modal Calender -->


                            </div>
                            <!--Packages-->



                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
        </div>
    </div>
</template>

<script>
// import {computed, toRefs, inject, onMounted, reactive, ref} from "vue";
// import Sidebar from "../../../components/web/sidebar";
// import webApi from "../../../api/webAxios";
import {useStore} from "vuex";
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import TimeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import {url, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
// import {useI18n} from "vue-i18n";

import {computed, toRefs, inject, onMounted, reactive, ref, watch} from "vue";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "packages",
    data(){
        return {
            errors:{}
        }
    },
    components:{
        // Sidebar,
        FullCalendar
    },
    setup(){
        const store = useStore();
        const emitter = inject('emitter');
        const {t} = useI18n({});

        // get create Package
        let Packages = ref([]);
        let loading = ref(false);
        let packageValidation = ref([]);

        let getPackage = () => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/scheduleAdvertise/create`)
            .then((res) => {
                let l =res.data.data;
                Packages.value = l.packages;
                l.packages.forEach((el,index) => {

                    data.model.push({view: false,closeId:el.id,image:[],mobileImage:[]});
                    data.pack.push({
                        date: '',
                        link: '',
                        day: el.period,
                        package_id: el.id,
                        file: [],
                        fileMobile: [],
                    });
                    let  file = [];
                    let  fileMobile = [];

                    el.page_view.forEach((e) => {
                        data.pack[index].file.push({
                            file_name: {},
                            width: e.size.width,
                            height:  e.size.height,
                            size_id: e.size.id,
                            page_id: e.id
                        });
                        file.push({file_name:{required}});
                        data.model[index].image.push({numberOfImage:0});
                    });

                    el.page_view_mobile.forEach((e) => {
                        data.pack[index].fileMobile.push({
                            file_name: {},
                            width: e.size.width,
                            height:  e.size.height,
                            size_id: e.size.id,
                            page_id: e.id
                        });
                        fileMobile.push({file_name:{required}});
                        data.model[index].mobileImage.push({numberOfImage:0});
                    });

                    packageValidation.value.push({
                        date:{required},
                        link:{required,url},
                        package_id: {required},
                        file: [
                            ...file
                        ],
                        fileMobile: [
                            ...fileMobile
                        ]
                    });
                });
            })
            .catch((err) => {
                Swal.fire({
                    icon: 'error',
                    title: `${t('advisor.ThereIsAnErrorInTheSystem')}`,
                    text: `${t('advisor.PleaseTryAgainLater')}`,
                });
            })
            .finally(() => {
                loading.value = false;
            });
        }

        onMounted(() => {
            getPackage();
        });

        emitter.on('get_lang_web', () => {
            getPackage();
        });

        let preview = (e,index,ind) => {
            let containerImages = document.querySelector('#container-images-' + ind);
            containerImages.innerHTML = '';
            data.model[index].image[ind].numberOfImage = e.target.files.length;

            for (let i of e.target.files) {

                data.pack[index].file[ind].file_name = e.target.files[0];
                let reader = new FileReader();
                let figure = document.createElement('figure');
                let figcap = document.createElement('figcaption');

                figcap.innerText = i.name;
                figure.appendChild(figcap);

                reader.onload = () => {
                    let img = document.createElement('img');
                    img.setAttribute('src',reader.result);
                    figure.insertBefore(img,figcap);
                }

                containerImages.appendChild(figure);
                reader.readAsDataURL(i);
            }

        }

        let preview2 = (e,index,indMob) => {

            let containerImages = document.querySelector('#container-image-' + indMob);
            containerImages.innerHTML = '';
            data.model[index].mobileImage[indMob].numberOfImage = e.target.files.length;

            for (let i of e.target.files) {
                data.pack[index].fileMobile[indMob].file_name = e.target.files[0];
                let reader = new FileReader();
                let figure = document.createElement('figure');
                let figcap = document.createElement('figcaption');

                figcap.innerText = i.name;
                figure.appendChild(figcap);

                reader.onload = () => {
                    let img = document.createElement('img');
                    img.setAttribute('src',reader.result);
                    figure.insertBefore(img,figcap);
                }

                containerImages.appendChild(figure);
                reader.readAsDataURL(i);
            }
        }

        const allEvents = ref([]);

        const options = reactive({
            plugins: [ dayGridPlugin, interactionPlugin,TimeGridPlugin,listPlugin ],
            initialView: 'dayGridMonth',
            headerToolbar: {
                center: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            editable:true,
            weekends:true,
            events:  allEvents || [],
            height: "auto"
        });

        let getCalender = (packageId)=> {
            allEvents.value = [];
            adminApi.get(`/v1/dashboard/scheduleAdvertise/getAll/${packageId}`)
            .then((res) => {
                let l = res.data.data;
                allEvents.value = l.schedule
            })
            .catch((err) => {
                Swal.fire({
                    icon: 'error',
                    title: `${t('advisor.ThereIsAnErrorInTheSystem')}`,
                    text: `${t('advisor.PleaseTryAgainLater')}`,
                });
            })
        };

        let data =  reactive({
            pack:[],
            model:[]
        });

        const rules = computed(() => {
            return {
                pack: [
                    ...packageValidation.value
                ],
            }
        });

        const v$ = useVuelidate(rules,data);

        return {
            Packages,
            getCalender,
            preview,
            loading,
            options,
            preview2,
            ...toRefs(data),
            v$
        };
    },
    methods: {
        pushasePackage(index) {
            this.v$.pack[index].$validate();

            if (!this.v$.pack[index].$error) {

                his.loading = true;
                let formData = new FormData();
                formData.append('date',this.pack[index].date);
                formData.append('link',this.pack[index].link);
                formData.append('package_id',this.pack[index].package_id);
                formData.append('day',this.pack[index].day);

                for ( var i = 0; i < this.pack[index].file.length; i++ ) {
                    let file = this.pack[index].file[i].file_name;
                    let size_id = this.pack[index].file[i].size_id;
                    let page_id = this.pack[index].file[i].page_id;
                    formData.append("file[" + i + "][file_name]", file);
                    formData.append("file[" + i + "][size_id]", size_id);
                    formData.append("file[" + i + "][page_id]", page_id);
                }

                for ( var i = 0; i < this.pack[index].fileMobile.length; i++ ) {
                    let file = this.pack[index].fileMobile[i].file_name;
                    let size_id = this.pack[index].fileMobile[i].size_id;
                    let page_id = this.pack[index].file[i].page_id;
                    formData.append("fileMobile[" + i + "][file_name]", file);
                    formData.append("fileMobile[" + i + "][size_id]", size_id);
                    formData.append("fileMobile[" + i + "][page_id]", page_id);
                }

                adminApi.post(`/v1/dashboard/scheduleAdvertise/buy_package`,formData)
                .then((res) => {
                    this.emity(index);
                    console.log(res.data);
                    if(res.data.url) {
                        window.location.href = res.data.url;
                    }
                })
                .catch((err) => {
                    console.log(err.response);
                    this.errors = err.response.data.errors;
                    // Swal.fire({
                    //     icon: 'error',
                    //     title: 'يوجد خطا في الصور...',
                    //     text: 'اقصي ارتفاع للصوره يكون 500px و اقصي عرض 500px و ان حجمها لا يتعدي 2mb !',
                    // });
                })
                .finally(() => {
                    this.loading = false;
                });
            }

        },
        emity(index){
            let modal = document.getElementById('modal-close-' + this.model[index].closeId);
            modal.click();
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
/**/
.form-control {
    border-color: #fcbd33;
}
.modal-content{
    width: 800px !important;
    overflow-y: scroll;
    height: 700px;
    padding: 30px;
}
.overlay{
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 3000;
    background-color: rgba(0,0,0,.5);
    justify-content: center;
    align-items: center;
    display: flex;
}
.container-images{
    width: 90%;
    position: relative;
    margin: auto;
    display: flex;
    justify-content: space-evenly;
    gap: 20px;
    flex-wrap: wrap;
    padding: 10px;
    border-radius: 20px;
    background-color: #f7f7f7;
    left: 20px;
}
.num-of-files{
    text-align: inherit;
    margin: 20px 48px 30px;

}
input[type="file"] {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 0;
    margin: 0;
    cursor: pointer;
    filter: alpha(opacity=0);
    opacity: 0;
}
.waves-effect {
    position: relative;
    overflow: hidden;
    cursor: pointer;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    width: 200px;
    height: 50px;
    text-align: center;
    line-height: 34px;
}
.submit-section .submit-btn {
    margin-top: 30px;
}
.price-btn:hover,.price-btn:active, .price-btn:focus {
    background-color: #fcb00c;
    border: 1px solid #fcb00c;
}
.checkCorrect {
    margin-bottom: 30px;
}
.price-btn {
    margin: 5px 0;
}
.modal.show{
    display: block;
    padding-right: 0px;
}
.know-you {
    margin-bottom: 25px;
}
label {
    margin-bottom: 15px;
}
.modal-lg, .modal-xl {
    max-width: 850px;
}
.content {
    position: relative;
}
.custom-icon{
    font-size: 110px;
    color: green;
}
</style>
