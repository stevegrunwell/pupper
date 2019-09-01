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
                window.axios.get(this.route)
                    .then(response => {
                        this.isLoading = false;
                        this.posts = response.data.data;
                    })
                    .catch(err => {
                        window.console.error(err);
                    });
            }
        }
    };
</script>
