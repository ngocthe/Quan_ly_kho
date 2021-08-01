import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/daugias",
        method: "get",
        params,
        responseType
    });
}
export function store(data) {
    return request({
        url: "/daugias",
        method: "post",
        data,
        headers: { "Content-Type": "multipart/form-data" }

    });
}
export function update(id, data) {
    return request({
        url: `/daugias/${id}`,
        method: "put",
        data
    });
}
export function destroy(id) {
    return request({
        url: `/daugias/${id}`,
        method: "delete"
    });
}
export function addkhachang(id,data) {
    return request({
        url: `/addkhachhang/${id}`,
        method: "put",
        data
    });
}
export function daugia(data) {
    return request({
        url: `/themdaugia`,
        method: "post",
        data
    });
}


export function show(id) {
    return request({
        url: `/showdaugias/${id}`,
        method: "get"
    });
}
