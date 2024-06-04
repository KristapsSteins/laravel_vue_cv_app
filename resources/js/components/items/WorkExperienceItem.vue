<template>
    <div class="mb-3">
        <div
            class="row mx-1 mb-2 py-1"
            :class="{ 'shadow-sm bg-white border rounded': editable }">
            <div class="col d-flex flex-column gap-0">
                <div class="d-flex pt-2 justify-content-between">
                    <span
                        ><span class="fw-bold">{{ experience.job_title }}</span>
                        {{
                            experience.is_remote ? "(Remote)" : "(On-site)"
                        }}</span>
                    <span>{{ formatDate(experience.start_date) }} -
                        {{
                            experience.is_current_job
                                ? "now"
                                : formatDate(experience.end_date)
                        }}</span>
                </div>
                <span>{{ experience.company_name }}</span>
                <ul>
                    <li
                        v-for="task in experience.job_description"
                        :key="task.id">
                        {{ task }}
                    </li>
                </ul>
            </div>
            <div
                class="col pt-2 d-flex gap-2 align-items-start justify-content-end"
                v-if="editable">
                <button class="btn btn-primary" @click="emitEdit">
                    <i class="fa-solid fa-pencil"></i>
                </button>
                <button class="btn btn-danger" @click="emitDelete">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import format from "../../formatMixin";

export default {
    mixins: [format],
    props: ["experience", "editable"],
    methods: {
        emitDelete() {
            if (window.confirm('Are you sure you want to delete this work experience?')) {
                this.$emit("delete", this.education.id);
            }
        },
        emitEdit() {
            console.log(this.experience.id, 'asddsasadas');
            this.$emit("edit", this.experience.id);
        },
    },
};
</script>
