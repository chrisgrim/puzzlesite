import { createApp, defineAsyncComponent } from 'vue';
import '../css/app.css';
import axios from 'axios';

// Define an async component

const NavBar = defineAsyncComponent(() => import('./Layouts/Nav/nav-bar.vue'));
const SideBar = defineAsyncComponent(() => import('./Layouts/Nav/side-bar.vue'));

const ChapterOne = defineAsyncComponent(() => import('./Chapters/ChapterOne/chapter-one-page.vue'));
const PuzzleOne = defineAsyncComponent(() => import('./Chapters/ChapterOne/PuzzleOne/puzzle-one-page.vue'));
const PuzzleTwo = defineAsyncComponent(() => import('./Chapters/ChapterOne/PuzzleTwo/puzzle-two-page.vue'));
const PuzzleThree = defineAsyncComponent(() => import('./Chapters/ChapterOne/PuzzleThree/puzzle-three-page.vue'));


const Login = defineAsyncComponent(() => import('./Auth/login.vue'));
const Purchase = defineAsyncComponent(() => import('./Auth/purchase.vue'));
const ResetPassword = defineAsyncComponent(() => import('./Auth/reset-password.vue'));




const app = createApp({
    data() {
        return {
            // Access the global user data
            user: window.Laravel.user
        };
    }
});

// Setup axios
window.axios = axios;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

app.component('nav-bar', NavBar);
app.component('side-bar', SideBar);

app.component('chapter-one', ChapterOne);
app.component('puzzle-one', PuzzleOne);
app.component('puzzle-two', PuzzleTwo);
app.component('puzzle-three', PuzzleThree);

app.component('login', Login);
app.component('purchase', Purchase);
app.component('vue-reset-password', ResetPassword);



app.mount('#app');