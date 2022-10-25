import TermAndConditionIndex from "../../view/admin/terms-and-conditions/index";
import store from "../../store/admin";
export default [
    {
        path: 'terms-and-conditions',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'TermAndConditionIndex',
                component: TermAndConditionIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('termAndCondition read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
