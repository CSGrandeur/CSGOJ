/// Sign store

/* Export store */
export const useSignStore = VueUse.createGlobalState(() => {
  // States
  const imgUrl = Vue.ref('');
  const text = Vue.ref('ON AIR');
  const color = Vue.ref('#f00');

  // Return state
  return { imgUrl, text, color };
});
