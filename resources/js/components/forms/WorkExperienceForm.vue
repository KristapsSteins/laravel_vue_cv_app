<template>
    <WorkExperienceItem
        v-for="experience in sortedExperiences"
        :key="experience.id"
        :experience="experience"
        @delete="deleteExperience"
        @edit="editExperience"
        :editable="true"
    />
    <hr />
    <AddMoreButton
        item="Experiences"
        :showButton="!experienceFormOpen"
        @click="toggleExperienceForm"
    />
    <form @submit.prevent="submitForm" v-if="experienceFormOpen">
        <InputGroup :inputs="inputGroup" :form="form" :errors="errors" />
        <CheckboxField label="Remote" id="is_remote" :form="form" />
        <label class="mt-3" :for="'description' + index">Description:</label>
        <div
            class="d-flex gap-2" :class="{ 'mt-3': index !== 0 }"
            v-for="(description, index) in job_descriptions"
            :key="index">
            <textarea
                class="form-control"
                v-model="job_descriptions[index]"
                :id="'description' + index"
                rows="1"
                ref="description"
            ></textarea>
            <button
                type="button"
                class="btn btn-danger"
                style="max-height: 35px"
                v-if="index > 0 || index === 0 && job_descriptions.length > 2"
                @click="removeDescription(index)">
                <i class="fa-solid fa-trash"></i>
            </button>
            <button
                type="button"
                class="btn btn-primary"
                style="max-height: 35px"
                v-if="index === 0 && job_descriptions.length < 3"
                @click="addDescription"
                :disabled="job_descriptions.length >= 3">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <div class="mb-3"><small class="form-text text-muted">You can add a maximum of 3 descriptions.</small></div>
        <DatePickerGroup
            :inputs="dateGroupInputs"
            :form="form"
            :errors="errors"
            :customErrors="customErrors"
        />
        <div class="d-flex justify-content-end">
            <CheckboxField
                label="Currently working"
                id="is_current_job"
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
import format from "../../formatMixin";
import WorkExperienceItem from "../items/WorkExperienceItem.vue";

export default {
    components: {
        InputGroup,
        InputField,
        DatePickerGroup,
        CheckboxField,
        Card,
        AddMoreButton,
        WorkExperienceItem,
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
        experiences: {
            type: Array,
            default: () => [],
        },
    },
    mixins: [errorHandlingMixin, format],
    data() {
        return {
            form: this.getInitialFormState(),
            experienceFormOpen: false,
            inputGroup: [
                { label: "Title", id: "job_title" },
                { label: "Organization", id: "company_name" },
            ],
            dateGroupInputs: [
                { label: "Start Date", id: "start_date" },
                { label: "End Date", id: "end_date" },
            ],
            job_descriptions: [""],
            customErrors: {
                end_date: null,
            },
            isSubmitting: false,
            editingExperienceId: null,
        };
    },
    methods: {
        getInitialFormState() {
            return {
                id: this.editingExperienceId,
                resume_id: this.id,
                job_title: "",
                company_name: "",
                start_date: "",
                end_date: "",
                is_current_job: false,
                is_remote: false,
                job_description: [""],
            };
        },
        submitForm() {
            if (
                this.form.end_date &&
                this.form.start_date > this.form.end_date
            ) {
                this.customErrors.end_date =
                    "End date cannot be earlier than start date";
                return;
            }
            this.form.job_description = this.job_descriptions.filter(
                (description) => description !== ""
            );
            axios
                .post("/api/experience/create", this.form)
                .then((response) => {
                    const index = this.experiences.findIndex(edu => edu.id === response.data.experience.id);
                    if (index !== -1) {
                        this.experiences.splice(index, 1, response.data.experience);
                    } else {
                        this.experiences.push(response.data.experience);
                    }
                    this.$emit("update-experiences", this.experiences);
                    this.clearErrors();
                    this.experienceFormOpen = false;
                    this.editingExperienceId = null;
                    this.form = this.getInitialFormState();
                    this.job_descriptions = [""];
                })
                .catch((error) => {
                    this.handleError(error);
                })
                .finally(() => {
                    this.isSubmitting = false;
                });
        },
        deleteExperience(id) {
            axios
                .delete(`/api/experience/${id}`)
                .then(() => {
                    let newExperiences = this.experiences.filter(
                        (experience) => experience.id !== id
                    );
                    this.$emit("update-experiences", newExperiences);
                })
                .catch((error) => {
                    this.handleError(error);
                });
        },
        toggleExperienceForm() {
            this.experienceFormOpen = !this.experienceFormOpen;
            if (this.experienceFormOpen) {
                this.form = this.getInitialFormState();
            }
        },
        addDescription() {
            if (this.job_descriptions[0] !== '') {
                this.job_descriptions.unshift('');
            }
            this.$nextTick(() => {
                this.$refs.description[0].focus();
            });
        },
        removeDescription(index) {
            this.job_descriptions.splice(index, 1);
        },
        editExperience(id) {
            const experience = this.experiences.find(
                (experience) => experience.id === id
            );

            if (experience) {
                this.editingExperienceId = experience.id;
                this.form = {
                    id: experience.id,
                    resume_id: this.id,
                    job_title: experience.job_title,
                    company_name: experience.company_name,
                    start_date: experience.start_date,
                    end_date: experience.end_date,
                    is_current_job: experience.is_current_job,
                    is_remote: experience.is_remote,
                    job_description: experience.job_description,
                };
                this.job_descriptions = experience.job_description;
                this.experienceFormOpen = true;

                this.$nextTick(() => {
                    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                });
            }
        },
    },
    watch: {
        experiences: {
            handler() {
                this.$emit("update:can-next-step", this.experiences.length > 0);
            },
            deep: true,
        },
    },
    computed: {
        canNextStep() {
            return this.experiences.length > 0;
        },
        sortedExperiences() {
            return this.experiences.sort((a, b) => {
                if (a.is_current_job === b.is_current_job) {
                    if (a.end_date === b.end_date) {
                        return new Date(b.start_date) - new Date(a.start_date);
                    }
                    return new Date(b.end_date) - new Date(a.end_date);
                }
                return a.is_current_job ? -1 : 1;
            });
        },
    },
};
</script>
