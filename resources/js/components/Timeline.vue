<template>
    <div
        class="card"
        :class="isLoading ? 'loading' : ''"
        v-show="isLoading || posts.length"
    >
        <header
            v-show="newPosts.length"
            class="card-header text-center p-0"
        >
            <button
                class="load-newer btn btn-light btn-block py-3"
                @click="loadNewer"
            >
                Load latest Barks
            </button>
        </header>
        <Post
            v-for="post in posts"
            :key="post.id"
            v-bind="post"
        />

        <footer
            v-if="posts.length"
            class="card-footer text-center p-0"
        >
            <button
                v-if="nextPage"
                class="load-more btn btn-light btn-block py-3"
                @click="fetch"
            >
                Load more Barks
            </button>
            <p
                v-else
                class="text-muted py-3"
            >
                Nothing more to show!
            </p>
        </footer>
    </div>
</template>

<script>
    import Post from './Post';

    export default {
        components: {
            Post,
        },

        props: {
            route: {
                type: String,
                required: true,
            },
        },

        data() {
            return {
                isLoading: true,
                nextPage: this.route,
                newPosts: [],
                posts: [],
            }
        },

        computed: {
            newPostIds() {
                return this.newPosts.map(post => post.id);
            },

            postIds() {
                return this.posts.map(post => post.id);
            },
        },

        mounted() {
            this.fetch();

            // Poll for new posts.
            setInterval(this.fetchNewer, 30000);
        },

        methods: {
            /**
             * Fetch posts from the API.
             *
             * Yes, we just made "fetch" happen.
             */
            fetch() {
                if (! this.nextPage) {
                    return;
                }

                window.axios.get(this.nextPage)
                    .then(response => {
                        response.data.data.forEach((post) => {
                            this.posts.push(post);
                        });

                        this.isLoading = false;
                        this.nextPage  = response.data.links.next;
                    })
                    .catch(err => {
                        window.console.error(err);
                    });
            },

            /**
             * Fetch any posts that have been created since we loaded.
             */
            fetchNewer() {
                window.axios.get(this.route)
                    .then(response => {
                        // Flip the order of the posts:
                        const posts = response.data.data.reverse();

                        posts.forEach((post) => {
                            if (! this.postIds.includes(post.id) && ! this.newPostIds.includes(post.id)) {
                               this.newPosts.unshift(post);
                            }
                        });
                    })
                    .catch(err => {
                        window.console.error(err);
                    });
            },

            /**
             * Merge the queue of newer posts into this.posts.
             */
            loadNewer() {
                this.$set(this, 'posts', this.newPosts.concat(this.posts));
                this.$set(this, 'newPosts', []);
            }
        }
    };
</script>
