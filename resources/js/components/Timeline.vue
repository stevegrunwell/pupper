<template>
    <div
        class="card"
        :class="isLoading ? 'loading' : ''"
    >
        <Post
            v-for="post in posts"
            :key="post.id"
            v-bind="post"
        />

        <footer
            v-if="posts.length"
            class="card-footer text-center"
        >
            <button
                v-if="nextPage"
                class="load-more btn btn-default"
                @click="fetch"
            >
                Load more Barks
            </button>
            <p
                v-else
                class="text-muted my-2"
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
                posts: [],
            }
        },

        mounted() {
            this.fetch();
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
        }
    };
</script>
