<template>
    <article>
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
