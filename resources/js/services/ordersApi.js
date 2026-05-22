import httpClient from './httpClient';

export function getOrders(params) {
    return httpClient.get('/orders', params);
}

export function assignOrder(orderId) {
    return httpClient.post(`/orders/${orderId}/assign`);
}
