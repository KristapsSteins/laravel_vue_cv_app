<template>
    <div class="card shadow bg-body-tertiary rounded mb-3">
        <div class="card-body">
            <h5 class="card-title">Professional Experience</h5>
            <div id="professional_experience_list" v-if="educations.length > 0">
                <div
                    class="mb-3"
                    v-for="education in sortedEducations"
                    :key="education.id">
                    <div class="row shadow-sm bg-white border rounded mx-1 mb-2 py-1">
                        <div class="col d-flex flex-column gap-0">
                            <span class="fw-bold">{{ education.degree }}</span>
                            <span>{{ education.school }}</span>
                            <span
                                >{{ formatDate(education.school_start_date) }} -
                                {{
                                    education.currently_studying
                                        ? "now"
                                        : formatDate(education.school_end_date)
                                }}</span>
                        </div>
                        <div
                            class="col d-flex align-items-center justify-content-end">
                            <button
                                class="btn btn-danger"
                                @click="deleteEducation(education.id)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <hr />
            </div>
            <button
                type="button"
                class="btn w-100 text-primary fw-bold mb-3"
                @click="addMoreEducation"
                v-if="educations.length > 0 || !educationFormOpen"
                style="border: 2px dashed #0000ff">
                Add More Education
            </button>
            <form @submit.prevent="submitForm" v-if="educationFormOpen">
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="degree">What was your title:</label>
                            <input
                                type="text"
                                class="form-control"
                                autocomplete="off"
                                :class="{ 'is-invalid': errors.degree }"
                                v-model="form.degree"
                                id="degree"
                                required
                            />
                            <div class="invalid-feedback" v-if="errors.degree">
                                {{ errors.degree[0] }}
                            </div>
                        </div>
                        <div class="col">
                            <label for="school_location">City/Town:</label>
                            <input
                                type="text"
                                class="form-control"
                                autocomplete="off"
                                :class="{
                                    'is-invalid': errors.school_location,
                                }"
                                v-model="form.school_location"
                                id="school_location"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.school_location"
                            >
                                {{ errors.school_location[0] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="school">School:</label>
                    <input
                        type="text"
                        class="form-control"
                        autocomplete="off"
                        :class="{ 'is-invalid': errors.school }"
                        v-model="form.school"
                        id="school"
                        required
                    />
                    <div class="invalid-feedback" v-if="errors.school">
                        {{ errors.school[0] }}
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="school_start_date">Start Date:</label>
                            <VueDatePicker
                                id="school_start_date"
                                v-model="form.school_start_date"
                                format="dd.MM.yyyy"
                                :class="{
                                    'is-invalid': errors.school_start_date,
                                }"
                                :enable-time-picker="false"
                                required
                            ></VueDatePicker>
                            <div
                                class="invalid-feedback"
                                v-if="errors.school_start_date"
                            >
                                {{ errors.school_start_date[0] }}
                            </div>
                        </div>
                        <div class="col">
                            <label for="school_end_date">End Date:</label>
                            <VueDatePicker
                                id="school_end_date"
                                v-model="form.school_end_date"
                                format="dd.MM.yyyy"
                                :class="{
                                    'is-invalid': customErrors.school_end_date,
                                }"
                                :enable-time-picker="false"
                            ></VueDatePicker>
                            <div
                                class="invalid-feedback"
                                v-if="
                                    customErrors.school_end_date ||
                                    errors.school_end_date
                                "
                            >
                                {{
                                    customErrors.school_end_date ||
                                    errors.school_end_date[0]
                                }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="currently_studying"
                            v-model="form.currently_studying"
                        />
                        <label class="form-check-label" for="currently_studying"
                            >Currently studying</label
                        >
                    </div>
                </div>
                <button class="btn btn-primary my-3 w-100" type="submit">
                    <i
                        class="fa-solid fa-floppy-disk"
                        style="padding-right: 5px"
                    ></i
                    >Save
                </button>
            </form>
            <div class="d-flex justify-content-between">
                <button
                    type="button"
                    class="btn btn-outline-dark"
                    @click="$emit('backStep')"
                >
                    Back
                </button>
                <button
                    type="button"
                    class="btn btn-outline-primary"
                    @click="$emit('nextStep')"
                    v-if="educations.length > 0"
                >
                    Next step
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import errorHandlingMixin from "../../errorHandlingMixin";

export default {
    props: {
        id: {
            type: [String, Number],
            default: null,
        },
    },
    mixins: [errorHandlingMixin],
    data() {
        return {
            form: {
                resume_id: this.id,
                degree: "",
                school_location: "",
                school: "",
                school_start_date: "",
                school_end_date: "",
                currently_studying: false,
            },
            educations: [],
            customErrors: {
                school_end_date: null,
            },
            educationFormOpen: true,
        };
    },
    methods: {
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
            axios
                .post("/api/education/create", this.form)
                .then((response) => {
                    this.educations.push(response.data.education);
                    this.clearErrors();
                    this.educationFormOpen = false;
                })
                .catch((error) => {
                    this.handleError(error);
                });
        },
        deleteEducation(id) {
            axios
                .delete(`/api/education/${id}`)
                .then(() => {
                    this.educations = this.educations.filter(
                        (education) => education.id !== id
                    );
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        formatDate(dateString) {
            const options = {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
            };
            return new Date(dateString).toLocaleDateString("de-DE", options);
        },
        addMoreEducation() {
            this.clearForm();
            this.educationFormOpen = !this.educationFormOpen;
        },
        clearForm() {
            this.form = {
                resume_id: this.id,
                degree: "",
                school_location: "",
                school: "",
                school_start_date: "",
                school_end_date: "",
                currently_studying: false,
            };
        },
    },
    watch: {
        "form.currently_studying": function (newValue) {
            if (newValue) {
                this.form.school_end_date = "";
            }
        },
    },
    computed: {
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
