<template>
  <!-- Sidebar -->
  <div :class="['sidebar sidebar-ar']" id="sidebar">
    <div class="sidebar-inner slimscroll">
      <div id="sidebar-menu" class="sidebar-menu">
        <ul>
          <li :class="[$route.name == 'dashboard' ? 'active' : '']">
            <router-link :to="{ name: 'dashboard' }">
              <i class="fa fa-home" aria-hidden="true"></i>
              <span>{{ $t("sidebar.Dashboard") }}</span>
            </router-link>
          </li>

            <!-- Start Category Links -->
            <li class="submenu" v-if="permission.includes('management')">
                <a href="#">
                    <i class="fas fa-home"></i>
                    <span>{{ $t("global.Categories") }}</span>
                    <span :class="['menu-arrow', 'menu-arrow-ar']"></span>
                </a>

                <ul>
                    <li :class="[$route.name == 'indexCategory' ? 'active' : '']" v-if="permission.includes('category read')">
                        <router-link :to="{ name: 'indexCategory' }" :class="['sidebar-menu-rtl']">
                            {{ $t("global.MainCategories") }}
                        </router-link>
                    </li>

                    <li :class="[$route.name == 'indexSubCategory' ? 'active' : '']" v-if="permission.includes('subCategory read')">
                        <router-link :to="{ name: 'indexSubCategory' }" :class="['sidebar-menu-rtl']">
                            {{ $t("global.SubCategories") }}
                        </router-link>
                    </li>
                </ul>
            </li>
            <!-- End Category Links -->

          <!-- Start Selling Method Links -->
          <li
            v-if="permission.includes('sellingMethod read')"
            :class="[$route.name == 'indexSellingMethod' ? 'active' : '']"
          >
            <router-link :to="{ name: 'indexSellingMethod' }">
              <i class="fa fa-home" aria-hidden="true"></i>
              <span>{{ $t("global.Selling Methods") }}</span>
            </router-link>
          </li>
          <!-- End Selling Method Links -->
            <!-- Start Users Category Links -->
            <li v-if="permission.includes('usersCategory read')" :class="[$route.name == 'indexUsersCategory' ? 'active' : '']">
                <router-link :to="{ name: 'indexUsersCategory' }">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>{{ $t("global.usersCategory") }}</span>
                </router-link>
            </li>
            <!-- End Users Category Links -->

            <!-- Start Tax Links -->
            <li v-if="permission.includes('tax read')" :class="[$route.name == 'indexTax' ? 'active' : '']">
                <router-link :to="{ name: 'indexTax' }">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>{{ $t("global.Taxes") }}</span>
                </router-link>
            </li>
            <!-- End Tax Links -->

            <!-- Start Company Links -->
            <li v-if="permission.includes('company read')" :class="[$route.name == 'indexCompany' ? 'active' : '']">
                <router-link :to="{ name: 'indexCompany' }">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>{{ $t("global.Companies") }}</span>
                </router-link>
            </li>
            <!-- End Company Links -->

          <li class="submenu" v-if="permission.includes('places')">
            <a href="#"
              ><i class="fas fa-home"></i>
              <span>{{ $t("sidebar.Places") }}</span>
              <span :class="['menu-arrow', 'menu-arrow-ar']"></span
            ></a>
            <ul>
              <li
                v-if="permission.includes('city read')"
                :class="[$route.name == 'CityIndex' ? 'active' : '']"
              >
                <router-link :to="{ name: 'CityIndex' }" :class="['sidebar-menu-rtl']">
                  {{ $t("sidebar.Cities") }}
                </router-link>
              </li>
              <li
                v-if="permission.includes('area read')"
                :class="[$route.name == 'AreaIndex' ? 'active' : '']"
              >
                <router-link :to="{ name: 'AreaIndex' }" :class="['sidebar-menu-rtl']">
                  {{ $t("sidebar.Areas") }}
                </router-link>
              </li>
            </ul>
          </li>

          <!-- Start Product Links -->
          <li class="submenu" v-if="permission.includes('management')">
            <a href="#">
              <i class="fas fa-home"></i>
              <span>{{ $t("global.Products") }}</span>
              <span :class="['menu-arrow', 'menu-arrow-ar']"></span>
            </a>
            <ul>
              <li
                v-if="permission.includes('productName read')"
                :class="[$route.name == 'indexProductName' ? 'active' : '']"
              >
                <router-link
                  :to="{ name: 'indexProductName' }"
                  :class="['sidebar-menu-rtl']"
                >
                  {{ $t("global.Product Names") }}
            <!-- Start Shift Links -->
            <li v-if="permission.includes('shift read')" :class="[$route.name == 'indexShift' ? 'active' : '']">
                <router-link :to="{ name: 'indexShift' }">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>{{ $t("global.Shifts") }}</span>
                </router-link>
            </li>
            <!-- End Shift Links -->

            <!-- Start Selling Method Links -->
            <li v-if="permission.includes('sellingMethod read')" :class="[$route.name == 'indexSellingMethod' ? 'active' : '']">
                    <router-link :to="{ name: 'indexSellingMethod' }">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <span>{{ $t("global.Selling Methods")}}</span>
                    </router-link>
                </li>
            <!-- End Selling Method Links -->

            <!-- Start Stocks Links -->
            <li class="submenu" v-if="permission.includes('management')">
                <a href="#">
                    <i class="fas fa-home"></i>
                    <span>{{ $t("global.Stocks") }}</span>
                    <span :class="['menu-arrow', 'menu-arrow-ar']"></span>
                </a>

                <ul>
                    <li v-if="permission.includes('stock read')" :class="[$route.name == 'indexStock' ? 'active' : '']">
                        <router-link :to="{ name: 'indexStock' }" :class="['sidebar-menu-rtl']">
                            {{ $t("global.Stocks") }}
                        </router-link>
                    </li>

                    <li v-if="permission.includes('virtualStock read')" :class="[$route.name == 'indexVirtualStock' ? 'active' : '']">
                        <router-link :to="{ name: 'indexVirtualStock' }" :class="['sidebar-menu-rtl']">
                            {{ $t("global.Virtual Stocks") }}
                        </router-link>
                    </li>
                </ul>
            </li>
            <!-- End Stocks Links -->

            <!-- Start Product Links -->
            <li class="submenu" v-if="permission.includes('management')">
                <a href="#">
                    <i class="fas fa-home"></i>
                    <span>{{ $t("global.Products") }}</span>
                    <span :class="['menu-arrow', 'menu-arrow-ar']"></span>
                </a>

                <ul>
                    <li v-if="permission.includes('productName read')" :class="[$route.name == 'indexProductName' ? 'active' : '']">
                        <router-link :to="{ name: 'indexProductName' }" :class="['sidebar-menu-rtl']">
                            {{ $t("global.Product Names") }}
                        </router-link>
                    </li>

              <li
                v-if="permission.includes('product read')"
                :class="[$route.name == 'indexProduct' ? 'active' : '']"
              >
                <router-link :to="{ name: 'indexProduct' }" :class="['sidebar-menu-rtl']">
                  {{ $t("global.Product Details") }}
                </router-link>
              </li>

              <!-- v-if="permission.includes('price read')" -->
              <!-- <li :class="[$route.name == 'indexPrice' ? 'active' : '']">
                        <router-link :to="{ name: 'indexPrice' }" :class="['sidebar-menu-rtl']">
                            {{ $t("global.Product Prices")}}
                        </router-link>
                    </li> -->
            </ul>
          </li>
          <!--End Product Links-->

          <!--Start Price Links-->
          <li class="submenu" v-if="permission.includes('management')">
            <a href="#">
              <i class="fas fa-home"></i>
              <span>{{ $t("global.Products Prices") }}</span>
              <span :class="['menu-arrow', 'menu-arrow-ar']"></span
            ></a>

            <ul>
              <li
                v-if="permission.includes('kayanPrice read')"
                :class="[$route.name == 'indexKayanPrice' ? 'active' : '']"
              >
                <router-link
                  :to="{ name: 'indexKayanPrice' }"
                  :class="['sidebar-menu-rtl']"
                >
                  {{ $t("global.Kayan Prices") }}
                </router-link>
              </li>

              <li
                v-if="permission.includes('price read')"
                :class="[$route.name == 'indexPrice' ? 'active' : '']"
              >
                <router-link :to="{ name: 'indexPrice' }" :class="['sidebar-menu-rtl']">
                  {{ $t("global.Prices") }}
                </router-link>
              </li>
            </ul>
          </li>
          <!--End Price Links-->

          <!-- Start Purchase Links -->
          <!-- <li v-if="permission.includes('purchase read')" :class="[$route.name == 'indexPurchase' ? 'active' : '']">
                <router-link :to="{ name: 'indexPurchase' }">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>{{ $t("global.Purchases")}}</span>
                </router-link>
            </li> -->
          <!-- End Purchase Links -->

          <!-- Start Refused Links -->
          <!-- <li v-if="permission.includes('refused read')" :class="[$route.name == 'indexRefused' ? 'active' : '']">
                <router-link :to="{ name: 'indexRefused' }">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>{{ $t("global.Refuseds")}}</span>
                </router-link>
            </li> -->
          <!-- End Refused Links -->

          <!-- <li v-if="permission.includes('product read')" :class="[$route.name == 'indexProduct' ? 'active' : '']">
                <router-link :to="{ name: 'indexProduct' }">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>{{ $t("global.Products")}}</span>
                </router-link>
            </li> -->
          <!-- End Product Links -->

          <!--start buy-->
          <li class="submenu" v-if="permission.includes('buy')">
            <a href="#"
              ><i class="fas fa-home"></i>
              <span> {{ $t("global.purchaseManagement") }}</span>
              <span :class="['menu-arrow menu-arrow-ar']"></span
            ></a>
            <ul>
              <li
                :class="[$route.name == 'indexPurchaseInvoice' ? 'active' : '']"
                v-if="permission.includes('PurchaseInvoice read')"
              >
                <router-link
                  :to="{ name: 'indexPurchaseInvoice' }"
                  :class="['sidebar-menu-rtl']"
                >
                  {{ $t("global.PurchaseInvoice") }}
                </router-link>
              </li>

              <li
                :class="[$route.name == 'indexExaminationRecord' ? 'active' : '']"
                v-if="permission.includes('examinationRecords read')"
              >
                <router-link
                  :to="{ name: 'indexExaminationRecord' }"
                  :class="['sidebar-menu-rtl']"
                >
                  {{ $t("global.examinationRecords") }}
                </router-link>
              </li>

              <li
                :class="[$route.name == 'indexPurchaseReturn' ? 'active' : '']"
                v-if="permission.includes('PurchaseReturn read')"
              >
                <router-link
                  :to="{ name: 'indexPurchaseReturn' }"
                  :class="['sidebar-menu-rtl']"
                >
                  {{ $t("global.PurchaseReturn") }}
                </router-link>
              </li>
            </ul>
          </li>
                    <li v-if="permission.includes('product read')" :class="[$route.name == 'indexProduct' ? 'active' : '']">
                        <router-link :to="{ name: 'indexProduct' }" :class="['sidebar-menu-rtl']">
                            {{ $t("global.Product Details")}}
                        </router-link>
                    </li>
                </ul>
            </li>
            <!--End Product Links-->

            <!--Start Price Links-->
            <li class="submenu" v-if="permission.includes('management')">
                <a href="#">
                    <i class="fas fa-home"></i>
                    <span>{{ $t("global.Products Prices") }}</span>
                    <span :class="['menu-arrow', 'menu-arrow-ar']"></span>
                </a>

                <ul>
                    <li v-if="permission.includes('kayanPrice read')" :class="[$route.name == 'indexKayanPrice' ? 'active' : '']">
                        <router-link :to="{ name: 'indexKayanPrice' }" :class="['sidebar-menu-rtl']">
                            {{ $t("global.Kayan Prices")}}
                        </router-link>
                    </li>

                    <li v-if="permission.includes('price read')" :class="[$route.name == 'indexPrice' ? 'active' : '']">
                        <router-link :to="{ name: 'indexPrice' }" :class="['sidebar-menu-rtl']">
                            {{ $t("global.Prices")}}
                        </router-link>
                    </li>
                </ul>
            </li>
            <!--End Price Links-->

            <!--Start Purchase Links-->
            <li class="submenu" v-if="permission.includes('buy')">
                <a href="#" ><i class="fas fa-home"></i> <span> {{ $t('global.purchaseManagement') }}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>

                <ul>
                    <li :class="[$route.name == 'indexPurchaseInvoice'? 'active': '']" v-if="permission.includes('PurchaseInvoice read')">
                        <router-link :to="{name:'indexPurchaseInvoice'}" :class="['sidebar-menu-rtl']">
                            {{ $t('global.PurchaseInvoice') }}
                        </router-link>
                    </li>

                    <li :class="[$route.name == 'indexExaminationRecord'? 'active': '']" v-if="permission.includes('examinationRecords read')">
                        <router-link :to="{name:'indexExaminationRecord'}" :class="['sidebar-menu-rtl']">
                            {{ $t('global.examinationRecords') }}
                        </router-link>
                    </li>

                    <li :class="[$route.name == 'indexPurchaseReturn'? 'active': '']" v-if="permission.includes('PurchaseReturn read')">
                        <router-link :to="{name:'indexPurchaseReturn'}" :class="['sidebar-menu-rtl']">
                            {{ $t('global.PurchaseReturn') }}
                        </router-link>
                    </li>
                </ul>
            </li>
            <!--End Purchase Links-->

            <!--Start Sales Links-->
            <li class="submenu" v-if="permission.includes('buy')">
                <a href="#" ><i class="fas fa-home"></i> <span> {{ $t('global.Sale Management') }}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>

                <ul>
                    <li :class="[$route.name == 'indexSaleInvoice'? 'active': '']" v-if="permission.includes('SaleInvoice read')">
                        <router-link :to="{name:'indexSaleInvoice'}" :class="['sidebar-menu-rtl']">
                            {{ $t('global.Sale Invoice') }}
                        </router-link>
                    </li>

                    <!-- <li :class="[$route.name == 'indexSaleRecord'? 'active': '']" v-if="permission.includes('saleRecords read')">
                        <router-link :to="{name:'indexSaleRecord'}" :class="['sidebar-menu-rtl']">
                            {{ $t('global.saleRecords') }}
                        </router-link>
                    </li>

                    <li :class="[$route.name == 'indexSaleReturn'? 'active': '']" v-if="permission.includes('saleReturn read')">
                        <router-link :to="{name:'indexSaleReturn'}" :class="['sidebar-menu-rtl']">
                            {{ $t('global.Sale Return') }}
                        </router-link>
                    </li> -->
                </ul>
            </li>
            <!--End Sales Links-->

            <!--Start Storage Links-->
            <li v-if="permission.includes('storage read')" :class="[$route.name == 'indexStorage' ? 'active' : '']">
                <router-link :to="{ name: 'indexStorage' }">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>{{ $t("global.Storages") }}</span>
                </router-link>
            </li>
            <!--End Storage Links-->

          <li
            v-if="permission.includes('unit read')"
            :class="[$route.name == 'UnitIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'UnitIndex' }">
              <i class="fa fa-balance-scale" aria-hidden="true"></i>
              <span>{{ $t("sidebar.Units") }}</span>
            </router-link>
          </li>
          <li
            v-if="permission.includes('know-us-way read')"
            :class="[$route.name == 'KnowUsWayIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'KnowUsWayIndex' }">
              <i class="fa fa-balance-scale" aria-hidden="true"></i>
              <span>{{ $t("sidebar.KnowUsWays") }}</span>
            </router-link>
          </li>

          <li
            v-if="permission.includes('offer read')"
            :class="[$route.name == 'OfferIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'OfferIndex' }">
              <i class="fas fa-boxes"></i><span>{{ $t("sidebar.Offers") }}</span>
            </router-link>
          </li>
          <li
            v-if="permission.includes('shipping read')"
            :class="[$route.name == 'ShippingIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'ShippingIndex' }">
              <i class="fa fa-truck" aria-hidden="true"></i>
              <span>{{ $t("sidebar.Shippings") }}</span>
            </router-link>
          </li>
          <li
            v-if="permission.includes('client-group read')"
            :class="[$route.name == 'ClientGroupIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'ClientGroupIndex' }">
              <i class="fa fa-users" aria-hidden="true"></i>
              <span>{{ $t("sidebar.ClientGroups") }}</span>
            </router-link>
          </li>
          <li
            v-if="permission.includes('supplier read')"
            :class="[$route.name == 'SupplierIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'SupplierIndex' }">
              <i class="fa fa-users" aria-hidden="true"></i>
              <span>{{ $t("sidebar.Suppliers") }}</span>
            </router-link>
          </li>
          <li
            v-if="permission.includes('client read')"
            :class="[$route.name == 'ClientIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'ClientIndex' }">
              <i class="fa fa-users" aria-hidden="true"></i>
              <span>{{ $t("sidebar.Clients") }}</span>
            </router-link>
          </li>
          <li
            v-if="permission.includes('sale-point read')"
            :class="[$route.name == 'SalePointIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'SalePointIndex' }">
              <i class="fa fa-users" aria-hidden="true"></i>
              <span>{{ $t("sidebar.SalePoints") }}</span>
            </router-link>
          </li>
          <li
            v-if="permission.includes('slider read')"
            :class="[$route.name == 'SliderIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'SliderIndex' }">
              <i class="fa fa-users" aria-hidden="true"></i>
              <span>{{ $t("sidebar.Sliders") }}</span>
            </router-link>
          </li>
          <li
            v-if="permission.includes('simple-advertise read')"
            :class="[$route.name == 'SimpleAdvertiseIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'SimpleAdvertiseIndex' }">
              <i class="fa fa-users" aria-hidden="true"></i>
              <span>{{ $t("sidebar.SimpleAdvertises") }}</span>
            </router-link>
          </li>

          <li
            v-if="permission.includes('newsletter read')"
            :class="[$route.name == 'NewsletterIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'NewsletterIndex' }">
              <i class="fa fa-users" aria-hidden="true"></i>
              <span>{{ $t("sidebar.Newsletters") }}</span>
            </router-link>
          </li>
          <li class="submenu" v-if="permission.includes('management')">
            <a href="#"
              ><i class="fas fa-suitcase"></i>
              <span>{{ $t("sidebar.Management") }}</span>
              <span :class="['menu-arrow', 'menu-arrow-ar']"></span
            ></a>
            <ul>
              <li
                :class="[$route.name == 'indexDepartment' ? 'active' : '']"
                v-if="permission.includes('department read')"
              >
                <router-link
                  :to="{ name: 'indexDepartment' }"
                  :class="['sidebar-menu-rtl']"
                >
                  {{ $t("sidebar.Department") }}
                </router-link>
              </li>
              <li
                :class="[$route.name == 'indexJob' ? 'active' : '']"
                v-if="permission.includes('job read')"
              >
                <router-link :to="{ name: 'indexJob' }" :class="['sidebar-menu-rtl']">
                  {{ $t("sidebar.Jobs") }}
                </router-link>
              </li>
            </ul>
          </li>

          <li class="submenu" v-if="permission.includes('role-employee')">
            <a href="#"
              ><i class="fas fa-user-tie"></i>
              <span> {{ $t("sidebar.Employees") }}</span>
              <span :class="['menu-arrow menu-arrow-ar']"></span
            ></a>
            <ul>
              <li
                :class="[$route.name == 'indexRole' ? 'active' : '']"
                v-if="permission.includes('role read')"
              >
                <router-link :to="{ name: 'indexRole' }" :class="['sidebar-menu-rtl']">
                  {{ $t("sidebar.Roles") }}
                </router-link>
              </li>

              <li
                :class="[$route.name == 'indexEmployee' ? 'active' : '']"
                v-if="permission.includes('employee read')"
              >
                <router-link
                  :to="{ name: 'indexEmployee' }"
                  :class="['sidebar-menu-rtl']"
                >
                  {{ $t("sidebar.Employees") }}
                </router-link>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!-- /Sidebar -->
</template>

<script>
import { ref, onMounted, reactive, computed } from "vue";
import { useStore } from "vuex";

export default {
  setup() {
    let store = useStore();

    let permission = computed(() => store.getters["authAdmin/permission"]);

    onMounted(() => {
      var $slimScrolls = $(".slimscroll");

      // Sidebar Slimscroll
      if ($slimScrolls.length > 0) {
        $slimScrolls.slimScroll({
          height: "auto",
          width: "100%",
          position: "right",
          size: "7px",
          color: "#ccc",
          allowPageScroll: false,
          wheelStep: 10,
          touchScrollStep: 100,
        });
        var wHeight = $(window).height() - 60;
        $slimScrolls.height(wHeight);
        $(".sidebar .slimScrollDiv").height(wHeight);
        $(window).resize(function () {
          var rHeight = $(window).height() - 60;
          $slimScrolls.height(rHeight);
          $(".sidebar .slimScrollDiv").height(rHeight);
        });
      }
    });

    return { permission };
  },
};

window.onload = (event) => {
  var Sidemenu = function () {
    this.$menuItem = $("#sidebar-menu a");
  };
  function init() {
    var $this = Sidemenu;
    $("#sidebar-menu a").on("click", function (e) {
      if ($(this).parent().hasClass("submenu")) {
        e.preventDefault();
      }
      if (!$(this).hasClass("subdrop")) {
        $("ul", $(this).parents("ul:first")).slideUp(350);
        $("a", $(this).parents("ul:first")).removeClass("subdrop");
        $(this).next("ul").slideDown(350);
        $(this).addClass("subdrop");
      } else if ($(this).hasClass("subdrop")) {
        $(this).removeClass("subdrop");
        $(this).next("ul").slideUp(350);
      }
    });
    $("#sidebar-menu ul li.submenu a.active")
      .parents("li:last")
      .children("a:first")
      .addClass("active")
      .trigger("click");
  }

  // Sidebar Initiate
  init();
};
</script>

<style>
.sidebar-ar {
  left: unset;
  right: 0;
}
.sidebar .sidebar-menu > ul > li > a span {
  margin-right: 10px;
}

.sidebar-menu li a {
  color: #000;
}

.sidebar-menu li a:hover {
  color: #fcb00c !important;
}

.sidebar-menu li.active > a {
  color: #fcb00c !important;
}

.menu-title {
  color: #fcb00c !important;
}

.show {
  display: block;
}
.sidebar {
  background-color: #fcb00c38;
}

.sidebar-menu .menu-arrow.menu-arrow-ar {
  left: 15px;
  right: unset;
}
.sidebar-menu-rtl {
  padding: 7px 45px 7px 10px !important;
}
.padding-en {
  padding: 7px 10px 7px 32px !important;
}
.drop-child {
  padding: none !important;
}
.drop-child span {
  float: none !important;
}
.t-right {
  text-align: right !important;
}
</style>
