import TopFooterSectionForm from "../../view/admin/top-footer-section/form";
import store from "../../store/admin";
export default [
    {
        path: 'top-footer-sections',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'TopFooterSectionForm',
                component: TopFooterSectionForm,
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
