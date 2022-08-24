import ClientGroupIndex from "../../view/admin/client-groups/index";
import store from "../../store/admin";
export default [
    {
        path: 'client-groups',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'ClientGroupIndex',
                component: ClientGroupIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('client-group read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
