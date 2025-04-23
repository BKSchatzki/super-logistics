import {beforeEach, describe, expect, it, vi} from 'vitest';
import PrimeVue from 'primevue/config';
import {mount} from '@vue/test-utils';
import {createStore} from 'vuex';
import UserForm from './UserForm.vue';

// Mock store
const createVuexStore = (isInternal = true) => createStore({
    state: {
        user: {
            isInternal,
            isClient: !isInternal,
            client: isInternal ? null : {id: 123}
        }
    }
});

// Mock API composable
vi.mock('@utils/composables/useAPI', () => ({
    useAPI: () => ({
        get: vi.fn(),
    })
}));

// Mock form assist composable
const mockSubmit = vi.fn(() => true);
vi.mock('@utils/composables/useFormAssist', () => ({
    useFormAssist: () => ({
        submitToAPI: mockSubmit,
        getRoleDroptions: () => [
            {label: 'Client Admin', value: 'client_admin'},
            {label: 'Client Employee', value: 'client_employee'},
            {label: 'Internal', value: 'internal'}
        ],
        getDroptions: vi.fn().mockImplementation((type) => {
            if (type === 'clients') return [{label: 'Client 1', value: 1}];
            if (type === 'shows') return [{label: 'Show 1', value: 1}];
            return [];
        })
    })
}));

describe('UserForm.vue', () => {
    let wrapper;
    const mockClose = vi.fn();

    const mountComponent = (props = {}) => {
        const store = createVuexStore();
        return mount(UserForm, {
            props: {
                close: mockClose,
                ...props
            },
            global: {
                plugins: [store, [PrimeVue, {ripple: true}]],
            }
        });
    };

    beforeEach(() => {
        vi.clearAllMocks();
    });

    describe('Form Validation', () => {
        it('should not submit the form when validation fails', async () => {
            wrapper = mountComponent();


            await wrapper.find('form').trigger('submit');

            expect(mockSubmit).not.toHaveBeenCalled();
        });
    });

    describe('Conditional Rendering', () => {
        it('should show client field when role is client_admin and user is internal', async () => {
            wrapper = mountComponent();

            wrapper.vm.values.role = 'client_admin';
            await wrapper.vm.$nextTick();

            const clientField = wrapper.findComponent({name: 'FormSelectInput', props: {name: 'client_id'}});
            expect(clientField.exists()).toBe(true);
        });

        it('should show shows field when role is client_employee', async () => {
            wrapper = mountComponent();

            wrapper.vm.values.role = 'client_employee';
            await wrapper.vm.$nextTick();

            const showsField = wrapper.findComponent({name: 'FormSelectInput', props: {name: 'shows'}});
            expect(showsField.exists()).toBe(true);
        });

        it('should not show client field when user is not internal', async () => {
            const store = createVuexStore(false);
            wrapper = mount(UserForm, {
                props: {close: mockClose},
                global: {
                    plugins: [store, PrimeVue, {ripple: true}]
                }
            });

            wrapper.vm.values.role = 'client_admin';
            await wrapper.vm.$nextTick();

            const clientField = wrapper.findComponent("[data-test='cli']");
            expect(clientField.exists()).toBe(false);
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