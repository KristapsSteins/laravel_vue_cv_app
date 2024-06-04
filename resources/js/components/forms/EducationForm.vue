<template>
    <EducationItem
        v-for="education in sortedEducations"
        :key="education.id"
        :education="education"
        @delete="deleteEducation"
        @edit="editEducation"
        :editable="true"
    />
    <hr />
    <AddMoreButton
        item="Education"
        :showButton="!educationFormOpen"
        @click="toggleEducationForm"
    />
    <form @submit.prevent="submitForm" v-if="educationFormOpen">
        <InputGroup :inputs="inputGroup" :form="form" :errors="errors" />
        <InputField label="School" id="school" :form="form" :errors="errors" />
        <DatePickerGroup
            :inputs="dateGroupInputs"
            :form="form"
            :errors="errors"
            :customErrors="customErrors"
        />
        <div class="d-flex justify-content-end">
            <CheckboxField
                label="Currently studying"
                id="currently_studying"
                :form="form"/>
        </div>
        <button
            class="btn btn-primary my-3 w-100"
            type="submit"
            :disabled="isSubmitting">
            <i class="fa-solid fa-floppy-disk" style="padding-right: 5px"></i>
            Save
        </button>
    </form>
</template>

<script>
import axios from "axios";
import errorHandlingMixin from "../../errorHandlingMixin";
import InputGroup from "../inputs/InputGroup.vue";
import InputField from "../inputs/InputField.vue";
import DatePickerGroup from "../inputs/DatePickerGroup.vue";
import CheckboxField from "../inputs/CheckboxField.vue";
import Card from "../shared/Card.vue";
import AddMoreButton from "../shared/AddMoreButton.vue";
import EducationItem from "../items/EducationItem.vue";
import format from "../../formatMixin";

export default {
    components: {
        InputGroup,
        InputField,
        DatePickerGroup,
        CheckboxField,
        Card,
        AddMoreButton,
        EducationItem,
    },
    props: {
        id: {
            type: [String, Number],
            default: null,
        },
        canNextStep: {
            type: Boolean,
            default: false,
        },
        educations: {
            type: Array,
            default: () => [],
        },
    },
    mixins: [errorHandlingMixin, format],
    data() {
        return {
            form: this.getInitialFormState(),
            customErrors: {
                school_end_date: null,
            },
            educationFormOpen: false,
            inputGroup: [
                { label: "Degree", id: "degree" },
                { label: "City/Town", id: "school_location" },
            ],
            dateGroupInputs: [
                { label: "Start Date", id: "school_start_date" },
                { label: "End Date", id: "school_end_date" },
            ],
            isSubmitting: false,
            editingEducationId: null,
        };
    },
    methods: {
        editEducation(id) {
            const education = this.educations.find(edu => edu.id === id);
            if (education) {
                this.form = { ...education };
                this.editingEducationId = id;
                this.educationFormOpen = true;
            }
            this.$nextTick(() => {
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            });
        },
        getInitialFormState() {
            return {
                id: this.editingEducationId,
                resume_id: this.id,
                degree: "",
                school_location: "",
                school: "",
                school_start_date: "",
                school_end_date: "",
                currently_studying: false,
            };
        },
        submitForm() {
            if (!this.form.school_end_date && !this.form.currently_studying) {
                this.customErrors.school_end_date =
                    "Either end date or currently studying must be provided";
                return;
            }
            if (
                this.form.school_end_date &&
                this.form.school_start_date > this.form.school_end_date
            ) {
                this.customErrors.school_end_date =
                    "End date cannot be earlier than start date";
                return;
            }
            this.isSubmitting = true;
            axios
                .post("/api/education/create", this.form)
                .then((response) => {
                    const index = this.educations.findIndex(edu => edu.id === response.data.education.id);
                    if (index !== -1) {
                        this.educations.splice(index, 1, response.data.education);
                    } else {
                        this.educations.push(response.data.education);
                    }
                    this.form.id = null;
                    this.form.resume_id = null;
                    this.editingEducationId = null;
                    this.$emit("update-educations", this.educations);

                    this.clearErrors();
                    this.educationFormOpen = false;
                    this.form = this.getInitialFormState();
                })
                .catch((error) => {
                    this.handleError(error);
                })
                .finally(() => {
                    this.isSubmitting = false;
                });
        },
        deleteEducation(id) {
            axios
                .delete(`/api/education/${id}`)
                .then(() => {
                    let updatedEducations = this.educations.filter(
                        (education) => education.id !== id
                    );
                    this.$emit('update-educations', updatedEducations);
                })
                .catch((error) => {
                    this.handleError(error);
                });
        },
        toggleEducationForm() {
            this.educationFormOpen = !this.educationFormOpen;
            if (this.educationFormOpen) {
                this.form = this.getInitialFormState();
            }
        },
        clearErrors() {
            this.customErrors.school_end_date = null;
        },
    },
    watch: {
        educations: {
            handler() {
                this.$emit("update:can-next-step", this.educations.length > 0);
            },
            deep: true,
        },
        "form.currently_studying": function (newValue) {
            if (newValue) {
                this.form.school_end_date = "";
            }
        },
    },
    computed: {
        canNextStep() {
            return this.educations.length > 0;
        },
        sortedEducations() {
            return this.educations.sort((a, b) => {
                if (a.currently_studying === b.currently_studying) {
                    if (a.school_end_date === b.school_end_date) {
                        return (
                            new Date(b.school_start_date) -
                            new Date(a.school_start_date)
                        );
                    }
                    return (
                        new Date(b.school_end_date) -
                        new Date(a.school_end_date)
                    );
                }
                return a.currently_studying ? -1 : 1;
            });
        },
    },
};
</script>
