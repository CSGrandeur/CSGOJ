/// Settings store
import { startReceiveCommand } from '../utils/command.esm.js';
import { unpackData } from '../utils/pack.esm.js';
import { useAcceptQueueStore } from './AcceptQueue.esm.js';
import { useBottomBarState } from './BottomBar.esm.js';
import { useRankStore } from './Rank.esm.js';
import { useSignStore } from './Sign.esm.js';
import { useStatisticStore } from './Statistic.esm.js';
import { useSubmissionQueueStore } from './SubmissionQueue.esm.js';

/* Export store */
export const useLiveAppStore = VueUse.createGlobalState(() => {
  // States
  const available = Vue.ref(false);

  const contestStartAt = Vue.ref(0);
  const problemColor = Vue.ref([]);

  // Actions
  function init() {
    const { imgUrl, text, color } = useSignStore();
    const { isShow: bbShow, setStartTime, setRollingMsg } = useBottomBarState();
    const { isShow: sqShow, update: sqUpdate } = useSubmissionQueueStore();
    const { isShow: stShow, update: stUpdate } = useStatisticStore();
    const { isShow: acShow, update: acUpdate } = useAcceptQueueStore();
    const {
      isShow: rkShow,
      update: rkUpdate,
      scrollToTop,
      scrollToBottom,
      scrollUp,
      scrollDown
    } = useRankStore();

    try {
      const url = new URL(window.location.href);
      if (url.searchParams.get('cid') === null) {
        throw Error('Search param needed: cid');
      }
      if (url.searchParams.get('data') === null) {
        throw Error('Search param needed: data');
      }

      DataLoadAll((cdata) => {
        console.log(cdata);
        contestStartAt.value = Math.floor(
          new Date(cdata.contest.start_time).getTime() / 1000
        );
        problemColor.value = cdata.map_num2p.map(
          (v1) => '#' + cdata.problem.find((v2) => v2.problem_id === v1).title
        );
        sqUpdate(cdata);
        stUpdate(cdata);
        acUpdate(cdata);
        rkUpdate(cdata);
      });

      const data = unpackData(url.searchParams.get('data'));
      imgUrl.value = data.sU;
      text.value = data.sT;
      color.value = data.sC;
      setStartTime(data.f);
      setRollingMsg(data.m.split('\n'));
      sqShow.value = data.iS.includes('submission_queue');
      acShow.value = data.iS.includes('accept_queue');
      stShow.value = data.iS.includes('statistic');
      bbShow.value = data.iS.includes('bottom_bar');
      rkShow.value = data.iS.includes('rank');

      setInterval(() => {
        DataSync((cdata) => {
          sqUpdate(cdata);
          stUpdate(cdata);
          acUpdate(cdata);
          rkUpdate(cdata);
        });
      }, 5000);

      let lastTime = Math.floor(Date.now() / 1000);
      startReceiveCommand(url.searchParams.get('cid'), (data) => {
        data
          .filter((v) => v.timestamp > lastTime)
          .forEach((v) => {
            const cmd = unpackData(v.live_command);

            switch (cmd.t) {
              case 'contest_sync':
                imgUrl.value = cmd.sU;
                text.value = cmd.sT;
                color.value = cmd.sC;
                problemColor.value = cmd.c;
                break;
              case 'bottom_bar_sync':
                setStartTime(cmd.f);
                setRollingMsg(cmd.m.split('\n'));
                break;
              case 'panel_show_change':
                sqShow.value = cmd.iS.includes('submission_queue');
                acShow.value = cmd.iS.includes('accept_queue');
                stShow.value = cmd.iS.includes('statistic');
                bbShow.value = cmd.iS.includes('bottom_bar');
                rkShow.value = cmd.iS.includes('rank');
                break;
              case 'scroll_top':
                scrollToTop();
                break;
              case 'scroll_bottom':
                scrollToBottom();
                break;
              case 'scroll_up':
                scrollUp();
                break;
              case 'scroll_down':
                scrollDown();
                break;
            }

            lastTime = v.timestamp;
          });
      });

      available.value = true;
    } catch (e) {
      console.error(e);
    }
  }

  // Return state
  return { available, contestStartAt, problemColor, init };
});
