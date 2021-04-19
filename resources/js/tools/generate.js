const { generateTemplateFiles } = require("generate-template-files");
generateTemplateFiles([
    {
        option: "Create Api File",
        defaultCase: "(noCase)",
        entry: {
            folderPath: "resources/js/tools/templates/api/__model__.js"
        },
        stringReplacers: [
            { question: 'Insert model name', slot: '__model__' },
            { question: 'Insert plural model name', slot: '__plural__' }
        ],
        output: {
            path: "resources/js/api/business/__model__.js",
        }
    },
    {
        option: "Create Template CRUD",
        defaultCase: "(noCase)",
        entry: {
            folderPath: "resources/js/tools/templates/view"
        },
        stringReplacers: ["__model__", "__parent__"],
        output: {
            path: "resources/js/views/dashboard/business/__parent__/__model__",
        }
    },
]);
