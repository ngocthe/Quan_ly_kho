import request from "@/utils/request";

export function getSalesReport(params, responseType = "json") {
    return request({
        url: "/reports/sales-report",
        method: "get",
        params,
        responseType
    });
}
export function getSalesByProductReport(params, responseType = "json") {
    return request({
        url: "/reports/sales-by-product-report",
        method: "get",
        params,
        responseType
    });
}
