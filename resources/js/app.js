import { createApp, defineAsyncComponent } from 'vue';
import '../css/app.css';
import axios from 'axios';

// Define an async component

const NavBar = defineAsyncComponent(() => import('./Layouts/Nav/nav-bar.vue'));
const SideBar = defineAsyncComponent(() => import('./Layouts/Nav/side-bar.vue'));
const Home = defineAsyncComponent(() => import('./Layouts/home.vue'));

const ChapterOne = defineAsyncComponent(() => import('./Chapters/chapter-one-page.vue'));
const PuzzleOneOne = defineAsyncComponent(() => import('./Chapters/ChapterOne/puzzle-one-page.vue'));
const PuzzleOneTwo = defineAsyncComponent(() => import('./Chapters/ChapterOne/puzzle-two-page.vue'));
const PuzzleOneThree = defineAsyncComponent(() => import('./Chapters/ChapterOne/puzzle-three-page.vue'));
const PuzzleOneFour = defineAsyncComponent(() => import('./Chapters/ChapterOne/puzzle-four-page.vue'));
const PuzzleOneFive = defineAsyncComponent(() => import('./Chapters/ChapterOne/puzzle-five-page.vue'));

const ChapterTwo = defineAsyncComponent(() => import('./Chapters/chapter-two-page.vue'));
const PuzzleTwoOne = defineAsyncComponent(() => import('./Chapters/ChapterTwo/puzzle-one-page.vue'));
const PuzzleTwoTwo = defineAsyncComponent(() => import('./Chapters/ChapterTwo/puzzle-two-page.vue'));
const PuzzleTwoThree = defineAsyncComponent(() => import('./Chapters/ChapterTwo/puzzle-three-page.vue'));
const PuzzleTwoFour = defineAsyncComponent(() => import('./Chapters/ChapterTwo/puzzle-four-page.vue'));
const PuzzleTwoFive = defineAsyncComponent(() => import('./Chapters/ChapterTwo/puzzle-five-page.vue'));


const Login = defineAsyncComponent(() => import('./Auth/login.vue'));
const Profile = defineAsyncComponent(() => import('./Auth/profile.vue'));
const Purchase = defineAsyncComponent(() => import('./Auth/purchase.vue'));
const ResetPassword = defineAsyncComponent(() => import('./Auth/reset-password.vue'));
const EmailVerify = defineAsyncComponent(() => import('./Auth/email-verify.vue'));




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
app.component('home', Home);

app.component('chapter-1', ChapterOne);
app.component('puzzle-1-1', PuzzleOneOne);
app.component('puzzle-1-2', PuzzleOneTwo);
app.component('puzzle-1-3', PuzzleOneThree);
app.component('puzzle-1-4', PuzzleOneFour);
app.component('puzzle-1-5', PuzzleOneFive);

app.component('chapter-2', ChapterTwo);
app.component('puzzle-2-1', PuzzleTwoOne);
app.component('puzzle-2-2', PuzzleTwoTwo);
app.component('puzzle-2-3', PuzzleTwoThree);
app.component('puzzle-2-4', PuzzleTwoFour);
app.component('puzzle-2-5', PuzzleTwoFive);

app.component('login', Login);
app.component('profile', Profile);
app.component('purchase', Purchase);
app.component('reset-password', ResetPassword);
app.component('email-verify', EmailVerify);



app.mount('#app');