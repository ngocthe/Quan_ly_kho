export function getAuth() {
    return !!localStorage.getItem("auth");
}

export function setAuth(val) {
    val ? localStorage.setItem("auth", true) : localStorage.removeItem("auth");
}
export function setCompany(company) {
    return localStorage.setItem("company", company);
}

export function getCompany() {
    return localStorage.getItem("company") || 1;
}
export function setLang(lang) {
    localStorage.setItem("lang", lang);
    window.location.reload();
}
export function getLang() {
    return localStorage.getItem("lang") || "vi";
}
