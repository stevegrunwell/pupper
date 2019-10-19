/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

const app = new Vue({
    el: '#app',
    components: {
        CreatePostModal: require('./components/CreatePostModal.vue').default,
        FollowButton: require('./components/FollowButton.vue').default,
        Post: require('./components/Post.vue').default,
        Timeline: require('./components/Timeline.vue').default,
    },
});

/**
 * When opening the #createPostModal, read data attributes and pass them to the underlying
 * CreatePostModal Vue component.
 */
window.jQuery(document).on('show.bs.modal', '#createPostModal', function (e) {
    if (! e.relatedTarget) {
        return;
    }

    Vue.set(app.$refs.createPostModal, 'parentPost', e.relatedTarget.dataset.parentPost || '');
    Vue.set(app.$refs.createPostModal, 'prefill', e.relatedTarget.dataset.prefill || '');
});

window.jQuery(document).on('hidden.bs.modal', '#createPostModal', function (e) {
    Vue.set(app.$refs.createPostModal, 'parentPost', '');
    Vue.set(app.$refs.createPostModal, 'prefill', '');
});
