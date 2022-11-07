import NeedHelpForm from "../../view/admin/need-help/form";
import store from "../../store/admin";
export default [
    {
        path: 'need-help',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'NeedHelpForm',
                component: NeedHelpForm,
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
