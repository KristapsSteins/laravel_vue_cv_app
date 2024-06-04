export default {
    data() {
        return {
            errors: {},
        };
    },
    methods: {
        handleError(error) {
            if (
                error.response &&
                error.response.data &&
                error.response.data.errors
            ) {
                this.errors = error.response.data.errors;
            } else {
                console.log(error);
            }
        },
        clearErrors() {
            this.errors = {};
        },
    },
};
