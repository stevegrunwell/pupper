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
        test('is a Vue instance', () => {
            const wrapper = createComponent();

            expect(wrapper.isVueInstance()).toBeTruthy()
        });
    });
});
