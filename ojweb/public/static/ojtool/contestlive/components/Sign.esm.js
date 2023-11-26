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
    return {
      ...useSignStore(),
      fallback: Vue.ref(window.staticDirectory + 'favicon.png')
    };
  },
  template
};
