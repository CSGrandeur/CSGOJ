/// Index main entrance

/* Plugins */
import Notifications from './components/Notification.esm.min.js';

/* Root component */
import CtrlApp from './CtrlApp.esm.js';

/* Create application */
Vue.createApp(CtrlApp).use(Notifications).mount('#app');
