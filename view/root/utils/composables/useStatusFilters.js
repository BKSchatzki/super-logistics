import {ref, computed, reactive, watchEffect} from 'vue';
import {useAPI} from '@utils/composables/useAPI.js';

export function useStatusFilters(topic) {

    const { get } = useAPI(topic);

    // all restrictions by user role are done in the backend
    // the parameters are so that the WordPress admin does automatically see the active and deleted items
    const data = get({active: 1, trashed: 0});

    //------------------------------------------
    // Status boxes
    //------------------------------------------
    const activeBox = ref(true);
    const inactiveBox = ref(false);
    const deletedBox = ref(false);
    const none = computed(() => {
        return !activeBox.value && !inactiveBox.value && !deletedBox.value;
    });
    const statusBoxes = reactive({
        active: activeBox,
        inactive: inactiveBox,
        deleted: deletedBox,
        none
    });

    //------------------------------------------
    // Status parameters based on checkboxes
    //------------------------------------------
    const active = computed(() => {
        if (activeBox.value && inactiveBox.value) {
            return null;
        } else if (activeBox.value) {
            return 1;
        } else if (inactiveBox.value) {
            return 0;
        }
    });
    const trashed = computed(() => {
        if (!activeBox.value && !inactiveBox.value) {
            return deletedBox.value ? 1 : null;
        } else {
            return deletedBox.value ? null : 0;
        }
    });
    const statusParams = reactive({active, trashed});
    // this constantly runs to update data based on the checkboxes
    watchEffect(() => {
        get(statusParams)
    });

    //------------------------------------------
    // Status styles
    //------------------------------------------
    const statusStyles = (data) => {
        const defaultStyle = {'border-radius': '10px'};
        if (data.trashed) {
            return {...defaultStyle, 'background': 'rgba(255, 0, 0, 0.2)'};
        } else if (!data.active) {
            return {...defaultStyle, 'background': 'rgba(150, 150, 150, 0.3)'};
        }
    }

    return { data, statusParams, statusBoxes, statusStyles };
}