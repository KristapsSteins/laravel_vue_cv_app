<template>
    <Card>
        <Breadcrumb :step="step" @updateStep="step = $event" />
        <ResumeNameForm
            v-if="step === 1"
            @valid="nextStep"
            @update:id="setId"
            :resume="resume"
            :id="id"
            @update-resume="updateData('resume', $event)"
        />
        <UserDetailsForm 
            v-if="step === 2" 
            @valid="nextStep" 
            :id="id"
            @update-user-details="updateData('userDetails', $event)"
            :userDetails="userDetails" />
        <EducationForm
            v-if="step === 3"
            :id="id"
            @update-educations="updateEducations"
            @update:can-next-step="canNextStep = $event"
            :educations="educations"
        />
        <WorkExperienceForm
            v-if="step === 4"
            :id="id"
            @update-experiences="updateExperiences"
            @update:can-next-step="canNextStep = $event"
            :experiences="experiences"
        />
        <SkillForm 
            v-if="step === 5" 
            :id="id"
            @skillAdded="handleSkillAdded"
            @skillRemoved="handleSkillRemoved"
            :skillList="skillList"
            :skills="skills"
            />
        <div class="d-flex justify-content-between">
            <button
                type="button"
                class="btn btn-outline-dark"
                v-if="step >= 2"
                @click="backStep">
                Back
            </button>
            <div class="d-flex gap-2">
                <button
                    type="button"
                    class="btn btn-outline-primary"
                    v-if="step >= 3"
                    @click="openPreview">
                    Preview
                </button>
                <ResumePreviewModal
                    :showModal="isDialogVisible"
                    @update:showModal="isDialogVisible = $event"
                    :previewResumeData="previewResumeData"
                />
                <button
                    type="button"
                    class="btn btn-primary"
                    v-if="step >= 3 && step < 5 && isNextStepAllowed"
                    @click="nextStep">
                    Next step
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    v-if="step === 5"
                    @click="finishResume">
                    Finish
                </button>
            </div>
        </div>
    </Card>
</template>

<script>
import axios from "axios";
import UserDetailsForm from "../../components/forms/UserDetailsForm.vue";
import EducationForm from "../../components/forms/EducationForm.vue";
import ResumeNameForm from "../../components/forms/ResumeNameForm.vue";
import Card from "../../components/shared/Card.vue";
import ResumePreviewModal from "../../components/shared/ResumePreviewModal.vue";
import WorkExperienceForm from "../../components/forms/WorkExperienceForm.vue";
import SkillForm from "../../components/forms/SkillForm.vue";
import Breadcrumb from "../../components/shared/Breadcrumb.vue";
import { useRouter, useRoute } from 'vue-router';

export default {
    props: ['id'],
    setup() {
        const router = useRouter();
        const route = useRoute();
        let paramsId = route.params.id;
        return { router, paramsId };
    },
    
    components: {
        UserDetailsForm,
        EducationForm,
        ResumeNameForm,
        Card,
        ResumePreviewModal,
        WorkExperienceForm,
        SkillForm,
        Breadcrumb,
    },
    data() {
        return {
            step: 1,
            id: null,
            isDialogVisible: false,
            canNextStep: false,
            showItems: false,
            resume: {},
            userDetails: {},
            educations: [],
            experiences: [],
            skills: [],
            previewResumeData: null,
            skillList: [
                { skill_name: "PHP" },
                { skill_name: "Laravel" },
                { skill_name: "Vue.js" },
                { skill_name: "React" },
                { skill_name: "Angular" },
                { skill_name: "Time Management"},
                { skill_name: "Communication"},
                { skill_name: "Problem Solving"},
                { skill_name: "Teamwork"},
                { skill_name: "Leadership"},
            ],
        };
    },
    methods: {
        nextStep() {
            this.step++;
            this.canNextStep = false;
        },
        backStep() {
            if (this.step > 1) {
                this.step--;
            }
            this.canNextStep = true;
        },
        setId(id) {
            this.id = id;
        },
        openPreview() {
            this.previewResumeData = {
                name: this.resume.name,
                user_details: this.userDetails,
                educations: this.educations,
                experiences: this.experiences,
                skills: this.skills,
            };
            this.isDialogVisible = true;
        },
        updateEducations(educations) {
            console.log(educations, 'asdasdasddsadasasdsasdassa12');
            this.educations = educations;
        },
        updateExperiences(experiences) {
            this.experiences = experiences;
        },
        updateData(property, value) {
            this[property] = value;
        },
        handleSkillAdded(skill) {
            this.skills.push(skill);
            this.skillList = this.skillList.filter(s => s.skill_name !== skill);
        },
        handleSkillRemoved(skill) {
            this.skills = this.skills.filter((s) => s !== skill);
            this.skillList.push({ skill_name: skill });
        },
        finishResume() {
            axios.
                post("/api/resume/finish", {
                    id: this.id,
                    completed: true,
                })
                .then(() => {
                    this.router.push('/resume');
                })
                .catch((error) => {
                    console.log(error);
                });
                
        },
    },
    mounted() {
        if (this.paramsId) {
            axios.get(`/api/resume/data/${this.paramsId}`)
                .then((response) => {
                    this.id = response.data.id;
                    this.resume.name = response.data.name;
                    this.userDetails = response.data.user_details;
                    this.resume_name = response.data.name;
                    this.educations = response.data.educations;
                    this.experiences = response.data.experiences;
                    this.skills = response.data.skills;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },
    computed: {
        isNextStepAllowed() {
            if (this.step === 3 && this.educations.length === 0) {
                return false;
            }
            if (this.step === 4 && this.experiences.length === 0) {
                return false;
            }
            return true;
        }
    }
};
</script>
