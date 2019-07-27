import ArticleComment       from '../components/ArticleComment';
import ArticleComments      from '../components/ArticleComments';
import ArticleCommentCreate from '../components/ArticleCommentCreate';

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

window.Vue.component(ArticleComment.name, ArticleComment);
window.Vue.component(ArticleComments.name, ArticleComments);
window.Vue.component(ArticleCommentCreate.name, ArticleCommentCreate);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});