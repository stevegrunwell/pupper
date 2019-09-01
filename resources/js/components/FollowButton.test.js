import { shallowMount } from '@vue/test-utils';
import axios from 'axios';
import FollowButton from './FollowButton';

jest.mock('axios');
global.axios = axios;

function createComponent(props) {
    return shallowMount(FollowButton, {
        propsData: {
            username: 'someuser',
            following: false,
            ...props,
        }
    });
}

describe('FollowButton', () => {
    describe('Construction', () => {
        it('is a Vue instance', () => {
            const wrapper = createComponent();

            expect(wrapper.isVueInstance()).toBeTruthy()
        });
    });

    describe('Display', () => {
        it('has .btn-primary when the user is not followed', () => {
            const wrapper = createComponent({
                following: false,
            });

            expect(wrapper.classes('btn-primary')).toBe(true);
            expect(wrapper.classes('btn-secondary')).toBe(false);
        });

        it('has .btn-secondary when the user is already followed', () => {
            const wrapper = createComponent({
                following: true,
            });

            expect(wrapper.classes('btn-primary')).toBe(false);
            expect(wrapper.classes('btn-secondary')).toBe(true);
        });
    });

    describe('Behavior', () => {
        it('should follow users upon click', () => {
            global.axios.post.mockResolvedValue(true);

            const wrapper = createComponent({
                username: 'someuser',
                following: false,
            });

            wrapper.trigger('click');

            expect(axios.post).toHaveBeenCalledWith('/api/someuser/follow');
        });

        it('should unfollow users upon click', () => {
            global.axios.delete.mockResolvedValue(true);

            const wrapper = createComponent({
                username: 'someuser',
                following: true,
            });

            wrapper.trigger('click');

            expect(axios.delete).toHaveBeenCalledWith('/api/someuser/follow');
        });
    });
});
