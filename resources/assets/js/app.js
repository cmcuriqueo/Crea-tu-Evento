    import './bootstrap';

    import router from './routes';

    import App from './components/App.vue';
    import moment from 'moment';
    /* validation componente */
    import messages from 'vee-validate/dist/locale/es';
    import VeeValidate, { Validator } from 'vee-validate';
    //Validator.installDateTimeValidators(moment);

    const config = {
        locale: 'es',
        fieldsBagName: 'formFields'
    };

    Validator.localize('es', messages);

    Vue.use(VeeValidate, config);
    /* fin validacion componente */

    /* notification conponent */
    //https://github.com/chengxulvtu/cxlt-vue2-toastr
    import CxltToastr from 'cxlt-vue2-toastr'
    var toastrConfigs = {
        position: 'top right',
        showDuration: 3000,
        hideDuration: 4000
    }
    Vue.use(CxltToastr, toastrConfigs)
    /* fin notification conponent */

	import 'vue-event-calendar/dist/style.css'
	import vueEventCalendar from 'vue-event-calendar'
	Vue.use(vueEventCalendar, {locale: 'es', color: 'rgb(60, 141, 188)'})
	
    import ElementUI from 'element-ui'
    import 'element-ui/lib/theme-default/index.css'
    import locale from 'element-ui/lib/locale/lang/es'

    Vue.use(ElementUI, { locale })

    import VueNumeric from 'vue-numeric'
     
    // register directive v-money and component <money>
    Vue.use(VueNumeric)

Vue.http.headers.common['X-CSRF-TOKEN'] = document.getElementsByName('csrf-token')[0].getAttribute('content');
Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token');
Vue.http.options.root = 'http://localhost:8000';

    var app = new Vue({
        el: '#app',
        render: h => h(App),
        router
    });
