/// Submission queue component
import { useLiveAppStore } from '../stores/LiveApp.esm.js';
import { useSubmissionQueueStore } from '../stores/SubmissionQueue.esm.js';

const template = `
<Transition @enter="adjustWidth" name="t-submission-queue">
  <ul v-show="isShow" class="submission-queue">
    <TransitionGroup name="tg-submission-queue">
      <li v-for="i in queueList" :key="i.id">
        <div class="submission-queue__rank" :data-medal="i.medal">{{ i.rank }}</div>
        <div class="submission-queue__name">
          <span>{{ i.name }}</span>
        </div>
        <div class="submission-queue__solved">{{ i.solved }}</div>
        <div class="submission-queue__problem" :style="'--problem-color:' + problemColor[i.problem]">{{ String.fromCharCode(i.problem + 'A'.charCodeAt(0)) }}</div>
        <div class="submission-queue__status" :data-status="i.type">{{ i.status }}</div>
      </li>
    </TransitionGroup>
  </ul>
</Transition>
`;

/* Export component */
export default {
  data() {
    const { isShow, queueList, adjustWidth } = useSubmissionQueueStore();
    const { problemColor } = useLiveAppStore();
    return { isShow, queueList, adjustWidth, problemColor };
  },
  mounted() {
    const { isShow } = useSubmissionQueueStore();
    document.addEventListener('keyup', (ev) => {
      if (ev.code === 'BracketLeft') {
        isShow.value = !isShow.value;
      }
    });
  },
  template
};
