<template>
    <div class="modal" v-if="showModal" @click="closeModal">
        <div class="modal-content" @click.stop>
            <span class="close-button" @click="closeModal">&times;</span>
            <div class="modal-title mb-2">
                <h5>{{ this.previewResumeData.name }}</h5>
            </div>
            <div
                class="modal-body text-center text-break"
                style="min-height: 1122px; border: 1px solid"
                ref="resumeActions"
            >
                <h1 style="margin-top: 30px">
                    {{ this.previewResumeData.user_details.given_name }}
                    {{ this.previewResumeData.user_details.family_name }}
                </h1>
                <p>
                    {{ this.previewResumeData.user_details.email }} |
                    {{ this.previewResumeData.user_details.phone }}
                </p>
                <div class="section-title mb-2">Summary</div>
                <p>{{ this.previewResumeData.user_details.summary }}</p>
                <div class="section-title mb-2">Skills</div>
                <span v-for="skill in this.previewResumeData.skills"
                    >{{ skill }} |
                </span>
                <div class="section-title mt-3">Education</div>
                <div class="text-start">
                    <c
                        v-for="education in this.previewResumeData.educations"
                        :key="education.id"
                        :education="education"
                        :editable="false"
                    />
                </div>
                <div class="section-title mt-3">Work Experience</div>
                <div class="text-start">
                    <WorkExperienceItem
                        v-for="experience in this.previewResumeData.experiences"
                        :key="experience.id"
                        :experience="experience"
                        :editable="false"
                    />
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button class="btn btn-outline-dark" @click="closeModal">
                    Close
                </button>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" @click="handleDownload">
                        Download
                    </button>
                    <button class="btn btn-primary" @click="handlePrint">
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import EducationItem from "../items/EducationItem.vue";
import WorkExperienceItem from "../items/WorkExperienceItem.vue";
import { useVueToPrint } from "vue-to-print";
import html2pdf from "html2pdf.js";

export default {
    components: {
        EducationItem,
        WorkExperienceItem,
    },
    props: {
        showModal: Boolean,
        previewResumeData: Object,
    },
    setup() {
        const resumeActions = ref(null);
        const { handlePrint } = useVueToPrint({
            content: () => resumeActions.value,
            documentTitle: "Your Resume",
        });

        const handleDownload = () => {
            const opt = {
                margin: 1,
                filename: "myCV.pdf",
                image: { type: "jpeg", quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: {
                    unit: "in",
                    format: "letter",
                    orientation: "portrait",
                },
            };

            resumeActions.value.style.border = "none";
            resumeActions.value.style.minHeight = "";

            html2pdf()
                .set(opt)
                .from(resumeActions.value)
                .save()
                .then(() => {
                    resumeActions.value.style.border = "1px solid";
                    resumeActions.value.style.minHeight = "1122px";
                });
        };

        return { resumeActions, handlePrint, handleDownload };
    },
    methods: {
        closeModal() {
            this.$emit("update:showModal", false);
        },
    },
};
</script>

<style scoped>
.modal {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    z-index: 1;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
    min-height: 30%;
}

.modal-boy {
    font-family: "Georgia", sans-serif;
}

.close-button {
    position: absolute;
    right: 20px;
    top: 10px;
    color: #aaaaaa;
    font-size: 28px;
    font-weight: bold;
}

.close-button:hover,
.close-button:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
.modal-title {
    margin-top: 20px;
}
.section-title {
    display: flex;
    align-items: center;
    text-align: center;
    overflow: hidden;
    font-size: 1.5rem;
}

.section-title::before,
.section-title::after {
    content: "";
    flex-grow: 1;
    border-bottom: 1px dashed;
    margin: 0 10px;
}
</style>
