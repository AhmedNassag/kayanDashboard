import SliderIndex from "../../view/admin/sliders/index";
import store from "../../store/admin";
export default [
    {
        path: 'sliders',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'SliderIndex',
                component: SliderIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('slider read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
