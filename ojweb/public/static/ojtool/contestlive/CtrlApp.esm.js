/// Index root component
import { useCtrlAppStore } from './stores/CtrlApp.esm.js';

const appVersion = '1.0.0';

const template = `
<notifications position="top right" />
<div class="controller">
  <header>
    <img :src="logo">
    <h1>CSG Live Controller</h1>
    <span>${appVersion}</span>
  </header>
  <main>
    <section>
      <h2>Overlay</h2>
      <hr>
      <p>&ensp;</p>
      <div class="controller__button-set">
        <button @click="openOverlay">Open Overlay</button>
      </div>
    </section>
    <section>
      <h2>Live sign</h2>
      <hr>
      <table>
        <tbody>
          <tr>
            <th>Image URL</th>
            <td><input v-model="signUrl" type="url"></td>
          </tr>
          <tr>
            <th>Sign Text</th>
            <td><input v-model="signTxt" type="text"></td>
          </tr>
          <tr>
            <th>Text color</th>
            <td><input v-model="signColor" type="color"></td>
          </tr>
        </tbody>
      </table>
      <p>&ensp;</p>
      <div class="controller__button-set">
        <button @click="resetLiveSignSection">Reset All</button>
        <button @click="syncLiveSignSection">Synchronize to Overlay</button>
      </div>
    </section>
    <section>
      <h2>Bottom Bar</h2>
      <hr>
      <h3>Focus</h3>
      <input v-model.trim="focus" placeholder="Empty to show time" style="margin: 0.5rem 0 1rem 0" type="text">
      <h3>Messages</h3>
      <textarea v-model.trim="messages" placeholder="Each message seperate with new line"></textarea>
      <p>&ensp;</p>
      <div class="controller__button-set">
        <button @click="resetBottomBarSection">Reset All</button>
        <button @click="syncBottomBarSection">Synchronize to Overlay</button>
      </div>
    </section>
    <section>
      <h2>Panel</h2>
      <hr>
      <p>&ensp;</p>
      <div class="controller__button-set">
        <button @click="togglePanel('submission_queue')" :style="isShown('submission_queue') ? 'border-color:red' : ''">{{ isShown('submission_queue') ? 'Hide' : 'Show' }} Submission Queue</button>
        <button @click="togglePanel('accept_queue')" :style="isShown('accept_queue') ? 'border-color:red' : ''">{{ isShown('accept_queue') ? 'Hide' : 'Show' }} Accept Queue</button>
      </div>
      <p>&ensp;</p>
      <div class="controller__button-set">
        <button @click="togglePanel('rank')" :style="isShown('rank') ? 'border-color:red' : ''">{{ isShown('rank') ? 'Hide' : 'Show' }} Rank</button>
        <button @click="togglePanel('statistic')" :style="isShown('statistic') ? 'border-color:red' : ''">{{ isShown('statistic') ? 'Hide' : 'Show' }} Statistic</button>
      </div>
      <p>&ensp;</p>
      <div class="controller__button-set">
        <button @click="togglePanel('bottom_bar')" :style="isShown('bottom_bar') ? 'border-color:red' : ''">{{ isShown('bottom_bar') ? 'Hide' : 'Show' }} Bottom Bar</button>
      </div>
      <p>&ensp;</p>
      <div class="controller__button-set">
        <button @click="showAllPanel(true)">Show All</button>
        <button @click="showAllPanel(false)">Hide All</button>
      </div>
    </section>
    <section>
      <h2>Rank</h2>
      <hr>
      <p>&ensp;</p>
      <div class="controller__button-set">
        <button @click="scrollToTop">Scroll to Top</button>
        <button @click="scrollToBottom">Scroll to Bottom</button>
      </div>
      <p>&ensp;</p>
      <div class="controller__button-set">
        <button @click="scrollUp">Scroll Up</button>
        <button @click="scrollDown">Scroll Down</button>
      </div>
    </section>
  </main>
  <footer>&copy;<a href="https://github.com/SamuNatsu" target="_blank">Rainiar</a></footer>
</div>
`;

/* Export component */
export default {
  data() {
    const logo = Vue.ref(window.staticDirectory + 'favicon.png');

    return { ...useCtrlAppStore(), logo };
  },
  mounted() {
    const { cid } = useCtrlAppStore();
    cid.value = new URL(window.location.href).searchParams.get('cid');
  },
  template
};
