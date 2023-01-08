import indexComplaint from "../../view/admin/complaint/index";
import createComplaint from "../../view/admin/complaint/create";
import editComplaint from "../../view/admin/complaint/edit";
import replyComplaint from "../../view/admin/complaint/reply";
import showComplaint from "../../view/admin/complaint/show";


import indexTypeComplaint from "../../view/admin/complaint/types/index";
import store from "../../store/admin";

export default [
    {
        path: 'complaint',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexComplaint',
                component: indexComplaint,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('complaint read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createComplaint',
                component: createComplaint,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('complaint create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editComplaint',
                component: editComplaint,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('complaint edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'reply/:id(\\d+)',
                name: 'replyComplaint',
                component: replyComplaint,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;
                    return next();
                }
            },
            {
                path: 'show/:id(\\d+)',
                name: 'showComplaint',
                component: showComplaint,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;
                    return next();
                }
            },
        ]
    },
    {
        path: 'types_of_complaints',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexTypeComplaint',
                component: indexTypeComplaint,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('complaint read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },

        ]
    },
];
