<template>
    <div class="card shadow bg-body-tertiary rounded">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <button
                    type="button"
                    class="btn btn-primary mb-3"
                    @click="createResume">
                    Create CV
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-stripe table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th class="text-center text-break" scope="col">Name</th>
                            <th class="text-center" scope="col">Completed</th>
                            <th class="text-center" scope="col">Created At</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(resume, index) in resumes"
                            :key="resume.id"
                            @click="previewResume(resume.id)">
                            <th scope="row">{{ index + 1 }}</th>
                            <td class="text-center">{{ resume.name }}</td>
                            <td
                                :class="
                                    resume.completed
                                        ? 'text-success icon-container text-center'
                                        : 'text-danger icon-container text-center'
                                ">
                                <i v-if="resume.completed" class="fas fa-check"></i>
                                <i v-else class="fas fa-times"></i>
                            </td>
                            <td class="text-center">
                                {{ formatDate(resume.created_at) }}
                            </td>
                            <td class="d-flex flex-nowrap justify-content-end">
                                <button
                                    type="button"
                                    class="btn btn-primary mx-1"
                                    @click="editResume(resume.id)">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-danger"
                                    @click.stop="deleteResume(resume.id)">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <ResumePreviewModal
        :showModal="isDialogVisible"
        @update:showModal="isDialogVisible = $event"
        :previewResumeData="previewResumeData"
    />
</template>

<script>
import axios from "axios";
import formatMixin from "../../formatMixin";
import { useRouter } from "vue-router";
import ResumePreviewModal from "../../components/shared/ResumePreviewModal.vue";

export default {
    components: {
        ResumePreviewModal,
    },
    mixins: [formatMixin],
    data: () => ({
        resumes: [],
        isDialogVisible: false,
        previewResumeData: null,
    }),
    setup() {
        const router = useRouter();
        return { router };
    },
    methods: {
        openPreview() {
            this.isDialogVisible = true;
        },
        previewResume(id) {
            axios
                .get(`/api/resume/data/${id}`)
                .then((response) => {
                    this.previewResumeData = response.data;
                    this.isDialogVisible = true;
                    console.log(response.data);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getAllResumes() {
            axios
                .get("/api/resume/list")
                .then((response) => {
                    this.resumes = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        createResume() {
            this.router.push("/resume/create");
        },
        deleteResume(id) {
            if (window.confirm('Are you sure you want to delete this resume?')) {
                axios
                    .delete(`/api/resume/${id}`)
                    .then((response) => {
                        this.getAllResumes();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        editResume(id) {
            this.router.push({
                name: "resume_create_with_id",
                params: { id: id },
            });
        },
    },
    mounted() {
        this.getAllResumes();
    },
};
</script>

<style scoped>
tr {
    cursor: pointer;
}
</style>
