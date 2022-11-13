import AboutInformationsIndex from "../../view/admin/about-informations/index";
import store from "../../store/admin";
export default [
    {
        path: 'about-informations',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'AboutInformationsIndex',
                component: AboutInformationsIndex,
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
