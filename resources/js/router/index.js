import { createRouter, createWebHistory } from 'vue-router';
import OrdersIndex from '../pages/OrdersIndex.vue';
import DriversIndex from '../pages/DriversIndex.vue';
import DriverOrders from '../pages/DriverOrders.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', redirect: '/orders' },
        { path: '/orders', name: 'orders.index', component: OrdersIndex },
        { path: '/drivers', name: 'drivers.index', component: DriversIndex },
        { path: '/drivers/:driverId/orders', name: 'drivers.orders', component: DriverOrders, props: true },
    ],
});

export default router;
