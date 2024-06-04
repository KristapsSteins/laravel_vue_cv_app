<template>
    <div class="card shadow bg-body-tertiary rounded mb-3">
        <div class="card-body">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: ["title"],
};
</script>
