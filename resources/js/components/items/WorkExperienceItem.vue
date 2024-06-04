<template>
    <div class="mb-3">
        <div
            class="row mx-1 mb-2 py-1"
            :class="{ 'shadow-sm bg-white border rounded': editable }">
            <div class="col d-flex flex-column gap-0">
                <div class="row">
                    <div class="col-lg-6 fw-bold item_text">
                        {{ experience.job_title }}{{experience.is_remote ? " (Remote)" : " (On-site)"}}
                    </div>
                    <div class="col-lg-6 item_text text-end">
                        {{ formatDate(experience.start_date) }} - {{experience.is_current_job ? "now" : formatDate(experience.end_date)}}
                    </div>
                </div>
                <span class="item_text">{{ experience.company_name }}</span>
                <ul>
                    <li class="item_text"
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
