import AboutSectionIndex from "../../view/admin/about-section/index";
import store from "../../store/admin";
export default [
    {
        path: 'about-sections',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'AboutSectionIndex',
                component: AboutSectionIndex,
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
