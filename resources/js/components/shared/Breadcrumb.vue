<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" :class="{ active: step === 1 }">
                <a
                    href="#"
                    v-if="step > 1"
                    @click.prevent="$emit('updateStep', 1)"
                    >CV details</a
                ><span v-else>CV details </span>
            </li>
            <li class="breadcrumb-item" :class="{ active: step === 2 }">
                <a
                    href="#"
                    v-if="step > 2"
                    @click.prevent="$emit('updateStep', 2)"
                    >User Details</a
                ><span v-else>User Details</span>
            </li>
            <li class="breadcrumb-item" :class="{ active: step === 3 }">
                <a
                    href="#"
                    v-if="step > 3"
                    @click.prevent="$emit('updateStep', 3)"
                    >Education</a
                ><span v-else>Education</span>
            </li>
            <li class="breadcrumb-item" :class="{ active: step === 4 }">
                <a
                    href="#"
                    v-if="step > 4"
                    @click.prevent="$emit('updateStep', 4)"
                    >Work Experience</a
                ><span v-else>Work Experience</span>
            </li>
            <li class="breadcrumb-item" :class="{ active: step === 5 }">
                Skills
            </li>
        </ol>
    </nav>
</template>

<script>
export default {
    props: {
        step: {
            type: Number,
            required: true,
        },
    },
};
</script>
