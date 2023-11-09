/// Send command module
import { useNotification } from '../components/Notification.esm.min.js';
import { packData } from './pack.esm.js';

export async function sendCommand(data, cid) {
  const { notify } = useNotification();

  try {
    if (cid === '') {
      throw Error('Contest ID empty');
    }

    const res = await csg.post(
      `/ojtool/contestlive/live_command_send_ajax?cid=${cid}`,
      { live_command: packData(data) }
    );

    if (!res.ok) {
      throw Error(res.statusText);
    }

    const rep = await res.json();
    if (rep.code !== 1) {
      throw Error(rep.msg);
    }

    return true;
  } catch (e) {
    notify({
      title: 'Fail to send command',
      text: e.message,
      type: 'error'
    });

    return false;
  }
}

export function startReceiveCommand(cid, cb) {
  setInterval(async () => {
    try {
      const res = await csg.get(
        `/ojtool/contestlive/live_command_get_ajax?cid=${cid}`
      );
      if (!res.ok) {
        throw Error(res.statusText);
      }

      const rep = await res.json();
      if (rep.code !== 1) {
        throw Error(rep.msg);
      }

      cb(rep.data);
    } catch (e) {
      console.error(e);
    }
  }, 500);
}
