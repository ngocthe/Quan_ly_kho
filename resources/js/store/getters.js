const getters = {
    barColor: state => state.app.barColor,
    barImage: state => state.app.barImage,
    drawer: state => state.app.drawer,
    snackbarShow: state => state.app.snackbarShow,
    snackbarText: state => state.app.snackbarText,
    snackbarType: state => state.app.snackbarType,
    isAuth: state => state.user.auth,
    company: state => state.user.company,

    id: state => state.user.id,
    // avatar: state => state.user.avatar,
    name: state => state.user.name,
    lang: state => state.user.lang,
    username: state => state.user.username,
    avatar: state => state.user.avatar,
    roles: state => state.user.roles,
    menus: state => state.user.menus,
    routes: state => state.user.routes,
    phone_number: state => state.user.phone_number,
    // position: state => state.user.position,
    // department: state => state.user.department,
    email: state => state.user.email,
    home_url: state => state.user.home_url,
    loader: state => state.app.loader,
    barImage: state => state.app.barImage,
    tonkhoId: state => state.app.tonkhoId,

    // tiny_drive_token: state => state.user.tiny_drive_token
};
export default getters;
