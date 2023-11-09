/// Bottom bar store
import { useLiveAppStore } from './LiveApp.esm.js';

/* Export store */
export const useBottomBarState = VueUse.createGlobalState(() => {
  // Injects
  const { contestStartAt } = useLiveAppStore();

  // States
  const isShow = Vue.ref(true);

  const customText = Vue.ref('');
  const currentAt = Vue.ref(Math.trunc(Date.now() / 1000));

  const rollingMsg = Vue.ref([]);
  let updateMsg = null;
  let currentMsgIdx = 0;

  // Getters
  const timeStr = Vue.computed(() => {
    if (customText.value !== '') {
      return '\u00a0' + customText.value;
    }

    let delta = currentAt.value - contestStartAt.value;
    let negative = false;
    if (delta < 0) {
      delta = -delta;
      negative = true;
    }

    const h = Math.trunc(delta / 3600);
    const m = Math.trunc((delta % 3600) / 60);
    const s = delta % 60;
    return (
      (negative ? '-' : '\u00a0') +
      h +
      ':' +
      m.toString().padStart(2, '0') +
      ':' +
      s.toString().padStart(2, '0')
    );
  });

  // Actions
  function setStartTime(t) {
    customText.value = t;
  }
  async function setRollingMsg(msg) {
    updateMsg = msg;
  }

  // Tick
  setInterval(() => {
    currentAt.value = Math.trunc(Date.now() / 1000);
  }, 500);

  const updateRolling = async () => {
    if (updateMsg != null) {
      rollingMsg.value = updateMsg;
      updateMsg = null;
      currentMsgIdx = 0;
    }

    if (rollingMsg.value.length === 0) {
      setTimeout(updateRolling, 500);
      return;
    }

    const e1 = document.querySelector('.bottom-bar__msg > span');
    const e2 = document.createElement('span');
    e2.innerText = rollingMsg.value[currentMsgIdx];
    e2.style.transform = 'translateY(100%)';
    e1.parentNode.appendChild(e2);

    const a1 = anime({
      targets: e1,
      translateY: '-100%',
      duration: 500,
      easing: 'easeOutQuint',
      complete: () => {
        e1.parentNode.removeChild(e1);
      }
    });
    const a2 = anime({
      targets: e2,
      translateY: '0%',
      duration: 500,
      easing: 'easeOutQuint'
    });
    currentMsgIdx = (currentMsgIdx + 1) % rollingMsg.value.length;

    await Promise.all([a1.finished, a2.finished]);
    setTimeout(updateRolling, 10000);
  };
  updateRolling();

  // Return state
  return { isShow, timeStr, setStartTime, setRollingMsg };
});
