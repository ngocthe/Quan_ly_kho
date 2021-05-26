import request from "@/utils/request";

export function index(params) {
    return request({
        url: "/histories",
        method: "get",
        params
    });
}



