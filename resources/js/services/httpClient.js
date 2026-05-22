const API_BASE_URL = '/api';

function buildUrl(endpoint, params = {}) {
    const url = new URL(`${API_BASE_URL}${endpoint}`, window.location.origin);

    Object.entries(params).forEach(([key, value]) => {
        if (value !== null && value !== undefined && value !== '') {
            url.searchParams.set(key, value);
        }
    });

    return url;
}

async function request(endpoint, options = {}) {
    const { params, headers, ...fetchOptions } = options;
    const response = await fetch(buildUrl(endpoint, params), {
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...headers,
        },
        ...fetchOptions,
    });

    const payload = await response.json().catch(() => ({
        message: 'Unexpected server response.',
    }));

    if (!response.ok) {
        throw new Error(payload.message || 'The request could not be completed.');
    }

    return payload;
}

export default {
    get(endpoint, params = {}) {
        return request(endpoint, { method: 'GET', params });
    },

    post(endpoint, body = {}) {
        return request(endpoint, {
            method: 'POST',
            body: JSON.stringify(body),
        });
    },
};
