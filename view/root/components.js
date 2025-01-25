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
import Textarea from 'primevue/textarea';
import ToggleButton from "primevue/togglebutton";
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Checkbox from 'primevue/checkbox';
import { QrcodeStream } from 'vue-qrcode-reader'

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
    DatePicker,
    Textarea,
    ToggleButton,
    IconField,
    InputIcon,
    Checkbox,
    QrcodeStream
}


const loadComponents = (app) => {
    for (let [k, v] of Object.entries(components)) {
        app.component(k, v);
    }
}

export {loadComponents, components};