import { readFile, writeFile } from 'node:fs/promises';
import { setTimeout as delay } from 'node:timers/promises';
const targets = await (await fetch('http://127.0.0.1:9223/json/list')).json();
const socket = new WebSocket(targets.find(t => t.type === 'page').webSocketDebuggerUrl);
await new Promise(r => socket.addEventListener('open', r, { once: true }));
let id = 0;
const pending = new Map();
socket.addEventListener('message', ({ data }) => {
  const m = JSON.parse(data);
  if (m.id) { const p = pending.get(m.id); pending.delete(m.id); m.error ? p.reject(m.error) : p.resolve(m.result); }
});
function send(method, params = {}) {
  return new Promise((resolve, reject) => { const n = ++id; pending.set(n, { resolve, reject }); socket.send(JSON.stringify({ id: n, method, params })); });
}
async function evaluate(expression) {
  const r = await send('Runtime.evaluate', { expression, awaitPromise: true, returnByValue: true });
  if (r.exceptionDetails) throw new Error(JSON.stringify(r.exceptionDetails));
  return r.result.value;
}
try {
  await send('Page.enable');
  await send('Page.navigate', { url: 'http://localhost/preview/index-light.html' });
  await delay(1000);
  // Use the supplied poster; verification must not regenerate image assets.
  const results = [];
  for (const width of [1440, 375]) {
    await send('Emulation.setDeviceMetricsOverride', { width, height: 900, deviceScaleFactor: 1, mobile: false });
    await send('Page.navigate', { url: 'http://localhost/preview/index-light.html' });
    await delay(800);
    await evaluate(`document.documentElement.style.scrollBehavior='auto'; document.querySelector('#about').scrollIntoView()`);
    await delay(1500);
    const state = await evaluate(`(() => {
      const s = document.querySelector('#about'), v = s.querySelector('video');
      v.dispatchEvent(new Event('waiting'));
      return { src: v.currentSrc, poster: v.poster, background: getComputedStyle(s).backgroundImage, playing: !v.paused, bufferingVisibility: getComputedStyle(v).visibility };
    })()`);
    await evaluate(`document.querySelector('#about video').currentTime = document.querySelector('#about video').duration - .25`);
    await delay(1500);
    state.loop = await evaluate(`({ time: document.querySelector('#about video').currentTime, visible: getComputedStyle(document.querySelector('#about video')).visibility })`);
    const shot = await send('Page.captureScreenshot', { format: 'png' });
    await writeFile('preview/checks/light-video-' + width + '.png', Buffer.from(shot.data, 'base64'));
    await send('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
    await delay(200);
    state.reducedMotion = await evaluate(`({ paused: document.querySelector('#about video').paused, visible: getComputedStyle(document.querySelector('#about video')).visibility, background: getComputedStyle(document.querySelector('#about')).backgroundImage })`);
    await send('Emulation.setEmulatedMedia', { features: [] });
    results.push({ width, ...state });
  }
  await writeFile('preview/checks/light-video-report.json', JSON.stringify(results, null, 2));
  console.log(JSON.stringify(results, null, 2));
} finally { socket.close(); }
