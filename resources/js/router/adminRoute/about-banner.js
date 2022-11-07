import AboutBannerIndex from "../../view/admin/about-banner/index";
import store from "../../store/admin";
export default [
    {
        path: 'about-banners',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'AboutBannerIndex',
                component: AboutBannerIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('about read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
