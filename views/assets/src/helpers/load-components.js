import PrimeVue from 'primevue/config';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

function loadComponents(vue) {
    vue.use(PrimeVue);
    vue.component('DataTable', DataTable);
    vue.component('Column', Column);
}

export default loadComponents;
