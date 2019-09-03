import { mount } from '@vue/test-utils';
import axios from 'axios';
import factory from '../factories';
import flushPromises from 'flush-promises';
import Post from './Post';
import Timeline from './Timeline';

jest.useFakeTimers();
jest.mock('axios');
global.axios = axios;

function createComponent(props) {
    return mount(Timeline, {
        propsData: {
            route: 'https://example.com/api/timeline',
            ...props,
        },
    });
}

describe('Timeline', () => {
    describe('Construction', () => {
        beforeEach(() => {
            global.axios.get.mockResolvedValue({
                data: {
                    data: [],
                    links: {
                        next: null,
                    }
                }
            });
        });

        it('is a Vue instance', () => {
            const wrapper = createComponent();

            expect(wrapper.isVueInstance()).toBeTruthy();
        });

        it('is begins with a .loading class ', () => {
            const wrapper = createComponent();

            expect(wrapper.classes('loading')).toBe(true)
        });

        it('creates Post components for each post', () => {
            const wrapper = createComponent();
            wrapper.setData({
                posts: factory('Post', 3),
            });

            expect(wrapper.findAll(Post).length).toBe(3);
        });
    });

    describe('Behavior', () => {
        it('calls the route for initial population', () => {
            global.axios.get.mockResolvedValue({
                data: {
                    data: factory('Post', 3),
                    links: {
                        next: null,
                    }
                },
            });

            const wrapper = createComponent({
                route: 'https://example.com/api/timeline',
            });

            expect(axios.get).toHaveBeenCalledWith('https://example.com/api/timeline');
        });

        it('removes the .loading class once posts are populated', async () => {
            global.axios.get.mockResolvedValue({
                data: {
                    data: factory('Post', 2),
                    links: {
                        next: null,
                    }
                },
            });

            const wrapper = createComponent();

            await flushPromises();

            expect(wrapper.classes('loading')).toBe(false);
        });

        it('can load the next page of posts', async () => {
            global.axios.get
                .mockResolvedValueOnce({
                    data: {
                        data: factory('Post', 2),
                        links: {
                            next: 'https://example.com/api/timeline?page=2',
                        },
                    },
                })
                .mockResolvedValueOnce({
                    data: {
                        data: factory('Post', 2),
                        links: {
                            next: null,
                        },
                    },
                });

            const wrapper = createComponent();

            await flushPromises();

            await wrapper.find('.load-more').trigger('click');

            expect(axios.get).toHaveBeenLastCalledWith('https://example.com/api/timeline?page=2');
            expect(wrapper.findAll(Post).length).toBe(4);
        });

        it('shows the next page button if there are more posts to show', async () => {
            global.axios.get
                .mockResolvedValueOnce({
                    data: {
                        data: factory('Post', 2),
                        links: {
                            next: 'https://example.com/api/timeline?page=2',
                        },
                    },
                });

            const wrapper = createComponent();

            await flushPromises();

            expect(wrapper.find('.load-more').exists()).toBe(true);
        });

        it('can load the next page of posts', async () => {
            global.axios.get
                .mockResolvedValueOnce({
                    data: {
                        data: factory('Post', 2),
                        links: {
                            next: 'https://example.com/api/timeline?page=2',
                        },
                    },
                })
                .mockResolvedValueOnce({
                    data: {
                        data: factory('Post', 2),
                        links: {
                            next: null,
                        },
                    },
                });

            const wrapper = createComponent();

            await flushPromises();
            await wrapper.find('.load-more').trigger('click');

            expect(axios.get).toHaveBeenLastCalledWith('https://example.com/api/timeline?page=2');
        });

        it('hides the next page button if there are no more posts to show', async () => {
            global.axios.get
                .mockResolvedValueOnce({
                    data: {
                        data: factory('Post', 2),
                        links: {
                            next: null,
                        },
                    },
                });

            const wrapper = createComponent();

            await flushPromises();

            expect(wrapper.find('.load-more').exists()).toBe(false);
        });

        it('polls for new posts', async () => {
            global.axios.get
                .mockResolvedValueOnce({
                    data: {
                        data: factory('Post', 3),
                        links: {
                            next: null,
                        },
                    },
                })
                .mockResolvedValueOnce({
                    data: {
                        data: factory('Post', 2),
                        links: {
                            next: null,
                        },
                    },
                });

            const wrapper = createComponent({
                route: 'https://example.com/api/timeline',
            });

            jest.advanceTimersByTime(30000);

            await flushPromises();

            expect(axios.get).toHaveBeenCalledWith('https://example.com/api/timeline');
            expect(wrapper.find('.load-newer').exists()).toBe(true);
            expect(wrapper.vm.newPosts.length).toBe(2);
        });

        it('loads the latest posts at the top of the list', async () => {
            global.axios.get
                .mockResolvedValueOnce({
                    data: {
                        data: factory('Post', 3),
                        links: {
                            next: null,
                        },
                    },
                });

            const wrapper = createComponent({
                route: 'https://example.com/api/timeline',
            });
            const posts = factory('Post', 2);

            await flushPromises();

            wrapper.setData({
                newPosts: posts,
            });
            wrapper.find('.load-newer').trigger('click');

            await flushPromises();

            const postObjects = wrapper.findAll(Post);

            expect(postObjects.length).toBe(5);
            expect(postObjects.at(0).props('id')).toBe(posts[0].id);
        });
    });
});
