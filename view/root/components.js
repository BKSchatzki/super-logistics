import Menubar from 'primevue/menubar';
import Panel from 'primevue/panel';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import Dialog from 'primevue/dialog';
import Select from 'primevue/select';
import MultiSelect from 'primevue/multiselect';
import IftaLabel from 'primevue/iftalabel';
import Toast from 'primevue/toast';
import DatePicker from 'primevue/datepicker';

const components = {
    Menubar,
    Panel,
    DataTable,
    Column,
    ColumnGroup,
    Row,
    InputText,
    Button,
    InputNumber,
    Dialog,
    MultiSelect,
    Select,
    IftaLabel,
    Toast,
    DatePicker
}


const loadComponents = (app) => {
    for (let [k, v] of Object.entries(components)) {
        app.component(k, v);
    }
}

export {loadComponents, components};