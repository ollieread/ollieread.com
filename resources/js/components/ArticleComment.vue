<template>
    <div>
        <article class="comment" :id="'comment-' + comment.id" ref="comment">
            <div class="comment__author">
                <div class="avatar">
                    <img :src="author.avatar" alt=""
                         class="avatar__image">
                </div>
            </div>

            <div class="comment__message" :class="{'comment__message--focused':isFocused()}">
                <main class="comment__message-body comment__message-body--headerless"
                      :class="{'comment__message-body--truncated':shouldTruncate && truncated}" ref="comment">
                    <div v-html="comment.comment" v-highlightjs></div>
                    <div class="comment__expand" v-if="shouldTruncate">
                        <a href="#" @click.prevent="toggleContent" class="button button--small">
                            <template v-if="truncated">Show More</template>
                            <template v-else>Show Less</template>
                        </a>
                    </div>
                </main>

                <footer class="comment__message-footer">
                    <strong>{{ author.username }}</strong>
                    <em class="comment__date">{{ comment.created_at }}</em>
                    <button class="button button--small" @click.prevent="toggleResponding" v-if="authed">
                        <i class="button__icon fa-comments"></i> Respond
                    </button>
                </footer>
            </div>
        </article>

        <div class="comment__thread" :id="'thread-' + comment.id">

            <article-comment-create :avatar="avatar" v-if="authed" v-show="responding"
                                    :responding="responding"
                                    :route="route"
                                    @commented="newComment"
                                    :parent="comment"></article-comment-create>

            <template v-if="startingReplies && startingReplies.length > 0">
                <article-comment v-for="response in startingReplies" :key="response.id" :comment="response"
                                 :avatar="avatar" :authed="authed"
                                 :route="route" :loading="loading"
                                 :scroll-to="scrollTo"
                                 :author="response.author.data" :replies="response.replies.data"></article-comment>

                <a href="#" class="comment__toggle" v-if="shouldHide && hidden" @click.prevent="showMoreReplies">
                    Show {{ restOfReplies.length }} response(s).
                </a>

                <article-comment v-for="response in restOfReplies" :key="response.id" :comment="response"
                                 v-if="! hidden"
                                 :avatar="avatar" :authed="authed"
                                 :route="route" :loading="loading"
                                 :scroll-to="scrollTo"
                                 :author="response.author.data" :replies="response.replies.data"></article-comment>
            </template>

        </div>
    </div>
</template>

<script>
    export default {
        name: "article-comment",

        props: {
            route: {
                type: String,
                required: true,
            },
            comment: {
                type: Object,
                required: true,
            },
            replies: {
                type: Array,
                required: true,
            },
            author: {
                type: Object,
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
            loading: {
                type: Boolean,
                required: true,
            },
            scrollTo: {
                type: Number,
                required: false,
                default: null,
            },
        },

        data: () => {
            return {
                startingReplies: [],
                restOfReplies: [],
                shouldHide: false,
                hidden: true,
                responding: false,
                shouldTruncate: false,
                truncated: true,
            };
        },

        mounted() {
            this.startingReplies = this.replies;
        },

        watch: {
            loading(value) {
                if (!value) {
                    this.checkIfShouldTruncate();
                    this.checkIfShouldScrollTo();
                }
            },
        },

        methods: {
            showMoreReplies() {
                this.hidden = false;
            },

            checkIfShouldTruncate() {
                if (this.isFocused()) {
                    this.shouldTruncate = false;
                } else {
                    this.shouldTruncate = this.$refs.comment.offsetHeight > 300 && this.$refs.comment.offsetHeight > 325;
                }
            },

            checkIfShouldScrollTo() {
                if (this.isFocused()) {
                    let position = this.$refs.comment.getBoundingClientRect();
                    let bodyPosition = document.body.getBoundingClientRect();
                    window.scrollTo({
                        behavior: 'smooth',
                        top: position.top - bodyPosition.top,
                    });
                }
            },

            toggleContent() {
                this.truncated = !this.truncated;
            },

            newComment(comment) {
                if (this.startingReplies.length < 2) {
                    this.startingReplies = this.startingReplies.concat([comment]);
                } else {
                    this.restOfReplies = this.restOfReplies.concat([comment]);
                }

                this.showMoreReplies();
                this.responding = false;
                window.location.hash = '#comment-' + comment.id;
            },

            isFocused() {
                return this.scrollTo === Number(this.comment.id);
            },

            toggleResponding() {
                this.responding = !this.responding;
            }
        }
    }
</script>

<style scoped>

</style>
