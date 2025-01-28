import InputGroupAddon from 'primevue/inputgroupaddon';
import InputGroup from 'primevue/inputgroup';
import ToggleButton from "primevue/togglebutton";
import ColumnGroup from 'primevue/columngroup';
import MultiSelect from 'primevue/multiselect';
import InputNumber from 'primevue/inputnumber';
import StepPanels from 'primevue/steppanels';
import DatePicker from 'primevue/datepicker';
import IconField from 'primevue/iconfield';
import DataTable from 'primevue/datatable';
import IftaLabel from 'primevue/iftalabel';
import StepPanel from 'primevue/steppanel';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Fieldset from 'primevue/fieldset';
import Textarea from 'primevue/textarea';
import StepItem from 'primevue/stepitem';
import StepList from 'primevue/steplist';
import Checkbox from 'primevue/checkbox';
import Stepper from 'primevue/stepper';
import Menubar from 'primevue/menubar';
import Dialog from 'primevue/dialog';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Select from 'primevue/select';
import Toast from 'primevue/toast';
import Panel from 'primevue/panel';
import Step from 'primevue/step';
import Row from 'primevue/row';
import { QrcodeStream } from 'vue-qrcode-reader'

const components = {
    InputGroupAddon,
    InputGroup,
    ToggleButton,
    ColumnGroup,
    MultiSelect,
    InputNumber,
    StepPanels,
    DatePicker,
    IconField,
    DataTable,
    IftaLabel,
    StepPanel,
    InputIcon,
    InputText,
    Fieldset,
    Textarea,
    StepItem,
    StepList,
    Checkbox,
    Stepper,
    Menubar,
    Dialog,
    Column,
    Button,
    Select,
    Toast,
    Panel,
    Step,
    Row,
    QrcodeStream
}


const loadComponents = (app) => {
    for (let [k, v] of Object.entries(components)) {
        app.component(k, v);
    }
}

export {loadComponents, components};