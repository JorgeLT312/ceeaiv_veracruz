import moment from 'moment';
import 'moment/dist/locale/es-mx';


const Plugin = {
    install(app, options) {
        app.config.globalProperties.$filters = {
            /**
             * Convert timestamps to the date format: D MMM YYYY
             *
             * @param  {String|Number}  date
             * @param  {String}  format
             * @return {String}
             */
            toDate(date, format = 'D MMM YYYY hh:mm \\h\\r\\s') {
                if (date) {
                    moment.locale('es-mx');
                    return typeof date === 'number' ? moment.unix(date).format(format) : moment(date).format(format);
                } else {
                    return '-';
                }
            },

            /**
             * Convert timestamps to the date format: D MMM YYYY
             *
             * @param  {String|Number}  date
             * @param  {String}  format
             * @return {String}
             */
            toFullDate(date, format = 'dddd D \\d\\e MMMM \\d\\e YYYY \\a \\l\\a\\s H:mm \\h\\r\\s\\.') {
                if (date) {
                    moment.locale('es-mx');
                    return typeof date === 'number' ? moment.unix(date).format(format) : moment(date).format(format);
                } else {
                    return '-';
                }
            }
        }
    },
};

export default Plugin;

