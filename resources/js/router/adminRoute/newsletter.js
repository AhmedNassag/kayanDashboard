import NewsletterIndex from "../../view/admin/newsletter/index";
import store from "../../store/admin";
export default [
    {
        path: 'newsletters',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'NewsletterIndex',
                component: NewsletterIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('newsletter read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
