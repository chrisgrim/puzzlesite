import { createApp, defineAsyncComponent } from 'vue';
import '../css/app.css';
import axios from 'axios';

// Define an async component

const NavBar = defineAsyncComponent(() => import('./Layouts/Nav/nav-bar.vue'));

const ChapterOne = defineAsyncComponent(() => import('./Chapters/ChapterOne/chapter-one-page.vue'));
const PuzzleOne = defineAsyncComponent(() => import('./Chapters/ChapterOne/PuzzleOne/puzzle-one-page.vue'));
const PuzzleTwo = defineAsyncComponent(() => import('./Chapters/ChapterOne/PuzzleTwo/puzzle-two-page.vue'));


const Login = defineAsyncComponent(() => import('./Auth/login.vue'));




const app = createApp({});

// Setup axios
window.axios = axios;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

app.component('nav-bar', NavBar);

app.component('chapter-one', ChapterOne);
app.component('puzzle-one', PuzzleOne);
app.component('puzzle-two', PuzzleTwo);

app.component('login', Login);



app.mount('#app');