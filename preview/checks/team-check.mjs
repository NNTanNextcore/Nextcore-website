// Local responsive check: node --experimental-websocket preview/checks/team-check.mjs
import { writeFile } from 'node:fs/promises';
import { setTimeout as delay } from 'node:timers/promises';
const targets = await (await fetch('http://127.0.0.1:9223/json/list')).json();
const socket = new WebSocket(targets.find(t => t.type === 'page').webSocketDebuggerUrl);
await new Promise(r => socket.addEventListener('open', r, { once: true }));
let id = 0;
const pending = new Map();
const errors = [];
socket.addEventListener('message', ({ data }) => {
  const m = JSON.parse(data);
  if (m.method === 'Runtime.exceptionThrown') errors.push(m.params.exceptionDetails.text);
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
  await send('Runtime.enable');
  const results = [];
  for (const [name,width,height] of [['desktop',1440,900],['compact-desktop',1024,768],['tablet',768,1024],['mobile',390,844],['small-mobile',320,720]]) {
    await send('Emulation.setDeviceMetricsOverride', { width, height, deviceScaleFactor: 1, mobile: false });
    await send('Page.navigate', { url: 'http://localhost/preview/index.html' });
    await delay(600);
    await evaluate(`document.documentElement.style.scrollBehavior='auto'; window.scrollTo(0, document.querySelector('#team').offsetTop - 82)`);
    await evaluate(`Promise.all([...document.querySelectorAll('#team img')].map(i => i.decode()))`);
    await delay(700);
    const state = await evaluate(`({
      overflow: document.documentElement.scrollWidth > innerWidth,
      columns: getComputedStyle(document.querySelector('#team .team-grid')).gridTemplateColumns,
      cards: [...document.querySelectorAll('#team .team-card')].map(c => ({title: c.querySelector('h3').textContent, height: c.offsetHeight, image: c.querySelector('img').naturalWidth > 0, contacts: c.querySelectorAll('.team-contacts button').length, background: getComputedStyle(c).backgroundColor, role: c.querySelector('p').textContent})),
      overlay: getComputedStyle(document.querySelector('#team .team-card'), '::after').backgroundImage
    })`);
    // Reveal lower cards before a section-only screenshot on narrow screens.
    await evaluate(`(async () => {for(const c of document.querySelectorAll('#team .team-card')) {c.scrollIntoView(); await new Promise(r=>setTimeout(r,120));} window.scrollTo(0,document.querySelector('#team').offsetTop-82);})()`);
    await delay(650);
    const bounds = await evaluate(`(() => {const r=document.querySelector('#team').getBoundingClientRect(); return {x:r.x,y:r.y+scrollY,width:r.width,height:r.height,scale:1};})()`);
    const shot = await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: true, clip: bounds });
    await writeFile(new URL('./'+name+'-team.png', import.meta.url), Buffer.from(shot.data,'base64'));
    results.push({ name, ...state });
  }
  await send('Emulation.setEmulatedMedia', { features:[{name:'prefers-reduced-motion',value:'reduce'}] });
  const reducedMotion = await evaluate(`getComputedStyle(document.querySelector('.team-card img')).transitionDuration`);
  const report = { results, reducedMotion, errors };
  await writeFile(new URL('./team-report.json',import.meta.url),JSON.stringify(report,null,2));
  console.log(JSON.stringify(report,null,2));
} finally { await send('Browser.close').catch(()=>{}); socket.close(); }
