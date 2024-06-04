<template>
    <div class="mb-3">
        <div class="row">
            <div class="col" v-for="(input, index) in inputs" :key="input.id">
                <label :for="input.id">{{ input.label }}:</label>
                <VueDatePicker
                    :id="input.id"
                    v-model="form[input.id]"
                    format="dd.MM.yyyy"
                    :class="{
                        'is-invalid':
                            errors[input.id] || customErrors[input.id],
                    }"
                    :enable-time-picker="false"
                    :required="index === 0"
                ></VueDatePicker>
                <div
                    class="invalid-feedback"
                    v-if="errors[input.id] || customErrors[input.id]">
                    {{ customErrors[input.id] || errors[input.id][0] }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["inputs", "form", "errors", "customErrors"],
};
</script>
