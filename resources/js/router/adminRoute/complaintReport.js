import indexComplaintReport from "../../view/admin/complaintReport/index";
import store from "../../store/admin";

export default [
    {
        path: 'complaintReport',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexComplaintReport',
                component: indexComplaintReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('complaintReport read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
