<template>
    <button
        class="btn"
        :class="classes"
        @click="toggle"
    >
        {{ label }}
    </button>
</template>

<script>
    export default {
        props: {
            username: {
                type: String,
                required: true,
            },
            following: {
                type: Boolean,
                required: true,
            }
        },

        data() {
            return {
                isFollowing: this.following,
            };
        },

        computed: {
            classes() {
                return this.isFollowing ? 'btn-secondary' : 'btn-primary';
            },

            label() {
                return this.isFollowing ? 'Unfollow' : 'Follow';
            },
        },

        methods: {
            toggle() {
                const desiredState = ! this.isFollowing;
                const method = desiredState ? 'post' : 'delete';

                window.axios[method](`/api/${this.username}/follow`)
                    .then(() => {
                        this.isFollowing = desiredState;
                    });
            },
        },
    };
</script>
