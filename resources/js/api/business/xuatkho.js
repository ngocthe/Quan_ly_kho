import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/xuatkhos",
        method: "get",
        params,
        responseType
    });
}
export function store(data) {
    return request({
        url: "/xuatkhos",
        method: "post",
        data
    });
}
export function update(id, data) {
    return request({
        url: `/xuatkhos/${id}`,
        method: "put",
        data
    });
}
export function destroy(id) {
    return request({
        url: `/xuatkhos/${id}`,
        method: "delete"
    });
}



