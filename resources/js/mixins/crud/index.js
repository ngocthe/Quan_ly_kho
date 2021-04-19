const indexMixin = (index, options) => ({
    data() {
        return {
            model: "",
            showDialog: false,
            editing: false,
            defaultParams: {},
            params: {},
            pagination: {
                last_page: 1,
                total: 0
            },
            tableData: [],
            options: {},
            defaultForm: {},
            form: {
                id: undefined
            }
        };
    },
    methods: {
        async getData(page = null) {
            try {
                this.$loader(true);
                if (page) this.params.page = page;
                const { data, meta } = await index(this.params);
                this.tableData = data;
                this.pagination = meta;
                this.$router
                    .push({ path: this.$route.path, query: { ...this.params } })
                    .catch(err => {});
            } catch (error) {
                console.log(error);
            } finally {
                this.$loader(false);
            }
        },
        async getOption() {
            Object.keys(options), Object.values(options);
            try {
                const opts = {};
                let results = await Promise.all(
                    Object.values(options).map(option =>
                        option.func(option.params)
                    )
                );
                results = results.map(item => item.data);
                Object.keys(options).forEach((item, key) => {
                    opts[item] = results[key];
                });
                this.options = { ...opts };
            } catch (error) {
                console.log(error);
            }
        },
        showDialogForm(mode, data = null) {
            if (mode == "edit") {
                this.editing = true;
                for (let field in this.form) {
                    this.form[field] = data[field];
                }
            } else {
                this.form = JSON.parse(JSON.stringify(this.defaultForm));
                this.form.id = undefined;
                this.editing = false;
            }
            this.showDialog = true;
        },
        reset() {
            this.params = { ...this.defaultParams };
            this.getData();
        },
        getQueryParams() {
            this.params = { ...this.defaultParams };
            const query = this.$route.query;
            for (const key in query) {
                if (this.defaultParams.hasOwnProperty(key)) {
                    if (query[key] instanceof Array) {
                        if (this.defaultParams[key] instanceof Array)
                            this.params[key] = query[key].map(
                                item => Number(item) || item
                            );
                    } else
                        this.params[key] =
                            typeof this.defaultParams[key] === "string"
                                ? query[key]
                                : Number(query[key]) || "";
                }
            }
            console.log(this.params);
        },
        async exportData() {
            this.$loader(true);
            const data = await index({ ...this.params, export: true }, "blob");
            const url = URL.createObjectURL(
                new Blob([data], {
                    type:
                        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                })
            );
            console.log(data);
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", `${this.model || "export"}.xlsx`);
            document.body.appendChild(link);
            link.click();
            this.$loader(false);
        }
    },
    created() {
        this.defaultForm = JSON.parse(JSON.stringify(this.form));
        this.getQueryParams();
        if (options) this.getOption();
        this.getData();
    }
});

export default indexMixin;
