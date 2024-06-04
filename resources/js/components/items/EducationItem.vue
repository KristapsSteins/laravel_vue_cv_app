<template>
    <div class="mb-3">
        <div
            class="row mx-1 mb-2 p-2"
            :class="{ 'shadow-sm bg-white border rounded py-2': editable }">
            <div class="col d-flex flex-column gap-0">
                <div class="row">
                    <div class="col-lg-6 fw-bold item_text">
                        {{ education.degree }}
                    </div>
                    <div class="col-lg-6 item_text text-end">
                        {{ formatDate(education.school_start_date) }} - {{education.currently_studying ? "now" : formatDate(education.school_end_date)}}
                    </div>
                </div>
                <span class="item_text">{{ education.school }}</span>
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
    props: ["education", "editable"],
    methods: {
        emitDelete() {
            if (window.confirm('Are you sure you want to delete this education?')) {
                this.$emit("delete", this.education.id);
            }
        },
        emitEdit() {
            this.$emit("edit", this.education.id);
        },
    },
};
</script>
