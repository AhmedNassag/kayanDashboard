import OurStoreForm from "../../view/admin/our-store/form";
import store from "../../store/admin";
export default [
    {
        path: 'our-store',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'OurStoreForm',
                component: OurStoreForm,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('footer read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
