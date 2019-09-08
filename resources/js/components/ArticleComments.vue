<template>
    <div class="comments" id="article-comments" ref="comments">

        <h2 class="comments__title">Comments</h2>

        <article-comment-create :avatar="avatar" v-if="authed" :route="route"
                                @commented="newComment"></article-comment-create>

        <div v-else class="box box--footerless box--headerless">
            <main class="box__body text--center">
                You must <a :href="'/sign-in?redirect_to=' + location" class="link">sign in</a> to post a
                comment
            </main>
        </div>

        <div class="box box--headerless box--footerless" v-if="loading && scrolledToComments">
            <main class="box__body text--center">
                Loading Comments
            </main>
        </div>

        <article-comment v-show="! loading && comments.length > 0" v-for="comment in comments" :key="comment.id"
                         :comment="comment" :avatar="avatar" :authed="authed"
                         :route="route" :loading="loading"
                         :scroll-to="scrollTo"
                         :author="comment.author.data" :replies="comment.replies.data"></article-comment>

        <div class="box box--headerless box--footerless" v-if="! loading && comments.length < 1">
            <main class="box__body text--center">
                No Comments
            </main>
        </div>
    </div>
</template>

<script>
    import ArticleCommentCreate from './ArticleCommentCreate';

    export default {
        name: "article-comments",
        components: {ArticleCommentCreate},
        props: {
            route: {
                type: String,
                required: true,
            },
            avatar: {
                type: String,
                required: true,
            },
            authed: {
                type: Boolean,
                required: true,
                default: false,
            },
        },

        data: () => {
            return {
                location: window.location,
                comments: [],
                scrolledToComments: true,
                loading: true,
                scrollTo: null,
            };
        },

        created() {
            /*window.addEventListener('scroll', () => {
             this.scrolledToComments = this.hasScrolledEnough();
             });*/

            window.addEventListener('hashchange', () => {
                this.loadFocusedComment();
            });

            this.loadFocusedComment();
            this.loadComments();
        },

        watch: {
            /*scrolledToComments(scrolledToComments) {
             if (scrolledToComments) {
             this.loadComments();
             }
             },*/
        },

        methods: {

            hasScrolledEnough() {
                const scrollY      = window.scrollY;
                const visible      = document.documentElement.clientHeight;
                const bottomOfPage = visible + scrollY >= this.$refs.comments.offsetTop;
                return bottomOfPage || this.$refs.comments.offsetTop < visible;
            },

            loadComments() {
                window.axios
                      .get(this.route + '/comments')
                      .then(response => {
                          if (response.data.data) {
                              this.comments = response.data.data;
                              setTimeout(() => {
                                  this.loading = false;
                              }, 1000);
                          }
                      });
            },

            newComment(comment) {
                this.comments        = this.comments.concat([comment]);
                window.location.hash = '#comment-' + comment.id;
            },

            loadFocusedComment() {
                if (window.location.hash && window.location.hash.split("#")[1].startsWith('comment-')) {
                    this.scrollTo = Number(window.location.hash.substr(9));
                }
            },

        },
    };
</script>

<style scoped>

</style>
