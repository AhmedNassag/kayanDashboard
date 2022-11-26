import adminApi from "../../../../api/adminAxios";
import { ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { notify } from "@kyvg/vue3-notification";
import { useRouter } from "vue-router";
export function ordersComposable() {
    const order = ref({});
    const products = ref({});
    const client = ref({});
    const client_orders = ref(0);
    const order_numbers = ref(0);
    const refund_allowed = ref('');
    const refund_date = ref(0);
    const router = useRouter();
    const loading = ref(false); //loading for button
    const message = ref(""); // message for notify
    const area = ref(""); // message for notify
    const state = ref(""); // message for notify
    const country = ref(""); // message for notify

    let orders = ref({}); //data for index page
    let debounce = ref({}); // for search
    const search = ref(""); //search in index page
    const filter_order_status = ref(""); //search in index page
    const filter_payment_status = ref(""); //search in index page
    const filter_payment_method = ref(""); //search in index page

    const { t,locale } = useI18n();

    watch(search, (search, prevSearch) => {
        clearTimeout(debounce.value);
        debounce.value = setTimeout(() => {
            getOrders();
        }, 500);
    });

    //get all orders
    const getOrders = async (page = 1) => {
        loading.value = true;

        await adminApi
            .get(`/v1/dashboard/orders?page=${page}&search=${search.value}&order_status=${filter_order_status.value}&payment_status=${filter_payment_status.value}&payment_method=${filter_payment_method.value}`)
            .then((res) => {
                orders.value = res.data.data.orders;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            loading.value = false;
    };


    //get category by id
    const getOrder = async (id) => {
        loading.value = true;
       await adminApi
            .get(`/v1/dashboard/orders/${id}`)
            .then((res) => {
                let response=res.data.data
                order.value =response.order
                state.value =response.order.city_name
                area.value =response.order.area_name
                products.value =response.products
                client.value =response.client
                client_orders.value =response.client_orders
                order_numbers.value =response.order_numbers
                refund_allowed.value =response.refund_allowed
                refund_date.value =response.refund_date
                console.log(response.products,response.client)
            })
            .catch((err) => {
                console.log(err);
            })
            loading.value = false;
    };


    // update order status
    const updateOrderStatus = async (id) => {
        loading.value = true;

        await adminApi
            .post(`/v1/dashboard/updateOrderStatus/${id}`)
            .then((res) => {
                notify({
                    title:
                        t("global."+res.data.message) +
                        `<i class="fas fa-check-circle"></i>`,
                    type: "success",
                    duration: 5000,
                    speed: 2000,
                });
                getOrder(id)

            })
            .catch((err) => {
                console.log(err.response);
            })
            loading.value = false;
    }


    //hold order
    const holdOrder = async (id) => {
        loading.value = true;

        await adminApi
            .post(`/v1/dashboard/holdOrder/${id}`)
            .then((res) => {
                notify({
                    title:
                        t("global."+res.data.message) +
                        `<i class="fas fa-check-circle"></i>`,
                    type: "success",
                    duration: 5000,
                    speed: 2000,
                });
                getOrder(id)
            })
            .catch((err) => {
                console.log(err.response);
            })
            loading.value = false;
    }


    //cancel order
    const cancelOrder = async (id) => {
        Swal.fire({
            title: t(`global.Are you sure you want to cancel this`),
            text: t("treasury.YouWontBeAbleToRevertThis"),
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
        }).then((result) => {
            if (result.isConfirmed) {
                loading.value = true;
                adminApi
                    .post(`/v1/dashboard/cancelOrder/${id}`)
                    .then((res) => {
                        notify({
                            title:
                                t("global."+res.data.message) +
                                `<i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000,
                        });
                        if(res.data.canceled)
                        router.push({name:'Orders',params: {lang:locale.value}})
                        getOrder(id)

                    })
                    .catch((err) => {
                        console.log(err.response);
                    })
                loading.value = false;
            }
        });

    }



    return {
        loading,
        message,
        search,
        filter_order_status,
        filter_payment_status,
        filter_payment_method,
        orders,
        order,
        area,
        state,
        country,
        client,
        client_orders,
        order_numbers,
        refund_allowed,
        refund_date,
        products,
        getOrders,
        getOrder,
        updateOrderStatus,
        holdOrder,
        cancelOrder
    };
}

//set errors dynamic in object
// function setErrors(errors, errors_array) {
//     if (errors_array) {
//         for (const el in errors_array) {
//             errors.value[el] = errors_array[el][0];
//         }
//     } else {
//         notify(
//             i18n.t("Unknown error,please try again later."),
//             "topright",
//             "error"
//         );
//     }
// }
