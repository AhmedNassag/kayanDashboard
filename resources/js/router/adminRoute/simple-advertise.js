import SimpleAdvertiseIndex from "../../view/admin/simple-advertises/index";
import store from "../../store/admin";
export default [
    {
        path: 'simple-advertises',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'SimpleAdvertiseIndex',
                component: SimpleAdvertiseIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('simple-advertise read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
