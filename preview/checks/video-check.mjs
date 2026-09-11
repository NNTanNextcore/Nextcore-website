// Local Chrome CDP check and poster extraction from the supplied video.
import { writeFile } from 'node:fs/promises';
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
  await send('Page.navigate', { url: 'http://localhost/preview/index.html' });
  await delay(1000);
  const frame = await evaluate(`new Promise((resolve, reject) => {
    const v = document.createElement('video'); v.muted = true; v.preload = 'auto';
    const timeout = setTimeout(() => reject(new Error('Video decode timeout')), 15000);
    v.onloadedmetadata = () => { v.currentTime = Math.min(1, v.duration / 2); };
    v.onerror = () => { clearTimeout(timeout); reject(new Error('Cannot decode video')); };
    v.onseeked = () => {
      const canvas = document.createElement('canvas'); canvas.width = v.videoWidth; canvas.height = v.videoHeight;
      canvas.getContext('2d').drawImage(v, 0, 0);
      clearTimeout(timeout);
      resolve({ width: v.videoWidth, height: v.videoHeight, duration: v.duration, image: canvas.toDataURL('image/jpeg', .9) });
      v.removeAttribute('src'); v.load();
    };
    v.src = 'assets/video/nextcore-danang.mp4';
  })`);
  await writeFile(new URL('../assets/images/danang-poster.jpg', import.meta.url), Buffer.from(frame.image.split(',')[1], 'base64'));
  const results = [];
  for (const [name, width, height] of [['desktop',1440,900],['mobile',390,844],['small-mobile',320,720]]) {
    await send('Emulation.setDeviceMetricsOverride', { width, height, deviceScaleFactor: 1, mobile: false });
    await send('Page.navigate', { url: 'http://localhost/preview/index.html' });
    await delay(600);
    await evaluate(`document.documentElement.style.scrollBehavior='auto'; window.scrollTo(0, document.querySelector('#about').offsetTop - 74)`);
    await delay(1800);
    const state = await evaluate(`(() => {
      const s = document.querySelector('#about'), v = s.querySelector('video');
      return { playing: s.classList.contains('is-playing'), paused: v.paused, muted: v.muted, loop: v.loop, playsInline: v.playsInline, controls: v.controls,
        height: s.offsetHeight, overflow: document.documentElement.scrollWidth > innerWidth,
        order: [...document.querySelectorAll('main > section')].slice(0,3).map(e => e.id || 'stats') };
    })()`);
    const shot = await send('Page.captureScreenshot', { format: 'png' });
    await writeFile(new URL('./' + name + '-video.png', import.meta.url), Buffer.from(shot.data, 'base64'));
    await send('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
    await delay(200);
    state.reducedMotion = await evaluate(`({ paused: document.querySelector('#about video').paused, posterVisible: !document.querySelector('#about').classList.contains('is-playing') })`);
    await send('Emulation.setEmulatedMedia', { features: [] });
    results.push({ name, ...state });
  }
  await send('Page.addScriptToEvaluateOnNewDocument', { source: 'HTMLMediaElement.prototype.play = function () { return Promise.reject(new DOMException("Blocked for test", "NotAllowedError")); };' });
  await send('Page.navigate', { url: 'http://localhost/preview/index.html' });
  await delay(800);
  await evaluate(`document.querySelector('#about').scrollIntoView()`);
  await delay(800);
  const fallback = await evaluate(`({ posterVisible: !document.querySelector('#about').classList.contains('is-playing'), height: document.querySelector('#about').offsetHeight })`);
  const report = { video: { width: frame.width, height: frame.height, duration: frame.duration }, results, blockedAutoplay: fallback };
  await writeFile(new URL('./video-report.json', import.meta.url), JSON.stringify(report, null, 2));
  console.log(JSON.stringify(report, null, 2));
} finally { socket.close(); }
