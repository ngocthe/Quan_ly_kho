import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/doitacs",
        method: "get",
        params,
        responseType
    });
}
export function store(data) {
    return request({
        url: "/doitacs",
        method: "post",
        data
    });
}
export function update(id, data) {
    return request({
        url: `/doitacs/${id}`,
        method: "put",
        data
    });
}
export function destroy(id) {
    return request({
        url: `/doitacs/${id}`,
        method: "delete"
    });
}
