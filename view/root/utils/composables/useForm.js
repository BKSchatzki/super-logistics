import {computed, reactive, ref} from "vue";
import {useAPI} from "@utils/composables/useAPI.js";
import {useStore} from "vuex";

export function useForm(staticFormData) {

    const {get, post, update} = useAPI();
    const visible = ref(false);
    const defaultReference = {...staticFormData};
    const form = reactive(defaultReference);
    const store = useStore();

    const clearForm = () => {
        for (let key of Object.keys(form)) {
            form[key] = defaultReference[key];
        }
    }

    const getDroptions = (topic, params) => {
        const data = get(params, topic);
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

    const submit = async (topic, method = 'post') => {

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

        // Making and handling the request
        console.log("Form Data: ", form);
        const res = await request(form, topic);
        if (res.data) {
            clearForm();
            visible.value = false;
            return res.data;
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