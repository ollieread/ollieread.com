<template>
    <div class="comments" id="article-comments" ref="comments">

        <h2 class="comments__title">Comments</h2>

        <article-comment-create :avatar="avatar" v-if="authed" :route="route"
                                @commented="newComment"></article-comment-create>

        <div v-else class="box box--footerless box--headerless">
            <main class="box__body text--center">
                You must <a href="/sign-in" class="link">sign in</a> to post a comment
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
                comments: [],
                scrolledToComments: false,
                loading: true,
            };
        },

        created() {
            window.addEventListener('scroll', () => {
                this.scrolledToComments = this.hasScrolledEnough();
            });
        },

        watch: {
            scrolledToComments(scrolledToComments) {
                if (scrolledToComments) {
                    this.loadComments();
                }
            },
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
                this.comments = this.comments.concat([comment]);
            },

        },
    };
</script>

<style scoped>

</style>