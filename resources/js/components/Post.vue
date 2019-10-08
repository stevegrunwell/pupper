<template>
    <article class="card-body media border-bottom border-default">
        <a
            :href="user.url"
        >
            <img
                :src="user.avatar.thumbnail"
                alt=""
                class="avatar mr-3"
                width="60"
                height="60"
            />
        </a>
        <div class="media-body">
            <header class="post-meta-header mb-2">
                <p class="mb-0">
                    <span class="h6 pr-2">{{ user.displayName }}</span>
                    <a
                        :href="user.url"
                        class="text-muted"
                    >
                        @{{ user.username }}
                    </a>
                    <time
                        class="text-muted pl-2"
                        :datetime="fullCreatedAt"
                        :title="friendlyCreatedAt"
                    >
                        {{ timeAgo }}
                    </time>
                </p>
            </header>

            {{ content }}
        </div>
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
            id: {
                type: String,
                required: true,
            },
            user: {
                type: Object,
                required: true,
            },
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
                return timeAgo.format(this.date, 'twitter') || 'just now';
            },
        }
    };
</script>
