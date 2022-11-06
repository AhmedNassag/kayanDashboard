import FooterLinkIndex from "../../view/admin/footer-links/index";
import store from "../../store/admin";

export default [
    {
        path: 'footer-links',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'FooterLinkIndex',
                component: FooterLinkIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('footer-link read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
