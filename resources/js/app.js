import { createApp, defineAsyncComponent } from 'vue';
import '../css/app.css';
import axios from 'axios';

// Define an async component

const NavBar = defineAsyncComponent(() => import('./Layouts/Nav/nav-bar.vue'));
const SideBar = defineAsyncComponent(() => import('./Layouts/Nav/side-bar.vue'));
const Home = defineAsyncComponent(() => import('./Layouts/home.vue'));




const Login = defineAsyncComponent(() => import('./Auth/login.vue'));
const Profile = defineAsyncComponent(() => import('./Auth/profile.vue'));
const Purchase = defineAsyncComponent(() => import('./Auth/purchase.vue'));
const ResetPassword = defineAsyncComponent(() => import('./Auth/reset-password.vue'));
const EmailVerify = defineAsyncComponent(() => import('./Auth/email-verify.vue'));

const Admin = defineAsyncComponent(() => import('./Admin/index.vue'));




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

app.component('login', Login);
app.component('profile', Profile);
app.component('purchase', Purchase);
app.component('reset-password', ResetPassword);
app.component('email-verify', EmailVerify);

app.component('admin', Admin);

const puzzleComponentContext = import.meta.glob('./Chapters/**/*.vue');

for (const path in puzzleComponentContext) {
    const fileName = path.split('/').pop();
    const slugMatch = fileName.match(/puzzle-(.+)\.vue$/);
    if (slugMatch) {
        const slug = slugMatch[1];
        app.component(`puzzle-${slug}`, defineAsyncComponent(puzzleComponentContext[path]));
    }
}


app.mount('#app');