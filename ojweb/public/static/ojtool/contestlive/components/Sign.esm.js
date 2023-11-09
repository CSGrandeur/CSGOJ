/// Sign component
import { useSignStore } from '../stores/Sign.esm.js';

const template = `
<div class="sign">
  <img :src="imgUrl === '' ? fallback : imgUrl">
  <div :style="'color:' + color">{{ text }}</div>
</div>
`;

/* Export component */
export default {
  data() {
    const fallback = Vue.ref(window.staticDirectory + 'favicon.png');

    return { ...useSignStore(), fallback };
  },
  template
};
