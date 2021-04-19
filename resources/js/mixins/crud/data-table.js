const dataTableMixin = destroy => ({
    props: ["tableData", "form", "pagination"],
    computed: {
        headers() {
            return [];
        }
    },
    methods: {
        getHeaders() {
            return this.headers;
        },
        async handleDelete(id) {
            try {
                const res = await this.$confirm(
                    this.$t("delete_confirm_text"),
                    {
                        title: this.$t("delete_confirm_title"),
                        buttonTrueText: this.$t("delete_confirm_true_text"),
                        buttonFalseText: this.$t("delete_confirm_false_text")
                    }
                );
                if (res) {
                    await destroy(id);
                    this.$snackbar(
                        this.$t("delete_success_message"),
                        "success"
                    );
                    this.$emit("handle-delete");
                }
            } catch (error) {
                console.log(error);
            }
        }
    }
});

export default dataTableMixin;
