<template>
    <div class="mb-3">
        <div class="row">
            <div class="col" v-for="input in inputs" :key="input.id">
                <label :for="input.id">{{ input.label }}:</label>
                <input
                    type="text"
                    class="form-control"
                    autocomplete="off"
                    :class="{ 'is-invalid': errors[input.id] }"
                    v-model="form[input.id]"
                    :id="input.id"
                    required
                />
                <div class="invalid-feedback" v-if="errors[input.id]">
                    {{ errors[input.id][0] }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["inputs", "form", "errors"],
};
</script>
