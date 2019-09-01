<template>
    <article class="card-body border-bottom border-default">
        <header class="post-meta-header">
            <p>
                <span class="h6">{{ user.displayName }}</span>
                <a
                    :href="user.url"
                    class="text-muted"
                >
                    @{{ user.username }}
                </a>
                <time
                    class="text-muted"
                    :datetime="fullCreatedAt"
                    :title="friendlyCreatedAt"
                >
                    {{ timeAgo }}
                </time>
            </p>
        </header>

        {{ content }}
    </article>
</template>

<script>
    import en from 'javascript-time-ago/locale/en';
    import TimeAgo from 'javascript-time-ago';

    TimeAgo.addLocale(en);
    const timeAgo = new TimeAgo('en-US');

    export default {
        props: {
            content: {
                type: String,
                required: true,
            },
            createdAt: {
                type: String|Date,
                required: true,
            },
            user: {
                type: Object,
                required: true,
            }
        },

        data() {
            return {
                date: typeof this.createdAt === Date ? this.createdAt : new Date(this.createdAt),
            };
        },

        computed: {
            fullCreatedAt() {
                return this.date.toISOString();
            },
            friendlyCreatedAt() {
                return this.date.toUTCString();
            },
            timeAgo() {
                return timeAgo.format(this.date, 'twitter');
            },
        }
    };
</script>
