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
    const els = document.querySelectorAll('.rank__wrapper__list__name');
    const rem = parseFloat(getComputedStyle(document.documentElement).fontSize);
    els.forEach((el) => {
      const sub = el.querySelector('span');
      if (sub.style.transform !== '') {
        return;
      }

      const sw = sub.getBoundingClientRect().width;
      const cw = window.innerWidth * 0.15 - rem;
      if (sw > cw) {
        sub.style.transform = `scaleX(${cw / sw})`;
      } else {
        sub.style.transform = 'scaleX(1)';
      }
    });
  }

  async function update(cdata) {
    rankList.value = cdata.real_rank_list.map((v) => {
      const realRank = cdata.real_rank_map[v.team_id];
      const team = cdata.map_team[v.team_id];
      const solved = cdata.map_team_sol[v.team_id];

      return {
        id: v.team_id,
        rank: cdata.real_rank_map[v.team_id].rank,
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
        solved: v.sol,
        penalty: v.penalty,
        status: cdata.map_num2p.map((v) => {
          if (solved[v] === undefined) {
            return null;
          }

          if (solved.ac[v] !== undefined) {
            return {
              color: '#383a',
              text: solved[v].length - 1 === 0 ? '' : solved[v].length - 1
            };
          }
          if (solved[v].slice(-1)[0].result === -1) {
            return {
              color: '#aa0a',
              text: solved[v].length
            };
          }
          return {
            color: '#e00a',
            text: solved[v].length
          };
        })
      };
    });
    await Vue.nextTick();

    adjustWidth();
  }

  function scrollToTop() {
    const el = document.querySelector('.rank__wrapper');
    const obj = { data: el.scrollTop };
    anime({
      targets: obj,
      duration: 500,
      easing: 'easeInOutQuint',
      data: 0,
      update: () => {
        el.scrollTo(0, obj.data);
      }
    });
  }
  function scrollToBottom() {
    const el = document.querySelector('.rank__wrapper');
    const obj = { data: el.scrollTop };
    anime({
      targets: obj,
      duration: 500,
      easing: 'easeInOutQuint',
      data: el.scrollHeight,
      update: () => {
        el.scrollTo(0, obj.data);
      }
    });
  }
  function scrollUp() {
    const el = document.querySelector('.rank__wrapper');
    const obj = { data: el.scrollTop };
    anime({
      targets: obj,
      duration: 500,
      easing: 'easeInOutQuint',
      data: Math.max(0, el.scrollTop - window.innerHeight * 0.4),
      update: () => {
        el.scrollTo(0, obj.data);
      }
    });
  }
  function scrollDown() {
    const el = document.querySelector('.rank__wrapper');
    const obj = { data: el.scrollTop };
    anime({
      targets: obj,
      duration: 500,
      easing: 'easeInOutQuint',
      data: Math.min(el.scrollHeight, el.scrollTop + window.innerHeight * 0.4),
      update: () => {
        el.scrollTo(0, obj.data);
      }
    });
  }

  // Return store
  return {
    isShow,
    rankList,
    adjustWidth,
    update,
    scrollToTop,
    scrollToBottom,
    scrollUp,
    scrollDown
  };
});
