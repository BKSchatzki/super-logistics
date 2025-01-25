import {ref} from "vue";

export function useDetailsModal() {

    const selected = ref(null);
    const unselect = () => {
        selected.value = null;
    }
    const openDetails = (evt) => {
        selected.value = evt.data
    }

    return {selected, unselect, openDetails}
}