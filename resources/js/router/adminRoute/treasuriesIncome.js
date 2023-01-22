import treasuriesIncome from "../../view/admin/Treasuriesincome/index.vue";

export default [
    {
        path: 'treasuriesIncome',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'treasuriesIncome',
                component: treasuriesIncome,
            }
        ]
    },
];
