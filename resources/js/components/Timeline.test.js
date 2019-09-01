import { mount } from '@vue/test-utils';
import axios from 'axios';
import factory from '../factories';
import flushPromises from 'flush-promises';
import Post from './Post';
import Timeline from './Timeline';

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
                },
            });

            const wrapper = createComponent();

            await flushPromises();

            expect(wrapper.classes('loading')).toBe(false);
        })
    });
});
