import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/debts",
        method: "get",
        params,
        responseType
    });
}
export function store(data) {
    return request({
        url: "/debts",
        method: "post",
        data
    });
}
export function update(id, data) {
    return request({
        url: `/debts/${id}`,
        method: "put",
        data
    });
}
export function destroy(id) {
    return request({
        url: `/debts/${id}`,
        method: "delete"
    });
}
