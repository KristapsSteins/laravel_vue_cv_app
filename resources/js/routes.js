import Home from './components/Home.vue';
import ResumeView from './pages/resume/ResumeView.vue';
import ResumeForm from './pages/resume/ResumeForm.vue';

const Routes = [
    {
        path: '/',
        redirect: '/home'
    },
    {
        path: '/home',
        name: 'home',
        component: Home
    },
    {
        path: '/resume',
        name: 'resume',
        component: ResumeView
    },
    {
        path: '/resume/create',
        name: 'resume_create',
        component: ResumeForm,
    },
    {
        path: '/resume/create/:id',
        name: 'resume_create_with_id',
        component: ResumeForm,
        props: true
    },
];

export default Routes;