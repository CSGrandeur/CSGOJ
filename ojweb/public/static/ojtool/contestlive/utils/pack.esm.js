/// Pack/unpack data module
import pako from '../js/pako.esm.min.js';

export function packData(data) {
  return Base64.fromUint8Array(pako.deflate(JSON.stringify(data)), true);
}

export function unpackData(str) {
  return JSON.parse(pako.inflate(Base64.toUint8Array(str), { to: 'string' }));
}
