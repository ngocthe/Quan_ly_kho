import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/phanloais",
        method: "get",
        params,
        responseType
    });
}
export function store(data) {
    return request({
        url: "/phanloais",
        method: "post",
        data
    });
}
export function update(id, data) {
    return request({
        url: `/phanloais/${id}`,
        method: "put",
        data
    });
}
export function destroy(id) {
    return request({
        url: `/phanloais/${id}`,
        method: "delete"
    });
}



