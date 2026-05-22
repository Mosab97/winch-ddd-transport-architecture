import { onMounted, reactive, ref } from 'vue';
import { assignOrder, getOrders } from '../services/ordersApi';

export function useOrders() {
    const orders = ref([]);
    const meta = ref({});
    const loading = ref(false);
    const error = ref('');
    const successMessage = ref('');
    const assigningOrderIds = reactive({});
    const filters = reactive({
        status: '',
    });
    const pagination = reactive({
        page: 1,
        perPage: 15,
    });

    async function fetchOrders() {
        loading.value = true;
        error.value = '';

        try {
            const response = await getOrders({
                page: pagination.page,
                per_page: pagination.perPage,
                status: filters.status,
            });

            orders.value = response.data;
            meta.value = response.meta;
        } catch (requestError) {
            error.value = requestError.message;
            orders.value = [];
            meta.value = {};
        } finally {
            loading.value = false;
        }
    }

    async function setStatusFilter(status) {
        filters.status = status;
        pagination.page = 1;
        await fetchOrders();
    }

    async function goToPage(page) {
        pagination.page = page;
        await fetchOrders();
    }

    async function assignPendingOrder(orderId) {
        assigningOrderIds[orderId] = true;
        error.value = '';
        successMessage.value = '';

        try {
            const response = await assignOrder(orderId);
            successMessage.value = response.message;
            await fetchOrders();
        } catch (requestError) {
            error.value = requestError.message;
        } finally {
            assigningOrderIds[orderId] = false;
        }
    }

    onMounted(fetchOrders);

    return {
        orders,
        meta,
        loading,
        error,
        successMessage,
        assigningOrderIds,
        filters,
        fetchOrders,
        setStatusFilter,
        goToPage,
        assignPendingOrder,
    };
}
