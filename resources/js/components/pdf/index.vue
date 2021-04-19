<template>
    <vue-html2pdf
        :show-layout="false"
        :float-layout="true"
        :enable-download="false"
        :preview-modal="true"
        :paginate-elements-by-height="1400"
        filename="export"
        :pdf-quality="2"
        :manual-pagination="false"
        pdf-format="a4"
        pdf-orientation="portrait"
        pdf-content-width="800px"
        ref="html2Pdf"
    >
        <GRNContent slot="pdf-content" />
    </vue-html2pdf>
</template>
<script>
import VueHtml2pdf from "vue-html2pdf";
import GRNContent from "./GRNContent";
export default {
    methods: {
        /*
            Generate Report using refs and calling the
            refs function generatePdf()
        */
        generateReport() {
            this.$refs.html2Pdf.generatePdf();
        }
    },

    components: {
        VueHtml2pdf,
        GRNContent
    }
};
</script>
<style lang="scss" scoped>
.pdf-content {
    width: 100%;
    background: #fff;
}
#pdf-table {
    border-collapse: collapse;
    width: 100%;
    td,
    th {
        border-top: 1px solid black;
        border-left: 1px solid black;
        padding: 8px;
        text-align: center;
        &:last-child {
            border-right: 1px solid black;
        }
    }
    tr:last-child {
        td {
            border-bottom: 1px solid black;
        }
    }
    th {
        padding-top: 12px;
        padding-bottom: 12px;
    }
}
</style>
