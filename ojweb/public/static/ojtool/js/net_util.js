var csgn = {
    ajax: async function(method, url, data={}, dtype='json') {
        let fetchBody = {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            },
        }
        if(method.toLowerCase() == 'get') {
            let tmp = this.Json2Url(data);
            if(tmp !== undefined) {
                if(url.indexOf("?") != -1) {
                    url += "&"
                } else {
                    url += "?"
                }
                url += tmp;
            }
        } else {
            fetchBody['body'] = data;
        }
        return fetch(url, fetchBody).then(response => response.json());
    },
    get: function(url, data={}, dtype='json') {
        return this.ajax('get', url, data, dtype);
    },
    post: function(url, data={}, dtype='json') {
        return this.ajax('post', url, data, dtype);
    },
    Json2Url(data) {
        return new URLSearchParams(data).toString();
    },
    Url2Json() {
        return Object.fromEntries(new URLSearchParams(location.search));
    },
    async_ajax: async function(method, url, data={}, dtype='json') {
        let fetchBody = {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            },
        }
        if(method.toLowerCase() == 'get') {
            let tmp = this.Json2Url(data);
            if(tmp !== undefined) {
                if(url.indexOf("?") != -1) {
                    url += "&"
                } else {
                    url += "?"
                }
                url += tmp;
            }
        } else {
            fetchBody['body'] = data;
        }
        const response = await fetch(url, fetchBody);
        return response.json();
    },
    async_get: async function(url, data={}, dtype='json') {
        return this.async_ajax('get', url, data, dtype);
    },
    async_post: async function(url, data={}, dtype='json') {
        return this.async_ajax('post', url, data, dtype);
    },
}