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
    // Create empty list
    const ls = Array(cdata.map_num2p.length).fill(null);
    for (const i in ls) {
      ls[i] = {
        accept: 0,
        pending: 0,
        wrong: 0
      };
    }

    // Caluculating
    Object.values(cdata.map_team_sol).forEach((v) => {
      for (const i of cdata.map_num2p) {
        // If team not submit this problem
        if (v[i] === undefined) {
          continue;
        }

        // If check problem status
        const idx = cdata.map_num2p.indexOf(i);
        const sub = v[i].slice(-1)[0];
        if (v.ac[i] !== undefined) {
          ls[idx].accept++;
        } else if (sub.result <= 3) {
          ls[idx].pending++;
        } else if (sub.result > 4) {
          ls[idx].wrong++;
        }
      }
    });

    // Update DOM
    statList.value.splice(0, statList.value.length, ...ls);
    await Vue.nextTick();

    // Adjust graph
    const rem = parseFloat(getComputedStyle(document.documentElement).fontSize);
    const totW = window.innerWidth * 0.4 - 2 * rem;
    document
      .querySelectorAll(
        '.statistic__graph__accept,.statistic__graph__pending,.statistic__graph__wrong'
      )
      .forEach((el) => {
        const barW = (totW * parseInt(el.innerText)) / cdata.team.length;
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
