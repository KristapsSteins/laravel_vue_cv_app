<template>
    <div class="row skills" style="min-height: 550px">
        <div
            class="col-sm-6"
            style="
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border: 1px solid #ddd;
                padding: 20px;
                margin-bottom: 20px;
            ">
            <div class="mb-3">
                <label>Add Custom Skill</label>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" placeholder="Skill" v-model="customSkill" maxlength="30">
                    <button type="button" class="btn btn-primary btn-sm" style="min-width: 40px;" @click="addCustomSkill">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <small class="text-muted">Max 30 characters</small>
            </div>
            <h3 class="mb-3">Available Skills</h3>
            <div
                class="shadow-sm bg-white border rounded mx-1 mb-2 py-1 px-1 d-flex gap-2"
                @click="addSkill(skill.skill_name)"
                style="cursor: pointer;"
                v-for="(skill, index) in availableSkills"
                :key="index">
                <button type="button" class="btn btn-sm btn-primary skill-item" style="max-height: 30px;">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <h5 class="fw-bold mb-0 align-self-center skill-item">
                    {{ skill.skill_name }}
                </h5>
            </div>
        </div>
        <div
            class="col-sm-6"
            style="
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border: 1px solid #ddd;
                padding: 20px;
                margin-bottom: 20px;
            ">
            <h3 class="mb-3">Selected Skills</h3>
            <div
                class="shadow-sm bg-white border rounded mx-1 mb-2 py-1 px-1 d-flex gap-2"
                @click="removeSkill(skill)"
                style="cursor: pointer"
                v-for="(skill, index) in skills"
                :key="index">
                <button type="button" class="btn btn-sm btn-danger" style="max-height: 30px;">
                    <i class="fa-solid fa-trash"></i>
                </button>
                <h5 class="fw-bold mb-0 align-self-center">{{ skill }}</h5>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import InputField from "../inputs/InputField.vue";

export default {
    data() {
        return {
            customSkill: '',
        };
    },
    components: {
        InputField,
    },
    props: {
        id: {
            type: [String, Number],
            default: null,
        },
        skills: {
            type: Array,
            default: () => [],
        },
        skillList: {
            type: Array,
            default: () => [],
        },
    },
    methods: {
        addSkill(skill) {
            axios
                .post("/api/resume/skill/create", {
                    resume_id: this.id,
                    skill_name: skill,
                })
                .then((response) => {
                    this.$emit("skillAdded", response.data.skill);
                })
                .catch((error) => {});
        },
        removeSkill(skill) {
            if (window.confirm("Are you sure you want to remove this skill?")) {
                axios
                    .delete(`/api/resume/skill/${this.id}`, {
                        data: {
                            resume_id: this.id,
                            skill_name: skill,
                        },
                    })
                    .then((response) => {
                        this.$emit("skillRemoved", skill);
                    })
                    .catch((error) => {});
            }
        },
        addCustomSkill() {
            if (this.customSkill) {
                this.addSkill(this.customSkill);
                this.customSkill = '';
            }
        },
    },
    computed: {
        availableSkills() {
            return this.skillList.filter(skill => !this.skills.includes(skill.skill_name));
        },
    },
};
</script>

<style scoped>
@media (max-width: 768px) {
    .skills h5 {
        font-size: 0.8rem;
    }
}

@media (min-width: 768px) and (max-width: 992px) {
    .skills h5 {
        font-size: 1rem;
    }
}
</style>
