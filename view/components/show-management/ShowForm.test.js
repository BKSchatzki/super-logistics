import {beforeEach, describe, expect, it, vi} from 'vitest';
import PrimeVue from 'primevue/config';
import {mount} from '@vue/test-utils';
import {createStore} from 'vuex';
import ShowForm from './ShowForm.vue';
import {ref} from 'vue';

// Mock store
const createVuexStore = (isInternal = true, isInternalAdmin = true) => createStore({
    state: {
        user: ref({
            isInternal,
            isInternalAdmin,
            isClient: !isInternal,
            client: isInternal ? null : {id: 123}
        })
    }
});

// Mock form assist composable
const mockSubmit = vi.fn(() => true);
vi.mock('@utils/composables/useFormAssist', () => ({
    useFormAssist: () => ({
        submitToAPI: mockSubmit,
        getDroptions: () => [
            {label: 'Client 1', value: 1}
        ]
    })
}));

// Mock vee-validate
vi.mock('vee-validate', () => ({
    useForm: () => ({
        handleSubmit: (cb) => async () => {
            // Simulate validation failure by not calling the callback
            return;
        },
        values: ref({}),
        setFieldValue: vi.fn()
    }),
    useField: () => ({
        value: ref(''),
        errorMessage: ref(''),
        validate: vi.fn()
    })
}));

describe('ShowForm.vue', () => {
    let wrapper;
    const mockClose = vi.fn();

    const mountComponent = (props = {}, isInternal = true, isInternalAdmin = true) => {
        const store = createVuexStore(isInternal, isInternalAdmin);
        return mount(ShowForm, {
            props: {
                close: mockClose,
                ...props
            },
            global: {
                plugins: [[PrimeVue, {ripple: true}], store],
                stubs: {
                    Toast: true,
                    Button: {
                        template: '<button :type="type" :data-test="$attrs[\'data-test\']" @click="$emit(\'click\')">{{label}}</button>',
                        props: ['label', 'severity', 'type'],
                        emits: ['click']
                    },
                    FormTextInput: {
                        template: '<div><input :name="name" :value="modelValue" @input="$emit(\'update:modelValue\', $event.target.value)" /></div>',
                        props: ['modelValue', 'name', 'label'],
                        emits: ['update:modelValue']
                    },
                    FormSelectInput: {
                        template: '<div><select :name="name" :value="modelValue" @change="$emit(\'update:modelValue\', $event.target.value)"><option v-for="opt in options" :key="opt.value" :value="opt.value">{{opt.label}}</option></select></div>',
                        props: ['modelValue', 'options', 'name', 'label'],
                        emits: ['update:modelValue']
                    },
                    FormNumberInput: {
                        template: '<div><input type="number" :name="name" :value="modelValue" @input="$emit(\'update:modelValue\', Number($event.target.value))" /></div>',
                        props: ['modelValue', 'name', 'label'],
                        emits: ['update:modelValue']
                    },
                    DateInput: {
                        template: '<div><input type="date" :value="modelValue" @input="$emit(\'update:modelValue\', $event.target.value)" /></div>',
                        props: ['modelValue', 'label', 'mode', 'inline'],
                        emits: ['update:modelValue']
                    },
                    ShowTextarea: {
                        template: '<div><textarea :name="name" :value="modelValue" @input="$emit(\'update:modelValue\', $event.target.value)"></textarea></div>',
                        props: ['modelValue', 'name', 'label'],
                        emits: ['update:modelValue']
                    },
                    Row: {
                        template: '<div class="row"><slot></slot></div>'
                    },
                    Col: {
                        template: '<div class="col"><slot></slot></div>'
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
            await wrapper.find('button[data-test="submit-button"]').trigger('click');
            await wrapper.vm.$nextTick();
            expect(mockSubmit).not.toHaveBeenCalled();
        });
    });

    describe('Conditional Rendering', () => {
        it('should show client field when user is internal', async () => {
            wrapper = mountComponent({}, true, false);
            await wrapper.vm.$nextTick();
            const rows = wrapper.findAll('.row');
            const clientRow = rows.find(row => row.find('select[name="client_id"]').exists());
            expect(clientRow).toBeTruthy();
        });

        it('should not show client field when user is client', async () => {
            wrapper = mountComponent({}, false, false);
            await wrapper.vm.$nextTick();
            const clientField = wrapper.find('select[name="client_id"]');
            expect(clientField.exists()).toBe(false);
        });

        it('should show carat weight fields when user is internal admin', async () => {
            wrapper = mountComponent({}, true, true);
            await wrapper.vm.$nextTick();
            const rows = wrapper.findAll('.row');
            const caratRow = rows.find(row => {
                const row_el = row.element;
                return row_el.querySelector('input[name="min_carat_weight"]') && 
                       row_el.querySelector('input[name="carat_weight_inc"]');
            });
            expect(caratRow).toBeTruthy();
        });
    });

    describe('Cancel Button', () => {
        it('should call close function when cancel button is clicked', async () => {
            wrapper = mountComponent();
            const cancelButton = wrapper.find('button[data-test="cancel-button"]');
            await cancelButton.trigger('click');
            expect(mockClose).toHaveBeenCalled();
        });
    });
}); 