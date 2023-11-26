/// Index application store
import { sendCommand } from '../utils/command.esm.js';
import { notify, useNotification } from '../components/Notification.esm.min.js';
import { packData } from '../utils/pack.esm.js';

/* Export store */
export const useCtrlAppStore = VueUse.createGlobalState(() => {
  // States
  const status = Vue.ref('disconnect');

  const signUrl = VueUse.useStorage('csg-live-sign-url', '', localStorage);
  const signTxt = VueUse.useStorage(
    'csg-live-sign-txt',
    'ON AIR',
    localStorage
  );
  const signColor = VueUse.useStorage(
    'csg-live-sign-color',
    '#ff0000',
    localStorage
  );

  const focus = VueUse.useStorage('csg-live-focus', '', localStorage);
  const messages = VueUse.useStorage('csg-live-msgs', '', localStorage);

  const isShow = VueUse.useStorage(
    'csg-live-is-show',
    ['bottom_bar'],
    localStorage
  );

  // Getters
  const isShown = Vue.computed(() => (k) => isShow.value.includes(k));

  // Actions
  function openOverlay() {
    const { notify } = useNotification();

    if (window.csgLiveCid === null) {
      notify({
        title: 'Contest ID param lost',
        type: 'error'
      });
      return;
    }

    const url = new URL(window.location.href);
    url.pathname = '/ojtool/contestlive/live';
    url.searchParams.set(
      'data',
      packData({
        sU: signUrl.value,
        sT: signTxt.value,
        sC: signColor.value,
        f: focus.value,
        m: messages.value,
        iS: isShow.value
      })
    );

    window.open(url.href, '_blank');
  }
  function resetLiveSignSection() {
    signUrl.value = '';
    signTxt.value = 'ON AIR';
    signColor.value = '#ff0000';
  }
  async function syncLiveSignSection() {
    if (
      await sendCommand({
        t: 'live_sign_sync',
        sU: signUrl.value,
        sT: signTxt.value,
        sC: signColor.value
      })
    ) {
      notify({
        title: 'Live sign synchronized',
        type: 'success'
      });
    } else {
      notify({
        title: 'Live sign synchronizing failed',
        type: 'error'
      });
    }
  }
  function resetBottomBarSection() {
    focus.value = '';
    messages.value = '';
  }
  async function syncBottomBarSection() {
    if (
      await sendCommand({
        t: 'bottom_bar_sync',
        f: focus.value,
        m: messages.value
      })
    ) {
      notify({
        title: 'Bottom bar synchronized',
        type: 'success'
      });
    } else {
      notify({
        title: 'Bottom bar synchronizing failed',
        type: 'error'
      });
    }
  }
  async function togglePanel(s) {
    if (isShow.value.includes(s)) {
      isShow.value = isShow.value.filter((v) => v !== s);
    } else {
      isShow.value.push(s);
    }

    if (
      await sendCommand({
        t: 'panel_show_change',
        iS: isShow.value
      })
    ) {
      notify({
        title: 'Panel updated',
        type: 'success'
      });
    } else {
      notify({
        title: 'Panel updating failed',
        type: 'error'
      });
    }
  }
  async function showAllPanel(b) {
    if (b) {
      isShow.value = [
        'submission_queue',
        'accept_queue',
        'rank',
        'statistic',
        'bottom_bar'
      ];
    } else {
      isShow.value = [];
    }

    if (
      await sendCommand({
        t: 'panel_show_change',
        iS: isShow.value
      })
    ) {
      notify({
        title: 'Panel updated',
        type: 'success'
      });
    } else {
      notify({
        title: 'Panel updating failed',
        type: 'error'
      });
    }
  }
  async function scrollToTop() {
    if (await sendCommand({ t: 'scroll_top' })) {
      notify({
        title: 'Scrolled',
        type: 'success'
      });
    } else {
      notify({
        title: 'Scrolling failed',
        type: 'error'
      });
    }
  }
  async function scrollToBottom() {
    if (await sendCommand({ t: 'scroll_bottom' })) {
      notify({
        title: 'Scrolled',
        type: 'success'
      });
    } else {
      notify({
        title: 'Scrolling failed',
        type: 'error'
      });
    }
  }
  async function scrollUp() {
    if (await sendCommand({ t: 'scroll_up' })) {
      notify({
        title: 'Scrolled',
        type: 'success'
      });
    } else {
      notify({
        title: 'Scrolling failed',
        type: 'error'
      });
    }
  }
  async function scrollDown() {
    if (await sendCommand({ t: 'scroll_down' })) {
      notify({
        title: 'Scrolled',
        type: 'success'
      });
    } else {
      notify({
        title: 'Scrolling failed',
        type: 'error'
      });
    }
  }

  // Return store
  return {
    status,
    signUrl,
    signTxt,
    signColor,
    focus,
    messages,
    isShown,
    openOverlay,
    resetLiveSignSection,
    syncLiveSignSection,
    resetBottomBarSection,
    syncBottomBarSection,
    togglePanel,
    showAllPanel,
    scrollToTop,
    scrollToBottom,
    scrollUp,
    scrollDown
  };
});
