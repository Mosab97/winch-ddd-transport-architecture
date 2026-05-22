import { onMounted, reactive, ref, watch } from 'vue';
import { getDriverOrders } from '../services/driversApi';

export function useDriverOrders(driverId) {
    const orders = ref([]);
    const meta = ref({});
    const loading = ref(false);
    const error = ref('');
    const filters = reactive({
        status: '',
    });
    const pagination = reactive({
        page: 1,
        perPage: 15,
    });

    async function fetchDriverOrders() {
        loading.value = true;
        error.value = '';

        try {
            const response = await getDriverOrders(driverId.value, {
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
        await fetchDriverOrders();
    }

    async function goToPage(page) {
        pagination.page = page;
        await fetchDriverOrders();
    }

    watch(driverId, () => {
        pagination.page = 1;
        fetchDriverOrders();
    });

    onMounted(fetchDriverOrders);

    return {
        orders,
        meta,
        loading,
        error,
        filters,
        fetchDriverOrders,
        setStatusFilter,
        goToPage,
    };
}
