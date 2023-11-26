/// Accept queue component
import { useLiveAppStore } from '../stores/LiveApp.esm.js';
import { useAcceptQueueStore } from '../stores/AcceptQueue.esm.js';

const template = `
<Transition @enter="adjustWidth" name="t-accept-queue">
  <ul v-show="isShow" class="accept-queue">
    <TransitionGroup name="tg-accept-queue">
      <li v-for="i in queueList" :key="i.id">
        <div class="accept-queue__rank" :data-medal="i.medal">{{ i.rank }}</div>
        <div class="accept-queue__name">
          <span>{{ i.name }}</span>
        </div>
        <div class="accept-queue__solved">{{ i.solved }}</div>
        <div class="accept-queue__problem" :style="'--problem-color:' + problemColor[i.problem]">{{ String.fromCharCode(i.problem + 'A'.charCodeAt(0)) }}</div>
        <div class="accept-queue__penalty" :data-first="i.first">{{ i.penalty }}</div>
      </li>
    </TransitionGroup>
  </ul>
</Transition>
`;

/* Export component */
export default {
  data() {
    const { isShow, queueList, adjustWidth } = useAcceptQueueStore();
    const { problemColor } = useLiveAppStore();
    return { isShow, queueList, adjustWidth, problemColor };
  },
  mounted() {
    const { isShow } = useAcceptQueueStore();
    document.addEventListener('keyup', (ev) => {
      if (ev.code === 'Minus') {
        isShow.value = !isShow.value;
      }
    });
  },
  template
};
