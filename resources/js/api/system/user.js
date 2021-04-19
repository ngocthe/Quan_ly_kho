import request from "@/utils/request";

export function login(data) {
    return request({
        url: "/auth/login",
        method: "post",
        data
    });
}

export function initCSRFToken() {
    return request({
        url: process.env.MIX_VUE_APP_BASE_URL + "/sanctum/csrf-cookie",
        method: "get",
    });
}

export function getInfo() {
    return request({
        url: "/auth/me",
        method: "get"
    });
}

export function logout() {
    return request({
        url: "/auth/logout",
        method: "get"
    });
}

export function index(params) {
    return request({
        url: "/users",
        method: "get",
        params
    });
}
export function update(id, data) {
    return request({
        url: `/users/${id}`,
        method: "put",
        data
    });
}
export function destroy(id) {
    return request({
        url: `/users/${id}`,
        method: "delete"
    });
}
export function store(data) {
    return request({
        url: `/users`,
        method: "post",
        data
    });
}
export function changePassword(data) {
    return request({
        url: "/auth/password/change",
        method: "post",
        data
    });
}
export function changeAvatar(data) {
    return request({
        url: "/users/avatar",
        method: "post",
        data
    });
}
