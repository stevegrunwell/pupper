<template>
    <article class="card-body border-bottom border-default">
        {{ content }}
    </article>
</template>

<script>
    export default {
        props: {
            content: {
                type: String,
                required: true,
            },
        },
    };
</script>
