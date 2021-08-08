import 'flatpickr/dist/themes/dark.css';
import flatpickr from "flatpickr";

const params = document.getElementById('js-params');
const locale = params.getAttribute('data-locale');
const date_format = params.getAttribute('data-date-format');

flatpickr.localize(locale);

const picker = flatpickr('input[data-datepicker]', {
    enableTime: true,
    allowInput: true,
    dateFormat: date_format,
    locale: locale,
});

console.info('locale', locale);
console.info('date_fromat', date_format);
console.info('datetimepicker', picker);
