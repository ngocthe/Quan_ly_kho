import request from "@/utils/request";

export function index(params, responseType = "json") {
    return request({
        url: "/thukhos",
        method: "get",
        params,
        responseType
    });
}
export function store(data) {
    return request({
        url: "/thukhos",
        method: "post",
        data
    });
}
export function update(id, data) {
    return request({
        url: `/thukhos/${id}`,
        method: "put",
        data
    });
}
export function destroy(id) {
    return request({
        url: `/thukhos/${id}`,
        method: "delete"
    });
}

export function getmonth(id,year) {
    return request({
        url: `/getmonth/${id}/${year}`,
        method: "get"
    });
}

export function getyear(id) {
    return request({
        url: `/getyear/${id}`,
        method: "get"
    });
}

export function getamount(id,year,month) {
    return request({
        url: `/getamount/${id}/${year}/${month}`,
        method: "get"
    });
}


