import schedule from "../../view/admin/schedule/scheduleGet";
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
