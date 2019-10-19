<template>
    <div
        class="modal fade"
        id="createPostModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="createPostModalLabel"
    >
        <div
            class="modal-dialog"
            role="document"
            @click="hide"
        >
            <div
                class="modal-content"
                @click.stop
            >
                <header class="modal-header">
                    <h2 class="modal-title h5" id="exampleModalLabel">Compose new Bark</h2>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                        @click="hide"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </header>
                <div class="modal-body">
                    <form
                        class="card-body"
                        method="post"
                        :action="route"
                        @submit="submit"
                    >
                        <div class="form-group">
                            <label for="post-content">What's on your mind?</label>
                            <textarea name="content" id="post-content" class="form-control" rows="4">{{ prefill }}</textarea>
                        </div>
                        <input
                            name="parent_id"
                            type="hidden"
                            :value="parentPost"
                        />
                        <button type="submit" class="btn btn-primary float-right">Bark</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            route: {
                type: String,
                required: true,
            },
        },

        data() {
            return {
                parentPost: '',
                prefill: '',
            };
        },

        methods: {
            submit(e) {
                const self = this;

                e.preventDefault();

                window.axios.post(this.route, {
                    content: document.getElementById('post-content').value,
                    parentPost: this.parentPost,
                })
                    .then(response => {
                        if (201 === response.status) {
                            this.hide();
                        }
                    })
                    .catch(err => {
                        window.console.error(err);
                    });
            },

            hide() {
                window.$(document.getElementById('createPostModal')).modal('toggle');
            },
        },
    };
</script>
