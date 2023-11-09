/// Statistic component
import { useLiveAppStore } from '../stores/LiveApp.esm.js';
import { useStatisticStore } from '../stores/Statistic.esm.js';

const template = `
<Transition name="t-statistic">
  <div v-show="isShow" class="statistic">
    <p class="statistic__title">Statistic</p>
    <ul class="statistic__graph">
      <li v-for="(val, idx) in statList" :key="idx">
        <span class="statistic__graph__problem" :style="'--bg-color:' + problemColor[idx]">{{ String.fromCharCode(idx + 'A'.charCodeAt(0)) }}</span>
        <span v-if="val.accept" class="statistic__graph__accept">{{ val.accept }}</span>
        <span v-if="val.pending" class="statistic__graph__pending">{{ val.pending }}</span>
        <span v-if="val.wrong" class="statistic__graph__wrong">{{ val.wrong }}</span>
      </li>
    </ul>
  </div>
</Transition>
`;

/* Export component */
export default {
  data() {
    const { isShow, statList } = useStatisticStore();
    const { problemColor } = useLiveAppStore();

    return { isShow, statList, problemColor };
  },
  mounted() {
    const { isShow } = useStatisticStore();

    document.addEventListener('keyup', (ev) => {
      if (ev.code === 'BracketRight') {
        isShow.value = !isShow.value;
      }
    });
  },
  template
};
