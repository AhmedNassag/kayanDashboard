import ClientIndex from "../../view/admin/clients/index";
import store from "../../store/admin";
export default [
    {
        path: 'clients',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'ClientIndex',
                component: ClientIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('client read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
