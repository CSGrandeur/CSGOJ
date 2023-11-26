/// Rank component
import { useLiveAppStore } from '../stores/LiveApp.esm.js';
import { useRankStore } from '../stores/Rank.esm.js';

const template = `
<Transition @enter="adjustWidth" name="t-rank">
  <div v-show="isShow" class="rank">
    <p class="rank__title">Rank</p>
    <div class="rank__wrapper">
      <div class="rank__wrapper__header">
        <span class="rank__wrapper__rank">Rnk</span>
        <span class="rank__wrapper__name">Name</span>
        <span class="rank__wrapper__solved">Σ</span>
        <span class="rank__wrapper__penalty">PEN</span>
        <div class="rank__wrapper__problems" :style="'grid-template-columns: repeat(' + problemColor.length + ', 1fr);'">
          <span v-for="(val, idx) in problemColor" :key="idx" :style="'--problem-color:' + val">{{ String.fromCharCode(idx + 'A'.charCodeAt(0)) }}</span>
        </div>
      </div>
      <ul class="rank__wrapper__list">
        <TransitionGroup name="tg-rank">
          <li v-for="i in rankList" :key="i.id">
            <span class="rank__wrapper__rank" :data-medal="i.medal">{{ i.rank }}</span>
            <div class="rank__wrapper__name rank__wrapper__list__name">
              <span>{{ i.name }}</span>
            </div>
            <span class="rank__wrapper__solved">{{ i.solved }}</span>
            <span class="rank__wrapper__penalty">{{ i.penalty }}</span>
            <div class="rank__wrapper__problems" :style="'grid-template-columns: repeat(' + i.status.length + ', 1fr);'">
              <span v-for="(val, idx) in i.status" :key="idx" :data-type="val !== null ? val.type : null">{{ val !== null ? val.text : '' }}</span>
            </div>
          </li>
        </TransitionGroup>
      </ul>
    </div>
  </div>
</Transition>
`;

/* Export component */
export default {
  data() {
    const { isShow, rankList, adjustWidth } = useRankStore();
    const { problemColor } = useLiveAppStore();
    return { isShow, rankList, adjustWidth, problemColor };
  },
  mounted() {
    const { isShow,scrollTop, scrollBottom, scrollUp, scrollDown } = useRankStore();
    document.addEventListener('keyup', (ev) => {
      switch (ev.code) {
        case 'Equal':
          isShow.value = !isShow.value;
          break;
        case 'KeyJ':
          scrollDown();
          break;
        case 'KeyK':
          scrollUp();
          break;
        case 'KeyH':
          scrollBottom();
          break;
        case 'KeyL':
          scrollTop();
          break;
      }
    });
  },
  template
};
