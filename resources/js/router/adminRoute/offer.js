import OfferIndex from "../../view/admin/offers/index";
import store from "../../store/admin";
export default [
    {
        path: 'offers',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'OfferIndex',
                component: OfferIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('offer read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
