import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/__plural__",
        method: "get",
        params,
        responseType
    });
}
export function store(data) {
    return request({
        url: "/__plural__",
        method: "post",
        data
    });
}
export function update(id, data) {
    return request({
        url: `/__plural__/${id}`,
        method: "put",
        data
    });
}
export function destroy(id) {
    return request({
        url: `/__plural__/${id}`,
        method: "delete"
    });
}
