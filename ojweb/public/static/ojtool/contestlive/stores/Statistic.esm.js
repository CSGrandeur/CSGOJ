/// Statistic store

/**
 * [Statistic list structure]
 * accept: number
 * pending: number
 * wrong: number
 */

/* Export store */
export const useStatisticStore = VueUse.createGlobalState(() => {
  // States
  const isShow = Vue.ref(false);

  const statList = Vue.ref([]);

  // Actions
  async function update(cdata) {
    const ls = Array(cdata.map_num2p.length).fill(null);
    Object.values(cdata.map_team_sol).forEach((v) => {
      for (const i of cdata.map_num2p) {
        if (v[i] === undefined) {
          continue;
        }

        const idx = cdata.map_num2p.indexOf(i);
        const sub = v[i].slice(-1)[0];
        if (ls[idx] === null) {
          ls[idx] = {
            accept: 0,
            pending: 0,
            frozen: 0,
            wrong: 0
          };
        }

        if (v.ac[i] !== undefined) {
          ls[idx].accept++;
        }

        if (sub.result <= 3) {
          ls[idx].pending++;
        } else if (sub.result > 4) {
          ls[idx].wrong++;
        }
      }
    });

    statList.value = ls;
    await Vue.nextTick();

    document
      .querySelectorAll(
        '.statistic__graph__accept,.statistic__graph__pending,.statistic__graph__wrong'
      )
      .forEach((el) => {
        const rem = parseFloat(
          getComputedStyle(document.documentElement).fontSize
        );
        const barW =
          ((window.innerWidth * 0.4 - 2 * rem) * parseInt(el.innerText)) /
          cdata.team.length;
        const txtW = el.innerText.length * rem;
        if (barW < txtW) {
          el.style.width = `${txtW}px`;
        } else {
          el.style.width = `${barW}px`;
        }
      });
  }

  // Return state
  return { isShow, statList, update };
});
