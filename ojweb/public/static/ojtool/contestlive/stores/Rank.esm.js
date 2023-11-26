/// Rank store

/**
 * [Rank list data structure]
 * id: string => team id
 * rank: string | '*' => team rank
 * medal: string | null => team medal
 * name: string => team name
 * sovled: number => team solved
 * penalty: number => team penalty
 * status: []object => team problem status
 */

/* Export store */
export const useRankStore = VueUse.createGlobalState(() => {
  // States
  const isShow = Vue.ref(false);
  const rankList = Vue.ref([]);

  // Actions
  function adjustWidth() {
    const rem = parseFloat(getComputedStyle(document.documentElement).fontSize);
    document.querySelectorAll('.rank__wrapper__list__name').forEach((el) => {
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
    rankList.value.splice(
      0,
      rankList.value.length,
      ...cdata.real_rank_list.map((v1) => {
        // Generate item
        const realRank = cdata.real_rank_map[v1.team_id];
        const team = cdata.map_team[v1.team_id];
        const solved = cdata.map_team_sol[v1.team_id];
        return {
          id: v1.team_id,
          rank: cdata.real_rank_map[v1.team_id].rank,
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
          name: team.school + ' ' + team.name,
          solved: v1.sol,
          penalty: v1.penalty,
          status: cdata.map_num2p.map((v2) => {
            if (solved[v2] === undefined) {
              return null;
            }

            if (solved.ac[v2] !== undefined) {
              return {
                type:
                  cdata.map_fb.formal[v2]?.teams?.[v1.team_id] === true
                    ? 'first'
                    : 'accept',
                text: solved[v2].length - 1 === 0 ? '' : solved[v2].length - 1
              };
            }
            if (solved[v2].slice(-1)[0].result <= 3) {
              return {
                type: 'pending',
                text: solved[v2].length
              };
            }
            return {
              type: 'wrong',
              text: solved[v2].length
            };
          })
        };
      })
    );

    // Update DOM
    await Vue.nextTick();
    adjustWidth();
  }
  function _scrollAnimation(targetY) {
    const el = document.querySelector('.rank__wrapper');
    const obj = { y: el.scrollTop };
    anime({
      targets: obj,
      duration: 500,
      easing: 'easeInOutQuint',
      y: Math.max(0, Math.min(targetY, el.scrollHeight)),
      update: () => {
        el.scrollTo(0, obj.y);
      }
    });
  }
  function scrollTop() {
    _scrollAnimation(0);
  }
  function scrollBottom() {
    const el = document.querySelector('.rank__wrapper');
    _scrollAnimation(el.scrollHeight);
  }
  function scrollUp() {
    const el = document.querySelector('.rank__wrapper');
    _scrollAnimation(el.scrollTop - window.innerHeight * 0.4);
  }
  function scrollDown() {
    const el = document.querySelector('.rank__wrapper');
    _scrollAnimation(el.scrollTop + window.innerHeight * 0.4);
  }

  // Return store
  return {
    isShow,
    rankList,
    adjustWidth,
    update,
    scrollTop,
    scrollBottom,
    scrollUp,
    scrollDown
  };
});
