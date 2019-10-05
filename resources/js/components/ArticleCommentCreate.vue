<template>
    <article class="comment">
        <div class="comment__author">
            <div class="avatar">
                <img :src="avatar" alt=""
                     class="avatar__image">
            </div>
        </div>

        <div class="comment__message">
            <main class="comment__message-body comment__message-body--headerless comment__message-body--expanded"
                  ref="comment">
                <div class="notice" :class="{'notice--error':!succeeded, 'notice--success':succeeded}" v-if="message">
                    {{ message }}
                </div>
                <textarea rows="10" class="comment__message-input input__field" v-model="comment"
                          v-show="! succeeded" ref="response" @input="adjustSize"></textarea>
            </main>

            <footer class="comment__message-footer">
                <button class="button button--small" :class="{'button--disabled':!canComment}" :disabled="! canComment"
                        @click.prevent="postComment">
                    <i class="button__icon fa-comment"></i> Post
                </button>
            </footer>
        </div>
    </article>
</template>

<script>
    export default {
        name: "article-comment-create",

        props: {
            route: {
                type: String,
                required: true,
            },
            avatar: {
                type: String,
                required: true,
            },
            parent: {
                type: Object,
                required: false,
            },
            responding: {
                type: Boolean,
            }
        },

        data: () => {
            return {
                comment: '',
                message: '',
                succeeded: false,
            };
        },

        computed: {
            canComment() {
                return this.comment && this.comment.length > 10;
            },
        },

        watch: {
            responding(value) {
                if (value) {
                    this.$refs.response.focus();
                    let position = this.$refs.response.getBoundingClientRect();
                    let bodyPosition = document.body.getBoundingClientRect();
                    window.scrollTo({
                        behavior: 'smooth',
                        top: position.top - bodyPosition.top,
                    });
                }
            }
        },

        methods: {
            async postComment() {
                if (this.canComment) {
                    await window
                        .axios
                        .post(this.route + '/comment', {
                            comment: this.comment,
                            parent: this.parent ? this.parent.id : null,
                        })
                        .then(async response => {
                            this.succeeded = true;

                            if (response.status === 200) {
                                this.message = 'Comment successfully posted';
                                this.comment = '';
                                this.$emit('commented', response.data.data);
                                setTimeout(() => {
                                    this.message = '';
                                    this.succeeded = false;
                                    this.$refs.response.style = {};
                                }, 5000);
                            }
                        })
                        .catch(async response => {
                            this.succeeded = false;

                            if (response.status === 422) {
                                if (response.data.errors.comment[0]) {
                                    this.message = response.data.errors.comment[0];
                                } else {
                                    this.message = response.data.message;
                                }
                            } else if (response.data.message) {
                                this.message = response.data.message;
                            } else {
                                this.message = 'Unexpected error';
                            }
                        });
                }
            },

            adjustSize() {
                let scrollHeight = this.$refs.response.scrollHeight;

                if (scrollHeight > 226) {
                    this.$refs.response.style.height = scrollHeight + 'px';
                }
            }
        },
    };
</script>

<style scoped>

</style>
