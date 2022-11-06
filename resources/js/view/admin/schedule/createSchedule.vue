<template>
    <div :class="[
      'page-wrapper',
      this.$i18n.locale == 'ar' ? 'page-wrapper-ar' : '',
    ]">
        <!-- Page Content -->
        <div class="content">
            <loader v-if="loading" />
            <div class="container-fluid">
                <div class="row">

                    <!-- sidebar -->
                    <!-- <Sidebar /> -->
                    <!-- /sidebar -->
                    <div class="col-xl-9 col-md-8 mx-auto">
                        <div class="freelance-title" id="plan">
                            <h3>{{ $t('global.Advertisingpackages') }}</h3>
                            <p>{{ $t('global.Choosethebestpackagethatsuitsyou') }}</p>
                        </div>
                        <div class="row" v-if="Packages">

                                <div class="col-lg-4" v-for="(Package,index) in Packages" :key="Package.id">
                                    <div class="package-detail">
                                        <h4>{{ $t('global.Plan') }} {{Package.name}}</h4>
                                        <h3 class="package-price">${{parseFloat(Package.price,2)}}</h3>
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
                                            <a  data-bs-toggle="modal" @click="getCalender(Package.id)" href="#calender" class="btn btn-primary text-center price-btn btn-block">
                                                {{ $t('global.AvailableDays') }}
                                            </a>
                                        </div>
                                    </div>

                                    <!-- The Modal -->
                                    <!-- <div
                                        :id="'file' + Package.id"
                                        v-if="model[index].view"
                                        class="position-fixed overlay"
                                        @click.self="model[index].view= false"
                                    >
                                        <div
                                            class="modal-dialog-centered"
                                        >
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
                                                    <form
                                                        :id="'pushasePackage_' + Package.id"
                                                    >
                                                        <div class="modal-info">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="package-detail">
                                                                        <h4>{{Package.name}} {{ $t('global.Plan') }}</h4>
                                                                        <h3 class="package-price">${{parseFloat(Package.price,2)}}</h3>
                                                                        <div class="package-feature">
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
                                                                    type="datetime-local"
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

                                                                        <div class="col-lg-4 text-center" v-for="(pageView,ind) in Package.page_view" :key="pageView.id">

                                                                            <label class="text-center d-block">
                                                                                ( {{ $t('global.height') }}: {{ pageView.size.height }}px | {{ $t('global.width') }}: {{ pageView.size.width }}px )
                                                                            </label>
                                                                            <div class="btn btn-outline-primary waves-effect">
                                                                            <span>
                                                                                {{ $t('global.Choosefiles') }}
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
                                                                                ( {{ $t('global.height') }}: {{ pageView.size.height }}px | {{ $t('global.width') }}: {{ pageView.size.width }}px )
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
                                    </div> -->
                                    <!-- The Modal-->

                                </div>
                        </div>

                        <!-- The Modal -->
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
                        <!-- The Modal-->

                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
</template>

<script>
import { computed, onMounted, reactive, toRefs, inject, ref } from "vue";
import useVuelidate from "@vuelidate/core";
import { required, minLength, between, maxLength, alpha,integer } from "@vuelidate/validators";
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import { useI18n } from "vue-i18n";

export default {
    name: "createSchedule",
    data() {
        return {
            errors: {},
        };
    },
    setup() {
        const emitter = inject("emitter");
        const { t } = useI18n({});
        let loading = ref(false);
        let message = ref('');
        let users = ref([]);
        let advertisingPackages = ref([]);
        //
        let Packages = ref([]);
        let data =  reactive({
            pack:[],
            model:[]
        });
        let getCalender = (packageId)=> {

            allEvents.value = [];
            adminApi.get(`/v1/dashboard/scheduleAdvertise/getAll/${packageId}`)
            .then((res) => {
                let l = res.data.data;
                allEvents.value =  l.schedule
            })
            .catch((err) => {
                Swal.fire({
                    icon: 'error',
                    title: `${t('advisor.ThereIsAnErrorInTheSystem')}`,
                    text: `${t('advisor.PleaseTryAgainLater')}`,
                });
            })
        };
        //



        let getData = () => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/scheduleAdvertise/create`)
            .then((res) => {
                let l = res.data.data;
                users.value = l.users;
                advertisingPackages.value = l.advertisingPackages;
                //
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
                //
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
        }

        onMounted(() => {
            getData();
        });

        //start design
        let addSchedule = reactive({
            data: {
                title: "",
                start: "",
                end: "",
                link: "",
                price: "",
                visitor: "",
                user_id: "",
                advertising_package_id: "",
                // image: {},
                homeFirstSlider: {},
                homeSecondSlider: {},
                homeThirdSlider: {},
                homeFourthSlider: {},
                homeRightSquare: {},
                homeMiddleSquare: {},
                homeLeftSquare: {},
                homeTopBanner: {},
                homeMiddleBanner: {},
                homeBottomBanner: {},
                homePopUp: {},
                productsTopRightRectangle: {},
                productsBottomRightRectangle: {},
                productsBottom: {},
                productDetailsTopRightRectangle: {},
                productDetailsBottomRightRectangle: {},
                productDetailsBottom: {},
                productAlternativesTopRightRectangle: {},
                productAlternativesBottomRightRectangle: {},
                productAlternativesBottom: {},
                shoppingBottom: {},
                profileTop: {},
                profileBottom: {},
                mobFirstSlider: {},
                mobSecondSlider: {},
                mobThirdSlider: {},
                mobFourthSlider: {},
                mobRightSquare: {},
                mobMiddleSquare: {},
                mobLeftSquare: {},
                mobTopBanner: {},
                mobBottomBanner: {},
                mobPopUp: {},
                nameExist: false,
            },
        });

        const rules = computed(() => {
            return {
                title: {
                    required,
                    minLength: minLength(3),
                    maxLength: maxLength(70),
                },
                start: {
                    required,
                },
                end: {
                    required,
                },
                link: {
                    // required,
                },
                price: {
                    // required,
                },
                visitor: {
                    // required,
                },
                user_id: {
                    required,
                },
                advertising_package_id: {
                    required,
                },

                homeFirstSlider: {},
                homeSecondSlider: {},
                homeThirdSlider: {},
                homeFourthSlider: {},
                homeRightSquare: {},
                homeMiddleSquare: {},
                homeLeftSquare: {},
                homeTopBanner: {},
                homeMiddleBanner: {},
                homeBottomBanner: {},
                homePopUp: {},
                productsTopRightRectangle: {},
                productsBottomRightRectangle: {},
                productsBottom: {},
                productDetailsTopRightRectangle: {},
                productDetailsBottomRightRectangle: {},
                productDetailsBottom: {},
                productAlternativesTopRightRectangle: {},
                productAlternativesBottomRightRectangle: {},
                productAlternativesBottom: {},
                shoppingBottom: {},
                profileTop: {},
                profileBottom: {},
                mobFirstSlider: {},
                mobSecondSlider: {},
                mobThirdSlider: {},
                mobFourthSlider: {},
                mobRightSquare: {},
                mobMiddleSquare: {},
                mobLeftSquare: {},
                mobTopBanner: {},
                mobBottomBanner: {},
                mobPopUp: {},

            };
        });

        const v$ = useVuelidate(rules, addSchedule.data);

        let preview = (e) => {
            let containerImages = document.querySelector("#container-images");
            if (numberOfImage.value) {
                containerImages.innerHTML = "";
            }
            addAdOwner.data.file = {};

            numberOfImage.value = e.target.files.length;

            addAdOwner.data.file = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement("figure");
            let figcap = document.createElement("figcaption");

            figcap.innerText = addAdOwner.data.file.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement("img");
                img.setAttribute("src", reader.result);
                figure.insertBefore(img, figcap);
            };

            containerImages.appendChild(figure);
            reader.readAsDataURL(addAdOwner.data.file);
        };

        const numberOfImage = ref(0);

        return {
            loading,
            ...toRefs(addSchedule),
            v$,
            preview,
            numberOfImage,
            message,
            users,
            advertisingPackages,
            //
            Packages,
            ...toRefs(data),
            getCalender
            //
        };

    },
    methods: {
        storeSchedule() {
            this.v$.$validate();

            if (!this.v$.$error) {
                this.loading = true;
                this.errors = {};
                this.message = '';

                let formData = new FormData();
                formData.append("title", this.data.title);
                formData.append("start", this.data.start);
                formData.append("end", this.data.end);
                formData.append("link", this.data.link);
                formData.append("price", this.data.price);
                formData.append("visitor", this.data.visitor);
                formData.append("user_id", this.data.user_id);
                formData.append("advertising_package_id", this.data.advertising_package_id);
                // formData.append("image", this.data.image);

                adminApi
                .post(`/v1/dashboard/scheduleAdvertise`, formData)
                .then((res) => {
                    notify({
                        title: `تم الإضافة بنجاح <i class="fas fa-check-circle"></i>`,
                        type: "success",
                        duration: 5000,
                        speed: 2000,
                    });

                    this.resetForm();
                    this.$nextTick(() => {
                        this.v$.$reset();
                    });
                })
                .catch((err) => {
                    this.nameExist = err.response.data.errors;
                    this.message = err.response.data.message;
                })
                .finally(() => {
                    this.loading = false;
                });
            }
        },
        resetForm() {
            this.data.title = "";
            this.data.start = "";
            this.data.end = "";
            this.data.link = "";
            this.data.price = "";
            this.data.visitor = "";
            this.data.user_id = "";
            this.data.advertising_package_id = "";
            // this.data.image = {};
        },
    },
};
</script>

<style scoped>
.coustom-select {
    height: 100px;
}

.card {
    position: relative;
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
    margin: auto;
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

.num-of-files {
    text-align: center;
    margin: 20px 0 30px;
}

.container-images {
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
}
</style>
