import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/khos",
        method: "get",
        params,
        responseType
    });
}



export function addNhapKhoAdmin(data) {
    return request({
        url: "/adminnhapkhos",
        method: "post",
        data
    });
}
export function getPL(params) {
    return request({
        url: "/nhapKhoAdmin2",
        method: "get",
        params
    });
}
export function xuonghang(params, responseType = "json") {
    return request({
        url: "/xuong_hang",
        method: "get",
        params,
        responseType
    });
}
export function store(data) {
    return request({
        url: "/khos",
        method: "post",
        data
    });
}
export function update(id, data) {
    return request({
        url: `/khos/${id}`,
        method: "put",
        data
    });
}

export function getsophieu(id) {
    return request({
        url: `/getsophieu/${id}`,
        method: "get"
    });
}
export function tonkho(id, params) {
    return request({
        url: `/tonkhos/${id}`,
        method: "get",
        params

    });
}
export function duyet(id) {
    return request({
        url: `/xuonghang/duyet/${id}`,
        method: "put",
        data
    });
}
export function destroy(id) {
    return request({
        url: `/khos/${id}`,
        method: "delete"
    });
}



