<template>
    <vue-html2pdf
        :show-layout="true"
        :float-layout="true"
        :enable-download="false"
        :preview-modal="true"
        :paginate-elements-by-height="1400"
        filename="hee hee"
        :pdf-quality="2"
        :manual-pagination="true"
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
import GRNContent from "@/components/pdf/GRNContent";
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
<style lang="scss" scoped></style>
