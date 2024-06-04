<template>
    <button
        type="button"
        class="btn w-100 text-primary fw-bold mb-3"
        :click.stop="emitClick"
        v-if="showButton"
        style="border: 2px dashed #0000ff">
        <i class="fa-solid fa-plus"></i>
        Add More {{ item }}
    </button>
</template>

<script>
export default {
    props: ["item", "showButton"],
    methods: {
        emitClick() {
            this.$emit("click");
        },
    },
};
</script>
