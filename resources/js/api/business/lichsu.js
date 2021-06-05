import request from "@/utils/request";

export function index(params) {
    return request({
        url: "/histories",
        method: "get",
        params
    });
}


export function nhapphanloai(params) {
    return request({
        url: "/nhapphanloais",
        method: "get",
        params
    });
}




