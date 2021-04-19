import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/xes",
        method: "get",
        params,
        responseType
    });
}
export function store(data) {
    return request({
        url: "/xes",
        method: "post",
        data
    });
}
export function update(id, data) {
    return request({
        url: `/xes/${id}`,
        method: "put",
        data
    });
}
export function destroy(id) {
    return request({
        url: `/xes/${id}`,
        method: "delete"
    });
}
