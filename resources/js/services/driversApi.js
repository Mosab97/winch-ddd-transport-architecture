import httpClient from './httpClient';

export function getDrivers(params) {
    return httpClient.get('/drivers', params);
}

export function getDriverOrders(driverId, params) {
    return httpClient.get(`/drivers/${driverId}/orders`, params);
}
