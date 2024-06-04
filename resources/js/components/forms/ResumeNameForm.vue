<template>
    <form @submit.prevent="submitForm">
        <InputField
            label="CV name"
            id="resume_name"
            :form="form"
            :errors="errors"
        />
        <div class="d-flex">
            <button
                class="btn btn-primary"
                type="submit"
                :disabled="isSubmitting">
                <i class="fa-solid fa-floppy-disk" style="padding-right: 5px"></i
                >Save
            </button>
        </div>
    </form>
</template>

<script>
import axios from "axios";
import errorHandlingMixin from "../../errorHandlingMixin";
import InputField from "../inputs/InputField.vue";

export default {
    props: ["resume", "id"],
    components: {
        InputField,
    },
    mixins: [errorHandlingMixin],
    data() {
        return {
            form: {
                id: this.id,
                resume_name: this.resume.name,
            },
            isSubmitting: false,
        };
    },
    watch: {
        "resume.name": function (newName) {
            this.form.resume_name = newName;
        },
        id: function (newId) {
            this.form.id = newId;
        },
    },
    methods: {
        submitForm() {
            this.isSubmitting = true;
            axios
                .post("/api/resume/create", this.form)
                .then((response) => {
                    this.$emit("update:id", response.data.id);
                    this.$emit("valid");
                    this.$emit("update-resume", response.data);
                    this.clearErrors();
                })
                .catch((error) => {
                    this.handleError(error);
                })
                .finally(() => {
                    this.isSubmitting = false;
                });
        },
    },
};
</script>
