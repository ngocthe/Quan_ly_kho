import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/nhapkhos",
        method: "get",
        params,
        responseType
    });
}
export function index_admin(params, responseType = "json") {
    return request({
        url: "/nhapKhoAdmin",
        method: "get",
        params,
        responseType
    });
}
export function ghiso(data) {
    return request({
        url: "/ghiso",
        method: "post",
        data
    });
}

export function store(data) {
    return request({
        url: "/nhapkhos",
        method: "post",
        data
    });
}
export function update(id, data) {
    return request({
        url: `/nhapkhos/${id}`,
        method: "put",
        data
    });
}
export function updateSL(id, data) {
    return request({
        url: `/updateSL/${id}`,
        method: "put",
        data
    });
}

export function duyetpl(id) {
    return request({
        url: `/duyetpl/${id}`,
        method: "put"
    });
}
export function destroy(id) {
    return request({
        url: `/nhapkhos/${id}`,
        method: "delete"
    });
}



