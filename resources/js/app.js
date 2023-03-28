import ElementUI from 'element-ui'
import { Message } from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/en'
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'
import draggable from 'vuedraggable'
import { Datetime } from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'

import CurrencyFormat from './filters/CurrencyFormat'
import FormatDate from './filters/FormatDate'
import SnakeCaseToTitleCase from './filters/SnakeCaseToTitleCase'
import TrimString from './filters/TrimString'
import Vue from 'vue'
import CKEditor from '@ckeditor/ckeditor5-vue2';
import VueQrcode from '@chenfengyuan/vue-qrcode';
import moment from 'moment';


require('./bootstrap')

window.Vue = require('vue')
Vue.use(ElementUI, { locale })
Vue.use(VueSweetalert2)
Vue.use(Datetime)
Vue.use(CKEditor);
Vue.component(VueQrcode.name, VueQrcode);

Vue.filter('currencyFormat', CurrencyFormat)
Vue.filter('formatDate', FormatDate)
Vue.filter('snakeToTitle', SnakeCaseToTitleCase)
Vue.filter('trimString', TrimString)

Vue.component('login', require('./pages/Login').default)
Vue.component('admin-home', require('./pages/Admin/Home').default)
Vue.component('admin-users', require('./pages/Admin/Users').default)
Vue.component('datetime', Datetime)
Vue.component('draggable', draggable)

Vue.component('student-home', require('./pages/Student/ID/List').default)
Vue.component('qr-code', require('./pages/Student/ID/Qr').default)

Vue.component('id-setup', require('./pages/Admin/IDPrinting/setup').default)
Vue.component('request-setup', require('./pages/Admin/IDPrinting/RequestSetup').default)
Vue.component('id-requests', require('./pages/Admin/IDRequests/Requests').default)

Vue.component('access-rights', require('./pages/Admin/AccessRights/List').default)
Vue.component('edit-permission', require('./pages/Admin/AccessRights/Edit').default)

Vue.component('admin-employees', require('./pages/Admin/Employees/List').default)
Vue.component('admin-view-employee', require('./pages/Admin/Employees/View').default)
Vue.component('store-setup', require('./pages/Admin/Store/Setup').default)
Vue.component('add-product', require('./pages/Admin/Store/AddProduct').default)


const app = new Vue({
    el: '#app',
    data() {
        return {
            auth: {
                branchCode: window.auth ? window.auth.branch_code : null,
                id: window.auth ? window.auth.user_id : null,
                info: window.auth ? window.auth.user_info : null
            },
            folder_name: '/store',
            permissions: window.auth ? window.auth.permissions : null,
            moment: moment,
        }
    },
    methods: {
        defaultError(msg = 'Oops! Something went wrong.') {
            Message({
                showClose: true,
                message: msg,
                type: 'error',
                duration: 0
            })
        },
        arrayFind(array, condition) {
            const item = array.find(condition)
            return array.indexOf(item)
        },
        inArray(needle, arr) {
            var length = arr.length
            for (var i = 0; i < length; i++) {
                if (arr[i] == needle) return true
            }
            return false
        },
        logout() {
            axios.get(this.folder_name + '/logout').then((data) => {
                window.location.href = data.data
            })
        }
    },
})

export default app