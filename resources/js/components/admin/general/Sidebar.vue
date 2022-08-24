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
            <a href="#"
              ><i class="fas fa-suitcase"></i>
              <span>{{ $t("global.Categories") }}</span>
              <span :class="['menu-arrow', 'menu-arrow-ar']"></span
            ></a>
            <ul>
              <li
                :class="[$route.name == 'indexCategory' ? 'active' : '']"
                v-if="permission.includes('category read')"
              >
                <router-link
                  :to="{ name: 'indexCategory' }"
                  :class="['sidebar-menu-rtl']"
                >
                  {{ $t("global.MainCategories") }}
                </router-link>
              </li>

              <li
                :class="[$route.name == 'indexSubCategory' ? 'active' : '']"
                v-if="permission.includes('subCategory read')"
              >
                <router-link
                  :to="{ name: 'indexSubCategory' }"
                  :class="['sidebar-menu-rtl']"
                >
                  {{ $t("global.SubCategories") }}
                </router-link>
              </li>
            </ul>
          </li>
          <!-- End Category Links -->

          <!-- Start Users Category Links -->
          <li :class="[$route.name == 'indexUsersCategory' ? 'active' : '']">
            <router-link :to="{ name: 'indexUsersCategory' }">
              <i class="fa fa-home" aria-hidden="true"></i>
              <span>{{ $t("global.usersCategory") }}</span>
            </router-link>
          </li>
          <!-- End Users Category Links -->

          <!-- Start Tax Links -->
          <li :class="[$route.name == 'indexTax' ? 'active' : '']">
            <router-link :to="{ name: 'indexTax' }">
              <i class="fa fa-home" aria-hidden="true"></i>
              <span>{{ $t("global.Taxes") }}</span>
            </router-link>
          </li>
          <!-- End Tax Links -->

          <!-- Start Company Links -->
          <li :class="[$route.name == 'indexCompany' ? 'active' : '']">
            <router-link :to="{ name: 'indexCompany' }">
              <i class="fa fa-home" aria-hidden="true"></i>
              <span>{{ $t("global.Companies") }}</span>
            </router-link>
          </li>
          <!-- End Company Links -->

          <!-- Start Product Links -->
          <li :class="[$route.name == 'indexProduct' ? 'active' : '']">
            <router-link :to="{ name: 'indexProduct' }">
              <i class="fa fa-home" aria-hidden="true"></i>
              <span>{{ $t("global.Products") }}</span>
            </router-link>
          </li>
          <!-- End Product Links -->
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
            v-if="permission.includes('offer read')"
            :class="[$route.name == 'OfferIndex' ? 'active' : '']"
          >
            <router-link :to="{ name: 'OfferIndex' }">
              <i class="fas fa-boxes"></i
              ><span>{{ $t("sidebar.Offers") }}</span>
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
                <router-link
                  :to="{ name: 'indexJob' }"
                  :class="['sidebar-menu-rtl']"
                >
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
                <router-link
                  :to="{ name: 'indexRole' }"
                  :class="['sidebar-menu-rtl']"
                >
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
