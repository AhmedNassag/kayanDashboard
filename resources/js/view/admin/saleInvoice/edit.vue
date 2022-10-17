<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Sale Invoice')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexSaleInvoice'}">{{$t('global.Sale Invoice')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.Edit')}}</li>
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
                                <router-link
                                    :to="{name: 'indexSaleInvoice'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['type']">{{ errors['type'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['client_id']">{{ errors['client_id'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['stock_id']">{{ errors['stock_id'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['payment_method']">{{ errors['payment_method'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['purchase_type']">{{ errors['purchase_type'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['price']">{{ errors['price'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['visa_percentage']">{{ errors['visa_percentage'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['added_tax']">{{ errors['added_tax'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['discount_percentage']">{{ errors['discount_percentage'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['discount_percentage']">{{ errors['discount_percentage'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['discount_value']">{{ errors['discount_value'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['transfer_price']">{{ errors['transfer_price'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['note']">{{ errors['note'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.product_id']">{{ errors['product.0.product_id'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.quantity']">{{ errors['product.0.quantity'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.price_before_discount']">{{ errors['product.0.price_before_discount'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.price_after_discount']">{{ errors['product.0.price_after_discount'][0] }}<br/></div>

                                    <form @submit.prevent="storeJob" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Start Type Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom0">
                                                    {{ $t("global.Type") }}
                                                </label>
                                                <select class="form-control" v-model.trim="v$.type.$model" disabled>
                                                    <option value="Sale Invoice">
                                                        {{ $t("global.Sales Invoice") }}
                                                    </option>
                                                    <option value="Return Invoice">
                                                        {{ $t("global.Return Invoice") }}
                                                    </option>
                                                    <option value="Prices Offer">
                                                        {{ $t("global.Prices Offer") }}
                                                    </option>
                                                    <option value="Prices List">
                                                        {{ $t("global.Prices List") }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!--End Type Select-->

                                            <!--Start Client Select-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.Client') }}</label>
                                                <Select2 v-model="data.client_id" :options="clients" :settings="{ width: '100%' }" />
                                                <!-- <select
                                                    v-model="data.client_id"
                                                    :class="['form-select',{'is-invalid':v$.client_id.$error,'is-valid':!v$.client_id.$invalid}]"
                                                >
                                                    <option v-for="client in clients" :key="client.id" :value="client.id">{{client.user.name}}</option>
                                                </select> -->
                                                <router-link :to="{ name: 'ClientIndex' }">
                                                    <i class="fa fa-user" aria-hidden="true" style="color: #0E67D0"></i>
                                                    <span style="color: #0E67D0"> {{ $t("sidebar.New Client") }} </span>
                                                </router-link>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.client_id.required.$invalid">{{$t('global.NameIsRequired')}}<br /> </span>
                                                </div>
                                            </div>
                                            <!--End Client Select-->

                                            <!--Start Stock Select-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.ChooseStore') }}</label>
                                                <Select2 v-model="data.stock_id" :options="stores" :settings="{ width: '100%' }" />
                                                <!-- <select v-model="data.stock_id"
                                                    :class="['form-select',{'is-invalid':v$.stock_id.$error,'is-valid':!v$.stock_id.$invalid}]">
                                                    <option v-for="store in stores" :key="store.id" :value="store.id">{{store.name}}</option>
                                                </select> -->
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.stock_id.required.$invalid">{{$t('global.StoreIsRequired')}}<br /> </span>
                                                </div>

                                            </div>
                                            <!--End Stock Select-->

                                            <!--Start Purchase Type Select-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom0">
                                                    {{ $t("global.Purchase Type") }}
                                                </label>
                                                <select class="form-select" v-model.trim="v$.purchase_type.$model">
                                                    <option value="Tax">
                                                        {{ $t("global.tax") }}
                                                    </option>
                                                    <option value="Not Tax">
                                                        {{ $t("global.not tax") }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!--End Purchase Type Select-->

                                            <!--Start Price-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom10">{{ $t('global.TotalPrice') }}</label>
                                                <input type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.price.$model"
                                                       id="validationCustom10"
                                                       :class="{'is-invalid':v$.price.$error,'is-valid':!v$.price.$invalid}"
                                                       :placeholder="$t('global.TotalPrice')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.price.required.$invalid">{{ $t('global.TotalPriceIsRequired') }} <br/></span>
                                                    <span v-if="v$.price.numeric.$invalid">{{$t('global.TotalPriceIsNumeric')}} <br /></span>
                                                </div>
                                            </div>
                                            <!--End Price-->

                                            <!--Start Visa Percentage-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom08">{{ $t('global.Visa Percentage') }}</label>
                                                <input :disabled="!data.price" type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.visa_percentage.$model"
                                                       id="validationCustom08"
                                                       :class="{'is-invalid':v$.visa_percentage.$error,'is-valid':!v$.visa_percentage.$invalid}"
                                                       :placeholder="$t('global.Visa Percentage')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.visa_percentage.required.$invalid">{{ $t('global.NameIsRequired') }} <br/></span>
                                                    <span v-if="v$.visa_percentage.numeric.$invalid">{{$t('global.ThisFieldMustBeANumber')}} <br /></span>
                                                </div>
                                            </div>
                                            <!--End Visa Percentage-->

                                            <!--Start Visa Value-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom07">{{ $t('global.Visa Value') }}</label>
                                                <input :disabled="!data.price" type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.visa_value.$model"
                                                       id="validationCustom07"
                                                       :class="{'is-invalid':v$.visa_value.$error,'is-valid':!v$.visa_value.$invalid}"
                                                       :placeholder="$t('global.Visa Value')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.visa_value.required.$invalid">{{ $t('global.discountValueIsRequired') }} <br/></span>
                                                    <span v-if="v$.visa_value.numeric.$invalid">{{$t('global.discountValueIsNumeric')}} <br /></span>
                                                </div>
                                            </div>
                                            <!--End Visa Value-->

                                            <!--Start Added Tax-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom08">{{ $t('global.Added Tax') }}</label>
                                                <input :disabled="!data.price" type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.added_tax_percentage.$model"
                                                       id="validationCustom08"
                                                       :class="{'is-invalid':v$.added_tax_percentage.$error,'is-valid':!v$.added_tax_percentage.$invalid}"
                                                       :placeholder="$t('global.Added Tax')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.added_tax_percentage.required.$invalid">{{ $t('global.NameIsRequired') }} <br/></span>
                                                    <span v-if="v$.added_tax_percentage.numeric.$invalid">{{$t('global.ThisFieldMustBeANumber')}} <br /></span>
                                                </div>
                                            </div>
                                            <!--End Added Tax-->

                                            <!--Start Added Tax Value-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom07">{{ $t('global.Added Tax Value') }}</label>
                                                <input :disabled="!data.price" type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.added_tax_value.$model"
                                                       id="validationCustom07"
                                                       :class="{'is-invalid':v$.added_tax_value.$error,'is-valid':!v$.added_tax_value.$invalid}"
                                                       :placeholder="$t('global.Added Tax Value')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.added_tax_value.required.$invalid">{{ $t('global.discountValueIsRequired') }} <br/></span>
                                                    <span v-if="v$.added_tax_value.numeric.$invalid">{{$t('global.discountValueIsNumeric')}} <br /></span>
                                                </div>
                                            </div>
                                            <!--End Added Tax Value-->

                                            <!--Start Discount Percentage-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom06">{{ $t('global.discountPercentage') }}</label>
                                                <input :disabled="!data.price" type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.discount_percentage.$model"
                                                       id="validationCustom06"
                                                       :class="{'is-invalid':v$.discount_percentage.$error,'is-valid':!v$.discount_percentage.$invalid}"
                                                       :placeholder="$t('global.discountPercentage')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.discount_percentage.required.$invalid">{{ $t('global.discountPercentageIsRequired') }} <br/></span>
                                                    <span v-if="v$.discount_percentage.numeric.$invalid">{{$t('global.discountPercentageIsNumeric')}} <br /></span>
                                                </div>
                                            </div>
                                            <!--End Discount Percentage-->

                                            <!--Start Discount Value-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom07">{{ $t('global.discountValue') }}</label>
                                                <input :disabled="!data.price" type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.discount_value.$model"
                                                       id="validationCustom07"
                                                       :class="{'is-invalid':v$.discount_value.$error,'is-valid':!v$.discount_value.$invalid}"
                                                       :placeholder="$t('global.discountValue')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.discount_value.required.$invalid">{{ $t('global.discountValueIsRequired') }} <br/></span>
                                                    <span v-if="v$.discount_value.numeric.$invalid">{{$t('global.discountValueIsNumeric')}} <br /></span>
                                                </div>
                                            </div>
                                            <!--End Discount Value-->

                                            <!--Start Other Discount-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom08">{{ $t('global.otherDiscounts') }}</label>
                                                <input :disabled="!data.price" type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.other_discounts.$model"
                                                       id="validationCustom08"
                                                       :class="{'is-invalid':v$.other_discounts.$error,'is-valid':!v$.other_discounts.$invalid}"
                                                       :placeholder="$t('global.otherDiscounts')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.other_discounts.required.$invalid">{{ $t('global.otherDiscountsIsRequired') }} <br/></span>
                                                    <span v-if="v$.other_discounts.numeric.$invalid">{{$t('global.otherDiscountsIsNumeric')}} <br /></span>
                                                </div>
                                            </div>
                                            <!--End Other Discount-->

                                            <!--Start Transfer Price-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom09">{{ $t('global.transferPrice') }}</label>
                                                <input :disabled="!data.price" type="number" step=".01"
                                                       class="form-control"
                                                       v-model.trim="v$.transfer_price.$model"
                                                       id="validationCustom09"
                                                       :class="{'is-invalid':v$.transfer_price.$error,'is-valid':!v$.transfer_price.$invalid}"
                                                       :placeholder="$t('global.transferPrice')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.transfer_price.required.$invalid">{{ $t('global.transferPriceIsRequired') }} <br/></span>
                                                    <span v-if="v$.transfer_price.numeric.$invalid">{{$t('global.transferPriceIsNumeric')}} <br /></span>
                                                </div>
                                            </div>
                                            <!--End Transfer Price-->

                                            <!--Start Notes-->
                                            <div class="col-md-6 mb-3">
                                                <label>{{$t('global.Notes')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="v$.note.$model" :class="['form-control text-height',{'is-invalid':v$.note.$error,'is-valid':!v$.note.$invalid}]" :placeholder="$t('global.Notes')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.note.required.$invalid">{{$t('global.DescriptionIsRequired')}}<br /> </span>
                                                    <span v-if="v$.note.minLength.$invalid">{{$t('global.DescriptionIsMustHaveAtLeast')}} {{ v$.note.minLength.$params.max }} {{$t('global.Letters')}} <br /></span>
                                                </div>
                                            </div>
                                            <!--End Notes-->

                                            <!--Start Payment Method Select-->
                                            <div class="col-md-6 mb-3 pd-3">
                                                <label for="validationCustom0">
                                                    {{ $t("global.Payment Method") }}
                                                </label>
                                                <select class="form-select batch" v-model.trim="v$.payment_method.$model">
                                                    <option value="Cach">
                                                        {{ $t("global.Cach") }}
                                                    </option>
                                                    <option value="Delay">
                                                        {{ $t("global.Delay") }}
                                                    </option>
                                                </select>
                                            </div>
                                            <!--End Payment Method Select-->

                                            <!--Start Batches-->
                                            <div class="col-md-12 mb-3 batch-option">
                                                <div class="row account">
                                                    <div class="col-md-12 mb-12 head-account">
                                                        <h3>{{ $t('global.Batches') }}</h3>
                                                    </div>
                                                    <div v-for="(it,index) in data.batch" :key="it.id" class="col-md-12 mb-12 body-account row">

                                                        <!--Start Money :class="{'is-invalid':v$.batch[index].money.$error || !batches[index].send,'is-valid':!v$.batch[index].money.$invalid && batches[index].send}"-->
                                                        <div class="col-md-4 mb-4">
                                                            <label>{{$t('global.Money')}}</label>
                                                            <input type="number" step="0.1" class="form-control"
                                                                   @input="validateLTE(index)"
                                                                   v-model.number="v$.batch[index].money.$model"
                                                                   :placeholder="$t('global.Money')"

                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <!-- <div class="invalid-feedback">
                                                                <span v-if="v$.batch[index].money.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br/></span>
                                                                <span v-if="v$.batch[index].money.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br/></span>
                                                            </div> -->
                                                        </div>
                                                        <!--End Money-->

                                                        <!--Start Due Date :class="{'is-invalid':v$.batch[index].due_date.$error || !batches[index].sendDueDate,'is-valid':!v$.batch[index].due_date.$invalid && batches[index].sendDueDate}"-->
                                                        <div class="col-md-4 mb-4">
                                                            <label>{{$t('global.Due Date')}}</label>
                                                            <input type="date" class="form-control"
                                                                @change="validateDueDate(index)"
                                                                v-model="v$.batch[index].due_date.$model"
                                                                :placeholder="$t('global.Due Date')"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <!-- <div class="invalid-feedback">
                                                                <span v-if="v$.batch[index].due_date.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br/></span>
                                                                <span v-if="!batches[index].sendDueDate">{{$t('global.DueDateMastGreaterThanToday')}}<br/></span>
                                                            </div> -->
                                                        </div>
                                                        <!--End Due Date-->

                                                        <div class="col-md-3 mb-3">
                                                            <button @click.prevent="addBatch" v-if="(data.batch.length-1) == index"
                                                                class="btn btn-sm btn-success me-2 mt-5">
                                                                <i class="fas fa-clipboard-list"></i> {{$t('global.AddANewLine')}}
                                                            </button>
                                                            <button v-if="index" @click.prevent="deleteBatch(index)" data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2 mt-5">
                                                                <i class="far fa-trash-alt"></i> {{$t('global.Delete')}}
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!--End Batches-->

                                            <!--Start Sale Products-->
                                            <div class="col-md-12 mb-12">
                                                <div class="row account">
                                                    <div class="col-md-12 mb-12 head-account">
                                                        <h3>{{ $t('global.Products') }}</h3>
                                                    </div>
                                                    <div v-for="(it,index) in data.product" :key="it.id" class="col-md-12 mb-12 body-account row">

                                                        <!--Start Category-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.mainCategory') }}</label>
                                                            <!-- <Select2 @change="getSubCategory(it.category_id,index)" v-model="it.category_id" :options="categories" :settings="{ width: '100%' }" /> -->
                                                            <select @change="getSubCategory(it.category_id,index)" v-model="it.category_id" :class="['form-select',{'is-invalid':v$.product[index].category_id.$error,'is-valid':!v$.product[index].category_id.$invalid}]">
                                                                <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].category_id.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                            </div>
                                                        </div>
                                                        <!--End Category-->

                                                        <!--Start Sub Categoy-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.subCategory') }}</label>
                                                            <!-- <Select2 @change="getProduct(it.category_id,it.sub_category_id,index)" v-model="it.sub_category_id" :options="subCategory[index].subCategory" :settings="{ width: '100%' }" /> -->
                                                            <select @change="getProduct(it.category_id,it.sub_category_id,index)" v-model="it.sub_category_id" :class="['form-select',{'is-invalid':v$.product[index].sub_category_id.$error,'is-valid':!v$.product[index].sub_category_id.$invalid}]">
                                                                <option v-for="category in subCategory[index].subCategory" :key="category.id" :value="category.id">{{category.name}}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].sub_category_id.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /></span>
                                                            </div>
                                                        </div>
                                                        <!--End Sub Category-->

                                                        <!--Start Product-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.Products') }}</label>
                                                            <!-- <Select2 @change="getMeasurementUnit(it.product_id,index)" v-model="it.product_id" :options="subCategory[index].subCategory" :settings="{ width: '100%' }" /> -->
                                                            <select @change="getMeasurementUnit(it.product_id,index)" v-model="it.product_id" :class="['form-select',{'is-invalid':v$.product[index].product_id.$error,'is-valid':!v$.product[index].product_id.$invalid}]">
                                                                <option v-for="category in products[index].products" :key="category.id" :value="category.id">{{category.name}}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].product_id.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                            </div>
                                                        </div>
                                                        <!--End Product-->

                                                        <!--Start Quantity-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.RequiredQuantity')}} ( {{data.product[index].mainUnitMeasurement}} )</label>
                                                            <input type="number" class="form-control"
                                                                   v-model.number="v$.product[index].quantity.$model"
                                                                   @input="DebitAmount"
                                                                   :placeholder="$t('global.RequiredQuantity')"
                                                                   :class="{'is-invalid':v$.product[index].quantity.$error,'is-valid':!v$.product[index].quantity.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                            </div>
                                                        </div>
                                                        <!--End Quantity-->

                                                        <!--Start Unit-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.mainUnitMeasurement')}}</label>
                                                            <input type="text" disabled class="form-control"
                                                                v-model="data.product[index].mainUnitMeasurement"
                                                                @input="DebitAmount"
                                                            >
                                                        </div>
                                                        <!--End Unit-->

                                                        <!--Start Price Before Discount-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.priceBeforeDiscount')}}</label>
                                                            <input type="number" step="0.1" class="form-control"
                                                                @change="validateLTE(index)"
                                                                v-model.number="v$.product[index].price_before_discount.$model"
                                                                :placeholder="$t('global.priceBeforeDiscount')"
                                                                :class="{'is-invalid':v$.product[index].price_before_discount.$error,'is-valid':!v$.product[index].price_before_discount.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].price_before_discount.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].price_before_discount.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                            </div>
                                                        </div>
                                                        <!--End Price Before Discount-->

                                                        <!--Start Price After Discount-->
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.priceAfterDiscount')}}</label>
                                                            <input type="number" step="0.1" class="form-control"
                                                                @input="validateLTE(index)"
                                                                v-model.number="v$.product[index].price_after_discount.$model"
                                                                :placeholder="$t('global.priceAfterDiscount')"
                                                                :class="{'is-invalid':v$.product[index].price_after_discount.$error || !products[index].send,'is-valid':!v$.product[index].price_after_discount.$invalid && products[index].send}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].price_after_discount.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].price_after_discount.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                                <span v-if="!products[index].send">{{$t('global.priceAfterDiscountMastBeLessThanOrEqualPriceBeforeDiscount')}} <br /></span>
                                                            </div>
                                                        </div>
                                                        <!--End Price After Discount-->

                                                        <div class="col-md-3 mb-3">
                                                            <button @click.prevent="addDebit" v-if="(data.product.length-1) == index"
                                                                class="btn btn-sm btn-success me-2 mt-5">
                                                                <i class="fas fa-clipboard-list"></i> {{$t('global.AddANewLine')}}
                                                            </button>
                                                            <button v-if="index" @click.prevent="deleteDebit(index)"
                                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2 mt-5">
                                                                <i class="far fa-trash-alt"></i> {{$t('global.Delete')}}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!--End Sale Product-->

                                            <!--Start Statistics-->
                                            <div class="col-md-12 mt-5">
                                                <div class="table-responsive">
                                                    <table class="table table-center table-hover mb-0 datatable">
                                                        <thead class="account">
                                                            <tr class="text-center">
                                                                <th>{{ $t('global.TotalPriceBeforeDiscount') }}</th>
                                                                <th>{{ $t('global.TotalPriceAfterDiscount') }}</th>
                                                                <th>{{ $t('global.Total Discount') }}</th>
                                                                <th>{{ $t('global.TotalProductPrice') }}</th>
                                                                <th>{{ $t('global.TotalQuantityPrice') }}</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="text-center">
                                                                <td>{{data.price ? parseFloat(data.price) + parseFloat(data.transfer_price) : 0}}</td>
                                                                <td>{{data.price ? (parseFloat(data.price) + parseFloat(data.transfer_price)) - (parseFloat(data.visa_value) + parseFloat(data.added_tax_value) + parseFloat(data.discount_value) + parseFloat(data.other_discounts)) : 0}}</td>
                                                                <td>{{data.price ? (parseFloat(data.visa_value) + parseFloat(data.added_tax_value) + parseFloat(data.discount_value) + parseFloat(data.other_discounts)) : 0}}</td>
                                                                <td>{{totalProductPrice ? totalProductPrice : 0}}</td>
                                                                <td>{{totalProductQuantity ? totalProductQuantity : 0}}</td>
                                                            </tr>
                                                            <tr class="text-center">
                                                                <td>{{ $t('global.Notes') }}</td>
                                                                <td colspan="9" v-if="totalProductPrice < (parseFloat(data.price) + parseFloat(data.transfer_price)) - (parseFloat(data.visa_value) + parseFloat(data.added_tax_value) + parseFloat(data.discount_value) + parseFloat(data.other_discounts))"> {{$t('global.The total price of the products is less than the total price of the purchase invoice')}}</td>
                                                                <td colspan="9" v-else-if="totalProductPrice == (parseFloat(data.price) + parseFloat(data.transfer_price)) - (parseFloat(data.visa_value) + parseFloat(data.added_tax_value) + parseFloat(data.discount_value) + parseFloat(data.other_discounts))">{{$t('global.The total price of the products is equal to the total price of the purchase invoice')}}</td>
                                                                <td colspan="9" v-else>{{$t('global.The total price of the products is greater than the total price of the purchase invoice')}}</td>
                                                            </tr>
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                            <!--End Statistics-->

                                        </div>

                                        <button class="btn btn-primary mt-2" type="submit">{{$t('global.Submit')}}</button>
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
import {computed, onMounted, reactive,toRefs,ref,watch} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "edit",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const {id} = toRefs(props);
        const {t} = useI18n({});
        let loading = ref(false);
        let categories = ref([]);
        let clients = ref([]);
        let stores = ref([]);
        let productValidation = ref([]);
        //
        let batchValidation = ref([{
            money:{
                // required,
                // numeric
            },
            due_date:{
                // required
            },
        }]);
        //
        let totalProductPrice = ref(0);
        let totalProductQuantity = ref(0);

        let validateLTE = (index) =>{
            addJob.products[index].send = addJob.data.product[index].price_before_discount >= addJob.data.product[index].price_after_discount;
            DebitAmount();
        }
        let validateDueDate = (index) =>{
            addJob.batches[index].sendDueDate = addJob.data.batch[index].due_date > new Date().toISOString().split('T')[0];
        }

        let DebitAmount = () => {
            totalProductPrice.value = 0;
            totalProductQuantity.value = 0;
            addJob.data.product.forEach((el) => {
                totalProductPrice.value += parseFloat(el.price_after_discount) * parseInt( el.quantity);
                totalProductQuantity.value += parseInt( el.quantity);
            });
        }

        let getData = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/saleInvoice/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    console.log(l);
                    categories.value = l.categories;
                    stores.value     = l.stores;
                    clients.value    = l.clients;
                    addJob.data.type                 = l.sale.type ;
                    addJob.data.client_id            = l.sale.client_id ;
                    addJob.data.stock_id             = l.sale.stock_id;
                    addJob.data.payment_method       = l.sale.payment_method ;
                    addJob.data.purchase_type        = l.sale.purchase_type ;
                    addJob.data.price                = l.sale.price;
                    addJob.data.visa_percentage      = l.sale.visa_percentage;
                    addJob.data.visa_value           = l.sale.visa_value;
                    addJob.data.added_tax_percentage = l.sale.added_tax_percentage;
                    addJob.data.added_tax_value      = l.sale.added_tax_value;
                    addJob.data.discount_percentage  = l.sale.discount_percentage;
                    addJob.data.discount_value       = l.sale.discount_value;
                    addJob.data.other_discounts      = l.sale.other_discounts;
                    addJob.data.transfer_price       = l.sale.transfer_price;
                    addJob.data.note                 = l.sale.note;

                    l.sale.sale_products.forEach((el,index)=>{

                        productValidation.value.push({
                            price_before_discount: {
                                required,
                                numeric
                            },
                            price_after_discount: {
                                required,
                                numeric
                            },
                            quantity: {
                                required,
                                numeric
                            },
                            category_id:{
                                required
                            },
                            sub_category_id:{
                                required
                            },
                            product_id:{
                                required
                            }
                        });

                        addJob.data.product.push({
                            price_before_discount:el.price_before_discount,
                            price_after_discount:el.price_after_discount,
                            quantity:el.quantity,
                            product_id:el.product_id,
                            category_id:el.product.category_id,
                            sub_category_id:el.product.sub_category_id,
                            // subUnitMeasurement:el.product.sub_measurement_unit.name,
                            mainUnitMeasurement:el.product.main_measurement_unit.name,
                        });

                        addJob.subCategory.push({subCategory:[]});
                        addJob.products.push({products:[],send:true});
                        getSubCategory(el.product.category_id,index);

                        getProduct(el.product.category_id,el.product.sub_category_id,index);

                        getMeasurementUnit(el.product_id,index);

                        totalProductPrice.value += parseFloat(el.price_after_discount) * parseInt(el.quantity);
                        totalProductQuantity.value += parseInt(el.quantity);
                    });
                    //
                    l.sale.batches.forEach((el,index)=>{

                        batchValidation.value.push({
                            money: {
                                // required,
                                // numeric
                            },
                            due_date: {
                                // required,
                            },
                        });

                        addJob.data.batch.push({
                            money:el.money,
                            due_date:el.due_date,
                        });
                    });
                    //
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getData();
        });

        let getSubCategory = (id,index) => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/category/${id}`)
                .then((res) => {
                    let l = res.data.data;
                    addJob.subCategory[index].subCategory  = l.subCategories;
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        let getProduct = (category_id,sub_category_id,index) => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/purchaseInvoiceProduct?category_id=${category_id}&sub_category_id=${sub_category_id}`)
                .then((res) => {
                    let l = res.data.data;
                    addJob.products[index].products = l.products;
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        let getMeasurementUnit = (id,index) => {
            let products_data = [];
            let product=null;

            products_data = addJob.products[index].products;
            products_data.forEach(function(_product){
                if(_product.id==id){
                    return product=_product;
                }
            });

            // addJob.data.product[index].subUnitMeasurement = product.sub_measurement_unit.name;
            addJob.data.product[index].mainUnitMeasurement = product.main_measurement_unit.name;
        }

        let addJob =  reactive({
            data:{
                //
                batch:[
                    {
                        money:'',
                        due_date:'',
                    }
                ],
                //
                product:[],
                type:'Sale Invoice',
                client_id:'',
                stock_id:'',
                payment_method:'',
                purchase_type:'',
                price:0,
                visa_percentage:0,
                visa_value:0,
                added_tax_percentage:0,
                added_tax_value:0,
                discount_percentage:0,
                discount_value:0,
                other_discounts:0,
                transfer_price:0,
                note:'',
            },
            subCategory:[],
            products:[
                {products:[],send:true}
            ],
            //
            batches:[
                {batches:[],send:true}
            ]
            //
        });

        const rules = computed(() => {
            return {
                //
                batch:[
                    ...batchValidation.value
                ],
                //
                product:[
                    ...productValidation.value
                ],
                type:{
                    required,
                },
                client_id:{
                    required,
                },
                stock_id:{
                    required
                },
                payment_method:{
                    required,
                },
                purchase_type:{
                    required,
                },
                price:{
                    required,
                    numeric
                },
                visa_percentage:{
                    required,
                    numeric
                },
                visa_value:{
                    required,
                    numeric
                },
                added_tax_percentage:{
                    required,
                    numeric
                },
                added_tax_value:{
                    required,
                    numeric
                },
                discount_percentage:{
                    required,
                    numeric
                },
                discount_value:{
                    required,
                    numeric
                },
                other_discounts:{
                    required,
                    numeric
                },
                transfer_price:{
                    required,
                    numeric
                },
                note:{
                    required,
                    minLength: minLength(3),
                },
            }
        });

        watch(() => addJob.data.visa_percentage ,(after, before) => {
            addJob.data.visa_value = (after * addJob.data.price) /100;
        });
        watch(() => addJob.data.visa_value ,(after, before) => {
            addJob.data.visa_percentage =   (after*100)/addJob.data.price;
        });


        watch(() => addJob.data.added_tax_percentage ,(after, before) => {
            addJob.data.added_tax_value = (after * addJob.data.price) /100;
        });
        watch(() => addJob.data.added_tax_value ,(after, before) => {
            addJob.data.added_tax_percentage =   (after*100)/addJob.data.price;
        });


        watch(() => addJob.data.discount_percentage ,(after, before) => {
            addJob.data.discount_value = (after * addJob.data.price) /100;
        });
        watch(() => addJob.data.discount_value ,(after, before) => {
            addJob.data.discount_percentage =   (after*100)/addJob.data.price;
        });

        const v$ = useVuelidate(rules,addJob.data);

        return {t,validateLTE,getProduct,getMeasurementUnit,getSubCategory,loading,...toRefs(addJob),v$,DebitAmount,categories,clients,stores,totalProductQuantity,totalProductPrice,productValidation,validateDueDate,batchValidation};
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/saleInvoice/${this.id}`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });
                    })
                    .catch((err) => {
                        console.log(err.response.data);
                        // this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        addDebit(){
            this.data.product.push({ price_before_discount:null,price_after_discount:null,quantity:null, category_id:'', sub_category_id:'', product_id:''});
            this.productValidation.push({
                price_before_discount: {
                    required,
                    numeric
                },
                price_after_discount: {
                    required,
                    numeric
                },
                quantity: {
                    required,
                    numeric
                },
                category_id:{
                    required
                },
                sub_category_id:{
                    required
                },
                product_id:{
                    required
                }
            });

            this.subCategory.push({subCategory:[]});
            this.products.push({products:[],send:true});
            this.$nextTick(() => { this.v$.$reset() });
        },
        deleteDebit(index){
            this.data.product.splice(index,1);
            this.productValidation.splice(index,1);
            this.subCategory.splice(index,1);
            this.products.splice(index,1);
            this.$nextTick(() => { this.v$.$reset() });
            this.DebitAmount();
        },
        //
        addBatch(){
            this.data.batch.push({
                money:'',
                due_date:'',
            });
            this.batchValidation.push({
                money:{
                    // required,
                    // numeric
                },
                due_date:{
                    // required
                },
            });

            this.batches.push({batches:[],send:true});
            this.$nextTick(() => { this.v$.$reset() });
        },
        deleteBatch(index){
            this.data.batch.splice(index,1);
            this.batchValidation.splice(index,1);
            this.batches.splice(index,1);
            this.$nextTick(() => { this.v$.$reset() });
            this.DebitAmount();
        },
        //
    }
}
</script>

<style scoped>
.coustom-select {
    height: 100px;
}
.card{
    position: relative;
}
.account{
    background-color: #0E67D0;
    color: #000000 !important;
    border-radius: 5px;
}
.head-account{
    display: flex;
    justify-content: center;
}
.head-account h3{
    color: #000000 !important;
    font-weight: bold;
}
.body-account{
    border-top: 3px solid #000000;
    margin: 0 !important;
}
.text-height{
    height: 46px !important;
}
.error-amount{
    display: flex;
    justify-content: center;
    color: red;
    margin: 10px;
}
</style>
