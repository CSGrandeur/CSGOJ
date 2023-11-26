/// Accept queue store

/**
 * [Submission queue data structure]
 * id: numer => solution ID
 * medal: string | null => medal name
 * rank: number | '*' => current rank
 * name: string => team name
 * solved: number => team solved
 * problem: number => problem index
 * penalty: string => penalty time
 * first: boolean => first blood
 */

/* Export store */
export const useAcceptQueueStore = VueUse.createGlobalState(() => {
  // States
  const isShow = Vue.ref(false);
  const queueList = Vue.ref([]);

  // Actions
  function adjustWidth() {
    const rem = parseFloat(getComputedStyle(document.documentElement).fontSize);
    document.querySelectorAll('.accept-queue__name').forEach((el) => {
      // If not shown
      if (el.clientWidth === 0) {
        return;
      }

      // If scaled
      const sub = el.querySelector('span');
      if (sub.style.transform !== '') {
        return;
      }

      // Set scale
      const cw = el.clientWidth - rem;
      const sw = sub.clientWidth;
      if (sw > cw) {
        sub.style.transform = `scaleX(${cw / sw})`;
      } else {
        sub.style.transform = 'scaleX(1)';
      }
    });
  }
  async function update(cdata) {
    const ls = [];
    for (let i = cdata.solution.length - 1; i >= 0 && ls.length < 5; i--) {
      // If not accept
      if (cdata.solution[i].result !== 4) {
        continue;
      }

      // Generate item
      const v = cdata.solution[i];
      const realRank = cdata.real_rank_map[v.user_id];
      const team = cdata.map_team[v.user_id];
      const rank = cdata.real_rank_list[realRank.ith - 1];
      ls.push({
        id: v.solution_id,
        medal:
          realRank.rank === '*'
            ? null
            : realRank.rank <= cdata.rank_gold
            ? 'gold'
            : realRank.rank <= cdata.rank_silver
            ? 'silver'
            : realRank.rank <= cdata.rank_bronze
            ? 'bronze'
            : null,
        rank: realRank.rank,
        name: team.school + ' ' + team.name,
        solved: rank.sol,
        problem: cdata.map_num2p.indexOf(v.problem_id),
        penalty: Math.floor(
          (cdata.map_team_sol[v.user_id].ac[v.problem_id] -
            Math.floor(new Date(cdata.contest.start_time).getTime() / 1000)) /
            60
        ),
        first: cdata.map_fb.formal[v.problem_id]?.teams?.[v.user_id] === true
      });
    }

    // Update DOM
    queueList.value.splice(0, queueList.value.length, ...ls);
    await Vue.nextTick();
    adjustWidth();
  }

  // Return store
  return { isShow, queueList, adjustWidth, update };
});
