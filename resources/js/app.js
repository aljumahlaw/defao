import './bootstrap';

import Alpine from 'alpinejs';
import moment from 'moment';
import momentHijri from 'moment-hijri';

// Use the hijri-extended moment
window.Alpine = Alpine;
window.moment = momentHijri;

Alpine.start();
