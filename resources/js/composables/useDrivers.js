import { onMounted, reactive, ref } from 'vue';
import { getDrivers } from '../services/driversApi';

export function useDrivers() {
    const drivers = ref([]);
    const meta = ref({});
    const loading = ref(false);
    const error = ref('');
    const pagination = reactive({
        page: 1,
        perPage: 15,
    });

    async function fetchDrivers() {
        loading.value = true;
        error.value = '';

        try {
            const response = await getDrivers({
                page: pagination.page,
                per_page: pagination.perPage,
            });

            drivers.value = response.data;
            meta.value = response.meta;
        } catch (requestError) {
            error.value = requestError.message;
            drivers.value = [];
            meta.value = {};
        } finally {
            loading.value = false;
        }
    }

    async function goToPage(page) {
        pagination.page = page;
        await fetchDrivers();
    }

    onMounted(fetchDrivers);

    return {
        drivers,
        meta,
        loading,
        error,
        fetchDrivers,
        goToPage,
    };
}
