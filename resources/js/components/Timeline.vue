<template>
    <div class="card">
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
                        this.posts = response.data.data;
                    })
                    .catch(err => {
                        window.console.error(err);
                    });
            }
        }
    };
</script>
