import { shallowMount } from '@vue/test-utils';
import factory from '../factories';
import Modal from './CreatePostModal';
import Post from './Post';

function createComponent(props) {
    return shallowMount(Modal, {
        propsData: {
            route: '/post',
        }
    });
}

describe('Post', () => {
    describe('Construction', () => {
        it('is a Vue instance', () => {
            const wrapper = createComponent();

            expect(wrapper.isVueInstance()).toBeTruthy()
        });
    });

    describe('Behavior', () => {
        it('includes any prefill value', () => {
            const wrapper = createComponent();
            wrapper.setData({
                prefill: '@testmctest',
            });

            expect(wrapper.text()).toContain('@testmctest');
        });

        it('includes a parent post ID when available', () => {
            const wrapper = createComponent();
            const parent  = factory('Post');
            wrapper.setData({
                parentPost: parent.id,
            });

            expect(wrapper.find('[name="parent_id"]').element.value).toBe(parent.id);
        });
    });
});
