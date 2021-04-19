const dialogMixin = (store, update) => ({
    props: ["form", "editing", "showDialog", "options"],
    data: () => ({
        dialog: true,
        loading: false,
        valid: true
    }),
    watch: {
        showDialog(val) {
            if (val && !this.editing && this.$refs.form) {
                this.$refs.form.resetValidation();
            }
        }
    },
    methods: {
        closeDialog() {
            this.$emit("update:showDialog", false);
        },
        async createData() {
            try {
                await this.$refs.form.validate();
                if (!this.valid) return;
                this.loading = true;
                await store(this.form);
                this.reload();
            } catch (error) {
                this.loading = false;
            }
        },
        async updateData() {
            try {
                await this.$refs.form.validate();
                if (!this.valid) return;
                this.loading = true;
                await update(this.form.id, this.form);
                this.reload();
            } catch (error) {
                this.loading = false;
            }
        },
        reload() {
            this.loading = false;
            this.$snackbar(
                this.editing ? "Cập nhật thành công" : "Thêm mới thành công",
                "success"
            );
            this.closeDialog();
            this.$emit(this.editing ? "updated" : "created");
        }
    }
});

export default dialogMixin;
