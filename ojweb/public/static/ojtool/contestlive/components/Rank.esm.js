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
        <div class="rank__wrapper__problems" :style="rankList.length > 0 ? 'grid-template-columns: repeat(' + rankList[0].status.length + ', 1fr);' : ''">
          <span v-for="(_, idx) in (rankList.length > 0 ? rankList[0].status : [])" :key="idx" :style="'--bg-color:' + problemColor[idx]">{{ String.fromCharCode(idx + 'A'.charCodeAt(0)) }}</span>
        </div>
      </div>
      <ul class="rank__wrapper__list">
        <TransitionGroup name="tg-rank">
          <li v-for="i in rankList" :key="i.id">
            <span class="rank__wrapper__rank" :class="i.medal !== null ? 'rank__wrapper__rank--' + i.medal : ''">{{ i.rank }}</span>
            <div class="rank__wrapper__name rank__wrapper__list__name">
              <span>{{ i.name }}</span>
            </div>
            <span class="rank__wrapper__solved">{{ i.solved }}</span>
            <span class="rank__wrapper__penalty">{{ i.penalty }}</span>
            <div class="rank__wrapper__problems" :style="'grid-template-columns: repeat(' + i.status.length + ', 1fr);'">
              <span v-for="(val, idx) in i.status" :key="idx" :style="val !== null ? 'background-color:' + val.color : ''">{{ val !== null ? val.text : '' }}</span>
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
    const { isShow } = useRankStore();

    document.addEventListener('keyup', (ev) => {
      if (ev.code === 'Equal') {
        isShow.value = !isShow.value;
      }
    });
  },
  template
};
