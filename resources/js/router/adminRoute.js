import { createRouter, createWebHistory } from 'vue-router';
import Index from '../view/admin/Dashboard.vue';
import department from './adminRoute/department';
import alternative from './adminRoute/alternative';
import product from './adminRoute/product';
import ProductsPricing from './adminRoute/ProductPricing';
import category from './adminRoute/category';
import subCategory from './adminRoute/subCategory';
import usersCategory from './adminRoute/usersCategory';
import tax from './adminRoute/tax';
import discount from './adminRoute/discount';
import company from './adminRoute/company';
import pharmacistForm from './adminRoute/pharmacistForm';
import price from './adminRoute/price';
import alternativePrice from './adminRoute/alternativePrice';
import kayanPrice from './adminRoute/kayanPrice';
import sellingMethod from './adminRoute/sellingMethod';
import shift from './adminRoute/shift';
import stock from './adminRoute/stock';
import virtualStock from './adminRoute/virtualStock';
import purchase from './adminRoute/purchase';
import saleInvoice from './adminRoute/saleInvoice';
import saleRecord from './adminRoute/saleRecord';
import saleReturn from './adminRoute/saleReturn';
import storage from './adminRoute/storage';
import adOwner from './adminRoute/adOwner';
import complaint from './adminRoute/complaint';
import packages from './adminRoute/package';
import schedule from './adminRoute/schedule';
//crm
import targetPlan from './adminRoute/targetPlan';
import TargetAchived from './adminRoute/TargetAchived';
import sellerCategory from './adminRoute/sellerCategory';
import leadsManagement from './adminRoute/leadsManagement';
import Leads from './adminRoute/Leads';
import clientReport from './adminRoute/clientReport';
import areaReport from './adminRoute/storeReport';
import saleReport from './adminRoute/saleReport';
import CategoryProductReport from './adminRoute/CategoryProductReport';
// import financialReport from './adminRoute/financialReport';
// import productReport from './adminRoute/productReport';
// import customerReport from './adminRoute/clientReport';
// import supplierReport from './adminRoute/supplierReport';
// import stockReport from './adminRoute/stockReport';
import complaintReport from './adminRoute/complaintReport';
// import delegateReport from './adminRoute/delegateReport';
// import regionReport from './adminRoute/regionReport';
// import purchaseReport from './adminRoute/purchaseReport';
// import saleReport from './adminRoute/saleReport';
import refused from './adminRoute/refused';
import job from './adminRoute/job';
import role from './adminRoute/role';
import employee from './adminRoute/employee';
import notification from '../view/admin/notifications';
import Page404 from '../view/admin/Page404.vue';
import middlewarePipeline from "./middlewarePipeline";
import store from "../store/admin";
import guest from "../middleware/admin/guest";
import checkToken from "../middleware/admin/checkToken";
import auth from "../middleware/admin/auth";
import login from "../view/admin/login";
import forgetPassword from "../view/admin/forgetPassword";
import resetPassword from "../view/admin/resetPassword";
import representative from "./adminRoute/representative";
import unit from './adminRoute/unit';
import bestSeller from './adminRoute/best-seller';
import alsoBought from './adminRoute/also-bought';
import mostPopular from './adminRoute/most-popular';
import unavailable_city_user from './adminRoute/unavailable-city-client';
import city from './adminRoute/city';
import area from './adminRoute/area';
import knowUsWay from './adminRoute/know-us-way';
import simpleAdvertise from './adminRoute/simple-advertise';
import slider from './adminRoute/slider';
import clientGroup from './adminRoute/client-group';
import offer from './adminRoute/offer';
import shipping from './adminRoute/shipping';
import supplier from './adminRoute/supplier';
import client from './adminRoute/client';
import newsletter from './adminRoute/newsletter';
import purchaseInvoice from './adminRoute/purchaseInvoice';
import examinationRecord from './adminRoute/examinationRecord';
import purchaseReturn from './adminRoute/purchaseReturn';
import earnedDiscount from './adminRoute/earnedDiscount';
import salePoint from './adminRoute/sale-point';
import termAndCondition from './adminRoute/term-and-condtion';
import footerLink from './adminRoute/footer-link';
import needHelp from './adminRoute/need-help';
import ourStore from './adminRoute/our-store';
import topFooterSection from './adminRoute/top-footer-section';
import aboutBanner from './adminRoute/about-banner';
import AccountStatement from "./adminRoute/AccountStatement";
import Assets from "./adminRoute/Assets";
import dailyRestriction from "./adminRoute/dailyRestriction";
import ExpenseAccounts from "./adminRoute/ExpenseAccounts";
import financialCenter from "./adminRoute/financialCenter";
import IncomeAccounts from "./adminRoute/IncomeAccounts";
import incomeList from "./adminRoute/incomeList";
import Opponents from "./adminRoute/Opponents";
import trialBalance from "./adminRoute/trialBalance";
import aboutSection from './adminRoute/about-section';
import aboutInformations from './adminRoute/about-informations';
//
import orderDirect from './adminRoute/orderDirect';
import orderOnline from "./adminRoute/orderOnline";
import orderReturned from "./adminRoute/orderReturned";
import orderDelivered from "./adminRoute/orderDelivered";
import orderIncomes from "./adminRoute/orderIncomes";

import capitalOwnerAccount from "./adminRoute/capitalOwnerAccount";
import expense from "./adminRoute/expense";
import income from "./adminRoute/income";
import incomeAndExpense from "./adminRoute/incomeAndExpense";
import transferringTreasury from "./adminRoute/transferringTreasury";
import treasuriesExpense from "./adminRoute/treasuriesExpense";
import treasuriesIncome from "./adminRoute/treasuriesIncome";
import treasury from "./adminRoute/treasury";
import purchaseExpenses from "./adminRoute/purchaseExpenses";
import supplierExpenses from "./adminRoute/supplierExpenses";
import clientExpenses from "./adminRoute/clientExpenses";
import supplierIncomes from "./adminRoute/supplierIncomes";
import clientIncome from "./adminRoute/clientIncome";
import SupplierAccountStatement from "./adminRoute/SupplierAccountStatement";
import ClientAccountStatement from "./adminRoute/ClientAccountStatement";
import purchaseReturnIncomes from "./adminRoute/purchaseReturnIncomes";
import setting from "./adminRoute/setting";
//
const routes = [
    {
        path: '/',
        name: 'loginLang',
        component: login,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/login',
        name: 'login',
        component: login,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/forget-password',
        name: 'forgetPassword',
        component: forgetPassword,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/reset-password',
        name: 'resetPassword',
        component: resetPassword,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/dashboard',
        name: 'partner',
        component: {
            template: '<router-view />',
        },
        meta: {
            middleware: [auth, checkToken]
        },
        children: [
            {
                path: '',
                name: 'dashboard',
                component: Index,
            },
            {
                path: 'notification',
                name: 'notification',
                component: notification,
            },
            ...department,
            ...job,
            ...employee,
            ...role,
            ...pharmacistForm,
            ...category,
            ...subCategory,
            ...usersCategory,
            ...tax,
            ...company,
            ...alternative,
            ...product,
            ...ProductsPricing,
            ...price,
            ...alternativePrice,
            ...kayanPrice,
            ...sellingMethod,
            ...shift,
            ...stock,
            ...slider,
            ...simpleAdvertise,
            ...virtualStock,
            ...purchase,
            ...earnedDiscount,
            ...saleInvoice,
            ...saleRecord,
            ...saleReturn,
            ...storage,
            ...adOwner,
            ...complaint,
            ...packages,
            ...schedule,
            ...targetPlan,
            ...TargetAchived,
            ...sellerCategory,
            ...leadsManagement,
            ...Leads,
            ...areaReport,
            ...clientReport,
            ...saleReport,
            ...CategoryProductReport,
            // ...financialReport,
            // ...productReport,
            // ...customerReport,
            // ...supplierReport,
            // ...stockReport,
            ...complaintReport,
            // ...delegateReport,
            // ...regionReport,
            // ...purchaseReport,
            // ...saleReport,
            ...refused,
            ...unit,
            ...termAndCondition,
            ...bestSeller,
            ...alsoBought,
            ...mostPopular,
            ...unavailable_city_user,
            ...city,
            ...area,
            ...knowUsWay,
            ...newsletter,
            ...offer,
            ...shipping,
            ...supplier,
            ...clientGroup,
            ...client,
            ...purchaseInvoice,
            ...examinationRecord,
            ...purchaseReturn,
            ...salePoint,
            ...footerLink,
            ...needHelp,
            ...ourStore,
            ...topFooterSection,
            ...aboutBanner,
            ...discount,

            ...AccountStatement,
            ...Assets,
            ...dailyRestriction,
            ...ExpenseAccounts,
            ...financialCenter,
            ...IncomeAccounts,
            ...incomeList,
            ...Opponents,
            ...trialBalance,
            ...aboutSection,
            ...aboutInformations,
            //
            ...orderDirect,
            ...orderOnline,
            ...orderReturned,
            ...orderDelivered,
            ...orderIncomes,
            ...capitalOwnerAccount,
            ...expense,
            ...income,
            ...incomeAndExpense,
            ...transferringTreasury,
            ...treasuriesExpense,
            ...treasuriesIncome,
            ...treasury,
            ...purchaseExpenses,
            ...supplierExpenses,
            ...clientExpenses,
            ...supplierIncomes,
            ...clientIncome,
            ...SupplierAccountStatement,
            ...ClientAccountStatement,
            ...purchaseReturnIncomes,
            ...setting,
            ...representative,
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'Page404',
        component: Page404
    },
];

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes
});


router.beforeEach((to, from, next) => {

    if (!to.meta.middleware) return next();
    const middleware = to.meta.middleware;
    const context = {
        to,
        from,
        next,
        store
    };
    return middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1)
    });
});

export default router;
