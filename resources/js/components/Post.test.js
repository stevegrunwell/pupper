import { shallowMount } from '@vue/test-utils';
import factory from '../factories';
import Post from './Post';

function createComponent(props) {
    return shallowMount(Post, {
        propsData: factory('Post', props),
    });
}

describe('Post', () => {
    describe('Construction', () => {
        it('is a Vue instance', () => {
            const wrapper = createComponent();

            expect(wrapper.isVueInstance()).toBeTruthy()
        });
    });

    describe('Display', () => {
        it('includes the display name of the author', () => {
            const wrapper = createComponent({
                user: {
                    displayName: 'Test McTest',
                },
            });

            expect(wrapper.text()).toContain('Test McTest');
        });

        it('includes the username of the author', () => {
            const wrapper = createComponent({
                user: {
                    username: 'testmctest',
                },
            });

            expect(wrapper.text()).toContain('testmctest');
        });
    })
});
