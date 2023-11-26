/// Root component
import BottomBar from './components/BottomBar.esm.js';
import SubmissionQueue from './components/SubmissionQueue.esm.js';
import Sign from './components/Sign.esm.js';
import Statistic from './components/Statistic.esm.js';
import { useLiveAppStore } from './stores/LiveApp.esm.js';
import AcceptQueue from './components/AcceptQueue.esm.js';
import Rank from './components/Rank.esm.js';

const components = {
  BottomBar,
  SubmissionQueue,
  Sign,
  Statistic,
  AcceptQueue,
  Rank
};

const template = `
<BottomBar/>
<SubmissionQueue/>
<Sign/>
<Statistic/>
<AcceptQueue/>
<Rank/>
<div v-if="!available" class="live">Not Available</div>
<div v-if="test" class="live">Testing (x{{ 1 << testScale }})</div>
`;

/* Export component */
export default {
  data() {
    return useLiveAppStore();
  },
  mounted() {
    const { init } = useLiveAppStore();

    init();
  },
  components,
  template
};
