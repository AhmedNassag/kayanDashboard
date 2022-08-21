import UnitIndex from "../../view/admin/unit/index";
import store from "../../store/admin";
export default [
    {
        path: 'units',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'UnitIndex',
                component: UnitIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('unit read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
