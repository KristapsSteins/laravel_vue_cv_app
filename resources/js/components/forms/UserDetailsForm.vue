<template>
    <form @submit.prevent="submitForm">
        <InputGroup :inputs="inputsGroup" :form="form" :errors="errors" />
        <InputField label="Email" id="email" :form="form" :errors="errors" />
        <InputField label="Phone" id="phone" :form="form" :errors="errors" />
        <InputField
            label="Address"
            id="address"
            :form="form"
            :errors="errors"
        />
        <InputField label="City/Town" id="town" :form="form" :errors="errors" />
        <TextareaField
            label="Summary"
            id="summary"
            :form="form"
            :errors="errors"
        />
        <button
            class="btn btn-primary my-3 w-100"
            type="submit"
            :disabled="isSubmitting">
            <i class="fa-solid fa-floppy-disk" style="padding-right: 5px"></i
            >Save
        </button>
    </form>
</template>

<script>
import axios from "axios";
import errorHandlingMixin from "../../errorHandlingMixin";
import InputField from "../inputs/InputField.vue";
import InputGroup from "../inputs/InputGroup.vue";
import TextareaField from "../inputs/TextareaField.vue";
import Card from "../shared/Card.vue";

export default {
    components: {
        InputField,
        InputGroup,
        TextareaField,
        Card,
    },
    props: {
        id: {
            type: [String, Number],
            default: null,
        },
        userDetails: {
            type: Object,
            default: () => {},
        },
    },
    mixins: [errorHandlingMixin],
    data() {
        return {
            form: {
                resume_id: this.id,
                ...this.userDetails,
            },
            inputsGroup: [
                { label: "First name(s)", id: "given_name" },
                { label: "Last name(s)", id: "family_name" },
            ],
            isSubmitting: false,
        };
    },
    methods: {
        submitForm() {
            this.isSubmitting = true;
            axios
                .post("/api/user-details/create", this.form)
                .then((response) => {
                    this.$emit("update-user-details", this.form);
                    this.$emit("valid");
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
