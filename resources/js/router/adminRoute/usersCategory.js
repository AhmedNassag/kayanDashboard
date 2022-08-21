import indexUsersCategory from "../../view/admin/usersCategory/index";
import createUsersCategory from "../../view/admin/usersCategory/create";
import editUsersCategory from "../../view/admin/usersCategory/edit";
import store from "../../store/admin";

export default [
    {
        path: 'usersCategory',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexUsersCategory',
                component: indexUsersCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('usersCategory read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createUsersCategory',
                component: createUsersCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('usersCategory create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editUsersCategory',
                component: editUsersCategory,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('usersCategory edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
