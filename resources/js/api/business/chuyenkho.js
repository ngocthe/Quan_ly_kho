import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/chuyenkhos",
        method: "get",
        params,
        responseType
    });
}
export function store(data) {
    return request({
        url: "/chuyenkhos",
        method: "post",
        data
    });
}
export function update(id, data) {
    return request({
        url: `/chuyenkhos/${id}`,
        method: "put",
        data
    });
}
export function destroy(id) {
    return request({
        url: `/chuyenkhos/${id}`,
        method: "delete"
    });
}



