import schedule from "../../view/admin/schedule/scheduleGet";
//
import createSchedule from "../../view/admin/schedule/createSchedule";
import mobile_banners from "../../view/admin/schedule/update_mobile_banners";
import desktop_banners from "../../view/admin/schedule/update_desktop_banners";
import editSchedule from "../../view/admin/schedule/editSchedule";
import packagesSchedule from "../../view/admin/schedule/packages";
//
import showSchedule from "../../view/admin/schedule/editSchedule";
import indexJob from "../../view/admin/job";
import store from "../../store/admin";


export default [
    {
        path: 'schedule',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'scheduleGet',
                component: schedule,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('schedule read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            //
            {
                path: 'create',
                name: 'createSchedule',
                component: createSchedule,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;
                    if(permission.includes('schedule create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSchedule',
                component: editSchedule,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;
                    if(permission.includes('schedule edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            /****/
            {
                path: 'packagesSchedule',
                name: 'packagesSchedule',
                component: packagesSchedule,
                beforeEnter: (to, from,next) => {
                    // let permission = store.state.authAdmin.permission;
                    // if(permission.includes('packages')){
                        return next();
                    // }else{
                    //     return next({name:'Page404'});
                    // }
                }
            },
            {
                path: 'desktop_banners',
                name: 'desktop_banners',
                component: desktop_banners,
                beforeEnter: (to, from,next) => {
                    // let permission = store.state.authAdmin.permission;
                    // if(permission.includes('packages')){
                        return next();
                    // }else{
                    //     return next({name:'Page404'});
                    // }
                }
            },
            {
                path: 'mobile_banners',
                name: 'mobile_banners',
                component: mobile_banners,
                beforeEnter: (to, from,next) => {
                    // let permission = store.state.authAdmin.permission;
                    // if(permission.includes('packages')){
                        return next();
                    // }else{
                    //     return next({name:'Page404'});
                    // }
                }
            },
            /****/
            //
            {
                path: 'showSchedule/:id(\\d+)',
                name: 'showSchedule',
                component: showSchedule,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('schedule show')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
