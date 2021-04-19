const path = require("path");

module.exports = {
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js/")
        }
    },
    node: {
        fs: "empty"
    },
    devServer: {
        disableHostCheck: true
    },
    output: {
        chunkFilename: 'js/chunks/[name].js',
    },
};
