<template>
    <div class="mb-3">
        <label :for="id">{{ label }}:</label>
        <input
            type="text"
            class="form-control"
            autocomplete="on"
            :class="{ 'is-invalid': errors[id] }"
            v-model="form[id]"
            :id="id"
        />
        <div class="invalid-feedback" v-if="errors[id]">
            {{ errors[id][0] }}
        </div>
    </div>
</template>

<script>
export default {
    props: ["label", "id", "form", "errors"],
};
</script>
