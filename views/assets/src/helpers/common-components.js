import datePicker from '@components/common/date-picker.vue';
import DateTimePicker from '@components/common/time-picker.vue';
import DoAction from '@components/common/do-action.vue';
import ContentDatePicker from '@components/common/content-date-picker.vue';
import HeaderMenu from '@components/common/menu.vue';
import Popper from 'vue-popperjs';
import DateRangePicker from '@components/common/date-range-picker.vue';
import LoadingAnimation from '@components/common/loading-animation.vue';
import DoSlot from '@components/common/do-slot.vue';

pm.Vue.component('pm-date-picker', datePicker);
pm.Vue.component('pm-date-time-picker', DateTimePicker);
pm.Vue.component('pm-do-action', DoAction);
pm.Vue.component('pm-content-datepicker', ContentDatePicker);
pm.Vue.component('pm-heder-menu', HeaderMenu);
pm.Vue.component('pm-popper', Popper);
pm.Vue.component('pm-date-range-picker', DateRangePicker);
pm.Vue.component('pm-loading-animation', LoadingAnimation);
pm.Vue.component('pm-do-slot', DoSlot);

