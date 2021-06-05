import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/nhapkhos",
        method: "get",
        params,
        responseType
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



