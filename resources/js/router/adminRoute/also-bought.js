import AlsoBoughtIndex from "../../view/admin/also-bought/index";
import store from "../../store/admin";

export default [
    {
        path: 'also-bought',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'AlsoBoughtIndex',
                component: AlsoBoughtIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('also-bought read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
