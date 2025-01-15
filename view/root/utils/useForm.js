import {computed, reactive, ref} from "vue";
import {useAPI} from "@utils/useApi";
import {useToast} from "primevue/usetoast";
import {useStore} from "vuex";

export function useForm(staticFormData) {

    const {get, post, update} = useAPI();
    const toast = useToast();
    const visible = ref(false);
    const form = reactive(staticFormData);
    const store = useStore();

    const clearForm = () => {
        for (let key of Object.keys(form)) {
            if (typeof Object[key] === 'string') {
                form[key] = '';
            }
            if (Array.isArray(typeof Object[key])) {
                form[key] = [];
                continue;
            } else if (typeof Object[key] === 'object') {
                form[key] = {};
            }
            form[key] = null;
        }
        return form;
    }

    const getDroptions = (topic, params) => {
        const data = get(topic, params);
        return computed(() => data.value.map(d => ({label: d.name, value: parseInt(d.id)})));
    }

    const getRoleDroptions = () => {
        const user = computed(() => store.state.user)
        const clientRoleOptions = [
            {label: 'Client Admin', value: 'client_admin'},
            {label: 'Client Employee', value: 'client_employee'}
        ]
        const internalRoleOptions = [
            ...clientRoleOptions,
            {label: 'Internal Admin', value: 'internal_admin'},
            {label: 'Internal Employee', value: 'internal_employee'}
        ]
        return computed(() => user.value['isInternal'] ? internalRoleOptions : clientRoleOptions);
    }

    const submit = async (topic, method, customToastConfig) => {

        // Method for Request
        const methodFunctions = {
            post: post,
            update: update
        }
        const request = methodFunctions[method];
        if (!request) {
            console.error('Improper method provided while making submit request. Method provided: ', method);
            return;
        }

        // Toast Messaging
        const defaultToastConfig = {
            success: {severity: 'success', summary: 'Success', detail: 'Data saved successfully', life: 3000},
            fail: {severity: 'error', summary: 'Error', detail: 'Failed to save data', life: 3000}
        }
        const toastConfig = {
            success: {...defaultToastConfig.success, ...customToastConfig.success},
            fail: {...defaultToastConfig.fail, ...customToastConfig.fail}
        }

        // Making and handling the request
        const res = await request(topic, form);
        if (res.data) {
            toast.add(toastConfig.success);
            clearForm();
            visible.value = false;
        } else {
            toast.add(toastConfig.fail);
        }
    }

    return {
        form,
        visible,
        getDroptions,
        getRoleDroptions,
        clearForm,
        submit
    }
}