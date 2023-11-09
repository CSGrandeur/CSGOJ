/// Submission queue store

/**
 * [Submission queue data structure]
 * id: numer => solution ID
 * medal: string | null => medal name
 * rank: number | '*' => current rank
 * name: string => team name
 * solved: number => team solved
 * problem: number => problem index
 * status: string => status string
 * type: string => type string
 */

function getStatusType(result) {
  switch (result) {
    case -1:
      return ['FR', 'frozen'];
    case 0:
    case 1:
    case 2:
    case 3:
      return ['PD', 'pending'];
    case 4:
      return ['AC', 'accept'];
    case 5:
      return ['PE', 'wrong'];
    case 6:
      return ['WA', 'wrong'];
    case 7:
      return ['TLE', 'wrong'];
    case 8:
      return ['MLE', 'wrong'];
    case 9:
      return ['OLE', 'wrong'];
    case 10:
      return ['RE', 'wrong'];
    case 11:
      return ['CE', 'wrong'];
  }
}

/* Export store */
export const useSubmissionQueueStore = VueUse.createGlobalState(() => {
  // States
  const isShow = Vue.ref(false);

  const queueList = Vue.ref([]);

  // Actions
  function adjustWidth() {
    const els = document.querySelectorAll('.submission-queue__name');
    const rem = parseFloat(getComputedStyle(document.documentElement).fontSize);
    els.forEach((el) => {
      const sub = el.querySelector('span');
      if (sub.style.transform !== '') {
        return;
      }

      const sw = sub.getBoundingClientRect().width;
      const cw = el.clientWidth - rem;
      if (sw > cw) {
        sub.style.transform = `scaleX(${cw / sw})`;
      } else {
        sub.style.transform = 'scaleX(1)';
      }
    });
  }

  async function update(cdata) {
    queueList.value = cdata.solution
      .slice(-8)
      .map((v) => {
        const realRank = cdata.real_rank_map[v.user_id];
        const team = cdata.map_team[v.user_id];
        const rank = cdata.real_rank_list[realRank.ith - 1];
        const [status, type] = getStatusType(v.result);

        return {
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
          status,
          type
        };
      })
      .reverse();
    await Vue.nextTick();

    adjustWidth();
  }

  // Return store
  return { isShow, queueList, adjustWidth, update };
});
