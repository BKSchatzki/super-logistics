import {beforeEach, describe, expect, it, vi} from 'vitest';
import PrimeVue from 'primevue/config';
import {mount} from '@vue/test-utils';
import ClientForm from './ClientForm.vue';

// Mock form assist composable
const mockSubmit = vi.fn(() => true);
vi.mock('@utils/composables/useFormAssist', () => ({
    useFormAssist: () => ({
        submitToAPI: mockSubmit
    })
}));

describe('ClientForm.vue', () => {
    let wrapper;
    const mockClose = vi.fn();

    const mountComponent = (props = {}) => {
        return mount(ClientForm, {
            props: {
                close: mockClose,
                ...props
            },
            global: {
                plugins: [[PrimeVue, {ripple: true}]],
                stubs: {
                    Button: {
                        template: '<button :data-test="$attrs[\'data-test\']" @click="$emit(\'click\')"><slot></slot></button>',
                        props: ['label', 'severity', 'type']
                    }
                }
            }
        });
    };

    beforeEach(() => {
        vi.clearAllMocks();
    });

    describe('Form Validation', () => {
        it('should not submit the form when validation fails', async () => {
            wrapper = mountComponent();
            await wrapper.find('[data-test="submit-button"]').trigger('click');
            await wrapper.vm.$nextTick();
            expect(mockSubmit).not.toHaveBeenCalled();
        });
    });

    describe('Cancel Button', () => {
        it('should call close function when cancel button is clicked', async () => {
            wrapper = mountComponent();
            const cancelButton = wrapper.find('[data-test="cancel-button"]');
            await cancelButton.trigger('click');
            expect(mockClose).toHaveBeenCalled();
        });
    });
}); 