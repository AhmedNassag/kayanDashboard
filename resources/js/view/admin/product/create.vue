<template>
    <div :class="['page-wrapper', 'page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left" />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">المنتجات</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{ name: 'indexProduct' }">المنتجات</router-link>
                            </li>
                            <li class="breadcrumb-item active">اضافه منتج</li>
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
                            <div class="card-header pt-0 mb-4">
                                <router-link :to="{ name: 'indexProduct' }" class="btn btn-custom btn-dark">
                                    رجوع
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="storeProduct" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Start Name Ar-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم المنتج بالعربية</label>
                                                <input type="text" class="form-control" v-model.trim="v$.nameAr.$model"
                                                    id="validationCustom01" placeholder="اسم المنتج بالعربية"
                                                    :class="{ 'is-invalid': v$.nameAr.$error, 'is-valid': !v$.nameAr.$invalid }">
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.nameAr.required.$invalid"> هذا الحقل مطلوب<br />
                                                    </span>
                                                    <span v-if="v$.nameAr.maxLength.$invalid"> يجب ان يكون علي الاقل {{
                                                            v$.nameAr.minLength.$params.min
                                                    }} حرف <br /></span>
                                                    <span v-if="v$.nameAr.minLength.$invalid">يجب ان يكون علي اكثر {{
                                                            v$.nameAr.maxLength.$params.max
                                                    }} حرف</span>
                                                </div>
                                            </div>
                                            <!--End Name Ar-->

                                            <!--Start Name En-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم المنتج بالإنجليزية</label>
                                                <input type="text" class="form-control" v-model.trim="v$.nameEn.$model"
                                                    id="validationCustom01" placeholder="اسم المنتج بالإنجليزية"
                                                    :class="{ 'is-invalid': v$.nameEn.$error, 'is-valid': !v$.nameEn.$invalid }">
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.nameEn.required.$invalid"> هذا الحقل مطلوب<br />
                                                    </span>
                                                    <span v-if="v$.nameEn.maxLength.$invalid"> يجب ان يكون علي الاقل {{
                                                            v$.nameEn.minLength.$params.min
                                                    }} حرف <br /></span>
                                                    <span v-if="v$.nameEn.minLength.$invalid">يجب ان يكون علي اكثر {{
                                                            v$.nameEn.maxLength.$params.max
                                                    }} حرف</span>
                                                </div>
                                            </div>
                                            <!--End Name En-->

                                            <!--Start BarCode-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">الباركود </label>
                                                <input type="number" class="form-control"
                                                    v-model.trim="v$.barcode.$model" id="validationCustom056"
                                                    placeholder="الباركود"
                                                    :class="{ 'is-invalid': v$.barcode.$error, 'is-valid': !v$.barcode.$invalid }">

                                                <button
                                                type="button"
                                                class="btn btn-secondary btn-sm"
                                                @click="myFunction()"
                                                >
                                                {{ $t("global.Generate Random") }}
                                                </button>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.barcode.integer.$invalid"> يجب ان يكون رقم
                                                        <br /></span>
                                                </div>
                                            </div>
                                            <!--End BarCode-->

                                            <!--Start Category-->
                                            <div class="col-md-6 mb-3">
                                                <label>الفئه الرئيسية</label>
                                                <select @change="getSubCategory(v$.category_id.$model)" name="type"
                                                    class="form-select" v-model="v$.category_id.$model"
                                                    :class="{ 'is-invalid': v$.category_id.$error, 'is-valid': !v$.category_id.$invalid }">
                                                    <option value="">---</option>
                                                    <option v-for="category in categories" :key="category.id"
                                                        :value="category.id">
                                                        {{ category.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.category_id.required.$invalid"> هذا الحقل مطلوب<br />
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Category-->

                                            <!--Start Sub Category-->
                                            <div class="col-md-6 mb-3">
                                                <label>الفئه الفرعية</label>
                                                <select name="type" class="form-select"
                                                    v-model="v$.sub_category_id.$model"
                                                    :class="{ 'is-invalid': v$.sub_category_id.$error, 'is-valid': !v$.sub_category_id.$invalid }">
                                                    <option value="">---</option>
                                                    <option v-for="category in subCategories" :key="category.id"
                                                        :value="category.id">
                                                        {{ category.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_category_id.required.$invalid"> هذا الحقل
                                                        مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--End Sub Category-->

                                            <!--End Effective Material-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">المادة الفعالة  </label>
                                                <input type="text" class="form-control" v-model.trim="v$.effectiveMaterial.$model"
                                                    id="validationCustom01" placeholder="اسم المنتج"
                                                    :class="{ 'is-invalid': v$.effectiveMaterial.$error, 'is-valid': !v$.effectiveMaterial.$invalid }">
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.effectiveMaterial.required.$invalid"> هذا الحقل مطلوب<br />
                                                    </span>
                                                    <span v-if="v$.effectiveMaterial.maxLength.$invalid"> يجب ان يكون علي الاقل {{
                                                            v$.effectiveMaterial.minLength.$params.min
                                                    }} حرف <br /></span>
                                                    <span v-if="v$.effectiveMaterial.minLength.$invalid">يجب ان يكون علي اكثر {{
                                                            v$.effectiveMaterial.maxLength.$params.max
                                                    }} حرف</span>
                                                </div>
                                            </div>
                                            <!--End Effective Material-->

                                            <!--Start Pharmacist Form-->
                                            <div class="col-md-6 mb-3">
                                                <label>الشكل الصيدلى</label>
                                                <select name="type" class="form-select"
                                                    v-model="v$.pharmacistForm_id.$model"
                                                    :class="{ 'is-invalid': v$.pharmacistForm_id.$error, 'is-valid': !v$.pharmacistForm_id.$invalid }">
                                                    <option value="">---</option>
                                                    <option v-for="pharmacistForm in pharmacistForms"
                                                        :key="pharmacistForm.id" :value="pharmacistForm.id">
                                                        {{ pharmacistForm.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.pharmacistForm_id.required.$invalid"> هذا الحقل
                                                        مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--End Pharmacist Form-->

                                            <!--Start Main Measurement Unit-->
                                            <div class="col-md-6 mb-3">
                                                <label>وحدة القياس الرئيسية</label>
                                                <select name="type" class="form-select"
                                                    v-model="v$.main_measurement_unit_id.$model"
                                                    :class="{ 'is-invalid': v$.main_measurement_unit_id.$error, 'is-valid': !v$.main_measurement_unit_id.$invalid }">
                                                    <option value="">---</option>
                                                    <option v-for="measure in measures" :key="measure.id"
                                                        :value="measure.id">
                                                        {{ measure.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.main_measurement_unit_id.required.$invalid"> هذا
                                                        الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--End Main Measurement Unit-->

                                            <!--Start Count Unit-->
                                            <div class="col-md-6 mb-3">
                                                <label>عدد الوحدات داخل الفئة الفرعية </label>
                                                <input type="number" class="form-control" v-model="v$.count_unit.$model"
                                                    @input="subPrice" placeholder="عدد الوحدات داخل الفئة الفرعية"
                                                    :class="{ 'is-invalid': v$.count_unit.$error, 'is-valid': !v$.count_unit.$invalid }">
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.count_unit.required.$invalid"> هذا الحقل مطلوب<br />
                                                    </span>
                                                    <span v-if="v$.count_unit.integer.$invalid"> يجب ان يكون رقم
                                                        <br /></span>
                                                </div>
                                            </div>
                                            <!--End Count Unit-->

                                            <!--Start Sub Measurement Unit-->
                                            <div class="col-md-6 mb-3">
                                                <label>وحدة القياس الفرعية</label>
                                                <select name="type" class="form-select"
                                                    v-model="v$.sub_measurement_unit_id.$model"
                                                    :class="{ 'is-invalid': v$.sub_measurement_unit_id.$error, 'is-valid': !v$.sub_measurement_unit_id.$invalid }">
                                                    <option value="">---</option>
                                                    <option v-for="measure in measures" :key="measure.id"
                                                        :value="measure.id">
                                                        {{ measure.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_measurement_unit_id.required.$invalid"> هذا الحقل
                                                        مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--End Sub Measurement Unit-->

                                            <!--Start Selling Method-->
                                            <div class="col-md-6 mb-3">
                                                <label>البيع</label>
                                                <select name="type" class="form-select" multiple
                                                    v-model="v$.selling_method.$model"
                                                    :class="{ 'is-invalid': v$.selling_method.$error, 'is-valid': !v$.selling_method.$invalid }">
                                                    <option v-for="sellingMethod in sellingMethods"
                                                        :key="sellingMethod.id" :value="sellingMethod.id">
                                                        {{ sellingMethod.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.selling_method.required.$invalid"> هذا الحقل
                                                        مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--End Selling Method-->

                                            <!--Start Re Order Limit-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom055">حد اعادة الطلب</label>
                                                <input type="number" class="form-control"
                                                    v-model.trim="v$.Re_order_limit.$model" id="validationCustom055"
                                                    placeholder="حد اعادة الطلب"
                                                    :class="{ 'is-invalid': v$.Re_order_limit.$error, 'is-valid': !v$.Re_order_limit.$invalid }">
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.Re_order_limit.required.$invalid"> هذا الحقل
                                                        مطلوب<br /> </span>
                                                    <span v-if="v$.Re_order_limit.integer.$invalid"> يجب ان يكون رقم
                                                        <br /></span>
                                                </div>
                                            </div>
                                            <!--End Re Order Limit-->

                                            <!--Start Maximum Product-->
                                            <div class="col-md-6 mb-3">
                                                <label>اقصي كمية فى المخزن</label>
                                                <input type="number" class="form-control"
                                                    v-model.trim="v$.maximum_product.$model"
                                                    placeholder="اقصي كمية فى المخزن"
                                                    :class="{ 'is-invalid': v$.maximum_product.$error, 'is-valid': !v$.maximum_product.$invalid }">
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.maximum_product.required.$invalid"> هذا الحقل
                                                        مطلوب<br /> </span>
                                                    <span v-if="v$.maximum_product.integer.$invalid"> يجب ان يكون رقم
                                                        <br /></span>
                                                </div>
                                            </div>
                                            <!--End Maximum Product-->

                                            <!--Start Sell App-->
                                            <div class="col-md-6 mb-3">
                                                <label>اماكن ظهور المنتج</label>
                                                <select name="type" class="form-select" v-model="v$.sell_app.$model"
                                                    :class="{ 'is-invalid': v$.sell_app.$error, 'is-valid': !v$.sell_app.$invalid }">
                                                    <option value="1">
                                                        {{ $t('global.OfferInDirectSellingAndApplication') }}</option>
                                                    <option value="0">{{ $t('global.OfferedForDirectSalesOnly') }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sell_app.required.$invalid"> هذا الحقل مطلوب<br />
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Sell App-->

                                            <!--Start Description-->
                                            <div class="col-md-12 mb-3">
                                                <label for="validationCustom034">الوصف</label>
                                                <textarea type="text" class="form-control custom-textarea"
                                                    v-model.trim="v$.description.$model" id="validationCustom034"
                                                    placeholder="الوصف"
                                                    :class="{ 'is-invalid': v$.description.$error, 'is-valid': !v$.description.$invalid }"></textarea>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.description.required.$invalid"> هذا الحقل مطلوب<br />
                                                    </span>
                                                </div>
                                            </div>
                                            <!--End Description-->

                                            <!--Start Image-->
                                            <div class="col-md-3 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        Choose files
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input name="mediaPackage" type="file" @change="preview"
                                                        id="mediaPackage" accept="image/png,jepg,jpg">
                                                </div>
                                                <span class="text-danger text-center">اقصي حجم لا يتعدي 2mb</span>
                                                <p class="num-of-files">{{ numberOfImage ? numberOfImage + ' Files Selected' : 'No Files Chosen' }}</p>
                                                <div class="container-images" id="container-images"
                                                    v-show="data.image && numberOfImage"></div>
                                                <div class="container-images" v-show="!numberOfImage">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/admin/img/company/img-1.png`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <!--End Image-->

                                            <!--Start Multiple Image-->
                                            <div class="col-md-9 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        Choose files
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input name="mediaPackage[]" type="file" multiple @change="preview2"
                                                        id="mediaPackage1" accept="image/png,jepg,jpg">
                                                </div>
                                                <span class="text-danger text-center">اقصي حجم لا يتعدي 2mb</span>
                                                <p class="num-of-files">{{ numberOfImage1 ? numberOfImage1 + ' Files Selected' : 'No Files Chosen' }}</p>
                                                <div class="container-images" id="container-images1"
                                                    v-show="data.files && numberOfImage1"></div>
                                                <div class="container-images" v-show="!numberOfImage1">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/admin/img/company/img-1.png`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <!--End Multiple Image-->



                                            <!--Start TheBalanceOfTheFirstDuration-->
                                            <div class="col-md-12 mb-3 mt-5">
                                                <div class="sec-body row">
                                                    <div class="col-md-12 mb-12 sec-head">
                                                        <h3>
                                                            {{ $t('global.TheBalanceOfTheFirstDuration') }}
                                                        </h3>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>
                                                            {{ $t('global.Quantity') }}
                                                            ( {{ $t('global.TotalAccount') }} )
                                                        </label>
                                                        <input type="number" class="form-control"
                                                            v-model.number="v$.quantity.$model"
                                                            :placeholder="$t('global.Quantity') + '(' + data.mainUnitMeasurement + ')'"
                                                            :class="{ 'is-invalid': v$.quantity.$error, 'is-valid': !v$.quantity.$invalid }">
                                                        <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                        <div class="invalid-feedback">
                                                            <span
                                                                v-if="v$.quantity.required.$invalid">{{ $t('global.ThisFieldIsRequired') }}<br />
                                                            </span>
                                                            <span
                                                                v-if="v$.quantity.numeric.$invalid">{{ $t('global.ThisFieldIsNumeric') }}
                                                                <br /></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>
                                                            {{ $t('global.price') }}
                                                            ( {{ $t('global.TotalAccount') }} )
                                                        </label>
                                                        <input type="number" step="0.1" class="form-control"
                                                            @input="subPrice" v-model.number="v$.price.$model"
                                                            :placeholder="$t('global.price') + ' (' + data.mainUnitMeasurement + ')'"
                                                            :class="{ 'is-invalid': v$.price.$error, 'is-valid': !v$.price.$invalid }">
                                                        <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                        <div class="invalid-feedback">
                                                            <span
                                                                v-if="v$.price.required.$invalid">{{ $t('global.ThisFieldIsRequired') }}<br />
                                                            </span>
                                                            <span
                                                                v-if="v$.price.numeric.$invalid">{{ $t('global.ThisFieldIsNumeric') }}
                                                                <br /></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>
                                                            {{ $t('global.Quantity') }}
                                                            ( {{ $t('global.Partial') }} )
                                                        </label>
                                                        <input type="number" class="form-control"
                                                            v-model.number="v$.sub_quantity.$model"
                                                            :placeholder="$t('global.RequiredQuantity') + '(' + data.subUnitMeasurement + ')'"
                                                            :class="{ 'is-invalid': v$.sub_quantity.$error, 'is-valid': !v$.sub_quantity.$invalid }">
                                                        <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                        <div class="invalid-feedback">
                                                            <span
                                                                v-if="v$.sub_quantity.required.$invalid">{{ $t('global.ThisFieldIsRequired') }}<br />
                                                            </span>
                                                            <span
                                                                v-if="v$.sub_quantity.numeric.$invalid">{{ $t('global.ThisFieldIsNumeric') }}
                                                                <br /></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>
                                                            {{ $t('global.price') }}
                                                            ( {{ $t('global.Partial') }} )
                                                        </label>
                                                        <input type="number" step="0.1" class="form-control" disabled
                                                            v-model.number="v$.sub_price.$model"
                                                            :placeholder="$t('global.price') + ' (' + data.subUnitMeasurement + ')'"
                                                            :class="{ 'is-invalid': v$.sub_price.$error, 'is-valid': !v$.sub_price.$invalid }">
                                                        <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                        <div class="invalid-feedback">
                                                            <span
                                                                v-if="v$.sub_price.required.$invalid">{{ $t('global.ThisFieldIsRequired') }}<br />
                                                            </span>
                                                            <span
                                                                v-if="v$.sub_price.numeric.$invalid">{{ $t('global.ThisFieldIsNumeric') }}
                                                                <br /></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>{{ $t('global.ChooseStore') }}</label>

                                                        <select v-model="data.store_id"
                                                            :class="['form-select', { 'is-invalid': v$.store_id.$error, 'is-valid': !v$.store_id.$invalid }]">
                                                            <option v-for="store in stores" :key="store.id"
                                                                :value="store.id">{{ store.name }}</option>
                                                        </select>
                                                        <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                        <div class="invalid-feedback">
                                                            <span
                                                                v-if="v$.store_id.required.$invalid">{{ $t('global.StoreIsRequired') }}<br />
                                                            </span>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <!--End TheBalanceOfTheFirstDuration-->

                                        </div>

                                        <button class="btn btn-primary" type="submit">اضافه</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
        </div>
    </div>
</template>

<script>
import { computed, onMounted, reactive, toRefs, ref, watch } from "vue";
import useVuelidate from '@vuelidate/core';
import { required, minLength, maxLength, numeric, integer } from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "createDepartment",
    data() {
        return {
            errors: {}
        }
    },
    setup() {
        let loading = ref(false);
        let pharmacistForms = ref([]);
        let categories = ref([]);
        let subCategories = ref([]);
        let measures = ref([]);
        let sellingMethods = ref([]);
        let stores = ref([]);

        //start design
        let addProduct = reactive({
            data: {
                nameAr: '',
                nameEn: '',
                effectiveMaterial: '',
                barcode: '',
                count_unit: null,
                maximum_product: null,
                Re_order_limit: null,
                description: '',
                image: {},
                files: [],
                category_id: null,
                sub_category_id: null,
                pharmacistForm_id: null,
                main_measurement_unit_id: null,
                sub_measurement_unit_id: null,
                selling_method: [],
                sell_app: 1,
                quantity: 0,
                sub_quantity: 0,
                price: 0,
                sub_price: 0,
                mainUnitMeasurement: '',
                subUnitMeasurement: '',
                store_id: 1,
            }
        });

        let getProduct = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/product/create`)
                .then((res) => {
                    let l = res.data.data;
                    pharmacistForms.value = l.pharmacistForms;
                    categories.value = l.categories;
                    measures.value = l.measures;
                    sellingMethods.value = l.sellingMethods;
                    stores.value = l.stores;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        };

        let getSubCategory = (id) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/category/${id}`)
                .then((res) => {
                    let l = res.data.data;
                    subCategories.value = l.subCategories;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        };

        const rules = computed(() => {
            return {
                nameAr: {
                    minLength: minLength(3),
                    maxLength: maxLength(70),
                    required
                },
                nameEn: {
                    minLength: minLength(3),
                    maxLength: maxLength(70),
                    required
                },
                effectiveMaterial: {
                    minLength: minLength(3),
                    maxLength: maxLength(70),
                    required
                },
                barcode: {
                    integer
                },
                count_unit: {
                    required,
                    integer
                },
                Re_order_limit: {
                    required,
                    integer
                },
                maximum_product: {
                    required,
                    integer
                },
                description: { required },
                image: {
                    required
                },
                files: {
                    required
                },
                category_id: {
                    required,
                    integer
                },
                sub_category_id: {
                    required,
                    integer
                },
                pharmacistForm_id: {
                    required,
                    integer
                },
                main_measurement_unit_id: {
                    required,
                    integer
                },
                sub_measurement_unit_id: {
                    required,
                    integer
                },
                selling_method: {
                    required
                },
                sell_app: {
                    required
                },
                price: {
                    required,
                    numeric
                },
                sub_price: {
                    required,
                    numeric
                },
                quantity: {
                    required,
                    numeric
                },
                sub_quantity: {
                    required,
                    numeric
                },
                store_id: {
                    required
                },
            }
        });

        const v$ = useVuelidate(rules, addProduct.data);

        let preview = (e) => {

            let containerImages = document.querySelector('#container-images');
            if (numberOfImage.value) {
                containerImages.innerHTML = '';
            }
            addProduct.data.image = {};

            numberOfImage.value = e.target.files.length;

            addProduct.data.image = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement('figure');
            let figcap = document.createElement('figcaption');

            figcap.innerText = addProduct.data.image.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement('img');
                img.setAttribute('src', reader.result);
                figure.insertBefore(img, figcap);
            }

            containerImages.appendChild(figure);
            reader.readAsDataURL(addProduct.data.image);

        };

        let preview2 = (e) => {

            let containerImages = document.querySelector('#container-images1');
            if (numberOfImage.value) {
                containerImages.innerHTML = '';
            }
            addProduct.data.files = [];

            numberOfImage1.value = e.target.files.length;

            for (let file of e.target.files) {

                addProduct.data.files.push(file);
                let reader = new FileReader();
                let figure = document.createElement('figure');
                let figcap = document.createElement('figcaption');

                figcap.innerText = file.name;
                figure.appendChild(figcap);

                reader.onload = () => {
                    let img = document.createElement('img');
                    img.setAttribute('src', reader.result);
                    figure.insertBefore(img, figcap);
                }

                containerImages.appendChild(figure);
                reader.readAsDataURL(file);
            }

        };

        const numberOfImage = ref(0);
        const numberOfImage1 = ref(0);

        let subPrice = () => {
            addProduct.data.sub_price = parseFloat(addProduct.data.price / addProduct.data.count_unit).toFixed(2);
        }

        onMounted(() => {
            getProduct();
        });

        watch(() => addProduct.data.main_measurement_unit_id, (after, before) => {
            let v = measures.value.filter((el) => el.id == addProduct.data.main_measurement_unit_id);
            if (v.length > 0) {
                addProduct.data.mainUnitMeasurement = v[0].name;
            }
        });

        watch(() => addProduct.data.sub_measurement_unit_id, (after, before) => {
            let v = measures.value.filter((el) => el.id == addProduct.data.sub_measurement_unit_id);
            if (v.length > 0) {
                addProduct.data.subUnitMeasurement = v[0].name;
            }
        });

        return {
            loading,
            ...toRefs(addProduct),
            v$,
            preview,
            preview2,
            subPrice,
            stores,
            numberOfImage,
            numberOfImage1,
            pharmacistForms,
            categories,
            measures,
            subCategories,
            getSubCategory,
            sellingMethods
        };
    },
    methods: {
        myFunction() {
            this.data.barcode = Math.round(Math.random() * 10000000000);
        },

        storeProduct() {
            this.v$.$validate();

            if (!this.v$.$error) {

                this.loading = true;
                this.errors = {};
                let formData = new FormData();
                formData.append('quantity', this.data.quantity);
                formData.append('store_id', this.data.store_id);
                formData.append('sub_quantity', this.data.sub_quantity);
                formData.append('price', this.data.price);
                formData.append('sub_price', this.data.sub_price);
                formData.append('nameAr', this.data.nameAr);
                formData.append('nameEn', this.data.nameEn);
                formData.append('effectiveMaterial', this.data.effectiveMaterial);
                formData.append('barcode', this.data.barcode);
                formData.append('count_unit', this.data.count_unit);
                formData.append('maximum_product', this.data.maximum_product);
                formData.append('Re_order_limit', this.data.Re_order_limit);
                formData.append('description', this.data.description);
                formData.append('category_id', this.data.category_id);
                formData.append('sub_category_id', this.data.sub_category_id);
                formData.append('pharmacistForm_id', this.data.pharmacistForm_id);
                formData.append('sub_measurement_unit_id', this.data.sub_measurement_unit_id);
                formData.append('main_measurement_unit_id', this.data.main_measurement_unit_id);
                formData.append('sell_app', this.data.sell_app);
                formData.append('image', this.data.image);
                formData.append('selling_method', this.data.selling_method);
                formData.append('quantity', this.data.quantity);
                for (var i = 0; i < this.numberOfImage1; i++) {
                    let file = this.data.files[i];
                    formData.append('files[' + i + ']', file);
                }

                adminApi.post(`/v1/dashboard/product`, formData)
                    .then((res) => {

                        notify({
                            title: `تم الاضافه بنجاح <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                        this.resetForm();
                        this.$nextTick(() => { this.v$.$reset() });
                    })
                    .catch((err) => {
                        this.errors = err.response.data.errors;
                        console.log(err.response);
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        resetForm() {
            document.querySelector('#container-images').innerHTML = '';
            document.querySelector('#container-images1').innerHTML = '';
            document.querySelector('#mediaPackage1').value = '';
            document.querySelector('#mediaPackage').value = '';
            this.numberOfImage = 0;
            this.numberOfImage1 = 0;
            this.data.nameAr = '';
            this.data.nameEn = '';
            this.data.barcode = '';
            this.data.count_unit = null;
            this.data.Re_order_limit = null;
            this.data.maximum_product = null;
            this.data.description = '';
            this.data.image = {};
            this.data.files = [];
            this.data.category_id = null;
            this.data.sub_category_id = null;
            this.data.pharmacistForm_id = null;
            this.data.main_measurement_unit_id = null;
            this.data.sub_measurement_unit_id = null;
            this.data.selling_method = [];
            this.data.sell_app = 1;
            this.data.quantity = 0;
            this.data.sub_quantity = 0;
            this.data.price = 0;
            this.data.sub_price = 0;
            this.data.store_id = 1;
            this.data.mainUnitMeasurement = '';
            this.data.subUnitMeasurement = '';
        }
    }
}
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

.custom-textarea {
    height: 150px;
}

.sec-body {
    border: 3px solid #0E67D0;
    border-radius: 20px;
    padding: 10px;
}

.sec-head {
    background-color: #0E67D0;
    color: #000;
    border-radius: 11px;
    padding: 5px;
    text-align: center;
    margin-bottom: 8px;
    margin-top: 10px;
}

.sec-body:hover .sec-head {
    border: 3px solid #00DD2F;
    padding: 2px;
    border-radius: 11px;
    background-color: #00DD2F;
}

.sec-head h3 {
    font-weight: 700;
}
</style>
