/// Pack/unpack data module
import pako from '../js/pako.esm.min.js';

/**
 * Compress data to Base64 URI-safe string
 *
 * @param {any} data Input data
 * @returns Output string
 */
export function packData(data) {
  return Base64.fromUint8Array(pako.deflate(JSON.stringify(data)), true);
}

/**
 * Decompress data from Base64 URI-safe string
 *
 * @param {string} str Input data
 * @returns Output data
 */
export function unpackData(str) {
  return JSON.parse(pako.inflate(Base64.toUint8Array(str), { to: 'string' }));
}
