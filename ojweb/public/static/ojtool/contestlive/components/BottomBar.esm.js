/// Button bar component
import { useBottomBarState } from '../stores/BottomBar.esm.js';

const template = `
<Transition name="t-bottom-bar">
  <div v-show="isShow" class="bottom-bar">
    <div class="bottom-bar__time">{{ timeStr }}</div>
    <div class="bottom-bar__msg">
      <span></span>
    </div>
  </div>
</Transition>
`;

/* Export component */
export default {
  data() {
    return useBottomBarState();
  },
  mounted() {
    const { isShow } = useBottomBarState();
    document.addEventListener('keyup', (ev) => {
      if (ev.code === 'Space') {
        isShow.value = !isShow.value;
      }
    });
  },
  template
};
