/// Send command module
import { useNotification } from '../components/Notification.esm.min.js';
import { packData } from './pack.esm.js';

/**
 * Send command to live overlay
 *
 * @param {any} data Data
 * @returns Is successfully sent
 */
export async function sendCommand(data) {
  const { notify } = useNotification();

  try {
    // Check contest ID
    if (window.csgLiveCid === null) {
      throw Error('Contest ID param lost');
    }

    // Send post
    const res = await csg.post(
      `/ojtool/contestlive/live_command_send_ajax?cid=${window.csgLiveCid}`,
      { live_command: packData(data) }
    );

    // Check send status
    if (!res.ok) {
      throw Error(res.statusText);
    }

    // Check response status
    const rep = await res.json();
    if (rep.code !== 1) {
      throw Error(rep.msg);
    }

    // Success
    return true;
  } catch (e) {
    notify({
      title: 'Fail to send command',
      text: e.message,
      type: 'error'
    });

    // Failed
    return false;
  }
}

/**
 * Start command receiving loop
 * 
 * @param {string} cid Contest ID
 * @param {(data: any) => void} cb Callback function
 */
export function startReceiveCommand(cid, cb) {
  // Start interval
  const command_interval = 1000;
  setInterval(async () => {
    try {
      // Get data
      const res = await csg.get(
        `/ojtool/contestlive/live_command_get_ajax?cid=${cid}`
      );

      // Check receive status
      if (!res.ok) {
        throw Error(res.statusText);
      }

      // Check response status
      const rep = await res.json();
      if (rep.code !== 1) {
        throw Error(rep.msg);
      }

      // Callback
      cb(rep.data);
    } catch (e) {
      // Log error
      console.error(e);
    }
  }, command_interval);
}
