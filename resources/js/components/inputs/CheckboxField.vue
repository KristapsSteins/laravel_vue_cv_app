<template>
    <div class="form-check">
        <input
            type="checkbox"
            class="form-check-input"
            :id="id"
            v-model="form[id]"/>
        <label class="form-check-label" :for="id">{{ label }}</label>
    </div>
</template>

<script>
export default {
    props: ["label", "id", "form"],
};
</script>
