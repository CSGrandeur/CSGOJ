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
  const test = Vue.ref(false);
  const testScale = Vue.ref(1);
  const contestStartAt = Vue.ref(0);
  const contestEndAt = Vue.ref(0);
  const problemColor = Vue.ref([]);
  const sync_interval = 1000;

  // Local states
  let fakeTime = 0;

  // Actions
  function init() {
    const { imgUrl, text, color } = useSignStore();
    const { isShow: bbShow, customText, setRollingMsg } = useBottomBarState();
    const { isShow: sqShow, update: sqUpdate } = useSubmissionQueueStore();
    const { isShow: stShow, update: stUpdate } = useStatisticStore();
    const { isShow: acShow, update: acUpdate } = useAcceptQueueStore();
    const {
      isShow: rkShow,
      update: rkUpdate,
      scrollTop,
      scrollBottom,
      scrollUp,
      scrollDown
    } = useRankStore();

    try {
      const url = new URL(window.location.href);
      if (url.searchParams.get('cid') === null) {
        throw Error('[CSG Live] Search param needed: cid');
      }
      if (url.searchParams.get('data') === null) {
        throw Error('[CSG Live] Search param needed: data');
      }
      if (url.searchParams.get('test') === '1') {
        test.value = true;
        document.addEventListener('keyup', (ev) => {
          if (ev.code.startsWith('Digit')) {
            testScale.value = parseInt(ev.code.slice(5));
          }
        });
        console.log('[CSG Live] Debug mode on');
      }

      DataLoadAll(
        (cdata) => {
          // console.log(cdata);

          contestStartAt.value = Math.floor(
            new Date(cdata.contest.start_time).getTime() / 1000
          );
          contestEndAt.value = Math.floor(
            new Date(cdata.contest.end_time).getTime() / 1000
          );
          problemColor.value = cdata.map_num2p.map(
            (v1) => '#' + cdata.problem.find((v2) => v2.problem_id === v1).title
          );

          sqUpdate(cdata);
          stUpdate(cdata);
          acUpdate(cdata);
          rkUpdate(cdata);
        },
        test.value ? fakeTime : null
      );

      const data = unpackData(url.searchParams.get('data'));
      imgUrl.value = data.sU;
      text.value = data.sT;
      color.value = data.sC;
      customText.value = data.f;
      setRollingMsg(data.m.split('\n'));
      sqShow.value = data.iS.includes('submission_queue');
      acShow.value = data.iS.includes('accept_queue');
      stShow.value = data.iS.includes('statistic');
      bbShow.value = data.iS.includes('bottom_bar');
      rkShow.value = data.iS.includes('rank');

      setInterval(() => {
        fakeTime += (sync_interval / 1000) * (1 << testScale.value);
        DataSync(
          (cdata) => {
            // console.log(cdata);
            sqUpdate(cdata);
            stUpdate(cdata);
            acUpdate(cdata);
            rkUpdate(cdata);
          },
          test.value ? contestStartAt.value + fakeTime : null
        );
      }, sync_interval);

      let lastTime = Math.floor(Date.now() / 1000);
      startReceiveCommand(url.searchParams.get('cid'), (data) => {
        data
          .filter((v) => v.timestamp > lastTime)
          .forEach((v) => {
            const cmd = unpackData(v.live_command);

            switch (cmd.t) {
              case 'live_sign_sync':
                imgUrl.value = cmd.sU;
                text.value = cmd.sT;
                color.value = cmd.sC;
                problemColor.value = cmd.c;
                break;
              case 'bottom_bar_sync':
                customText.value = cmd.f;
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
                scrollTop();
                break;
              case 'scroll_bottom':
                scrollBottom();
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
  return { available, test, testScale, contestStartAt, contestEndAt, problemColor, init };
});
