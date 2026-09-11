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
  for (const [name,width,height] of [['desktop',1440,900],['tablet',768,1024],['mobile',390,844],['small-mobile',320,720]]) {
    await send('Emulation.setDeviceMetricsOverride', {width,height,deviceScaleFactor:1,mobile:false});
    await send('Page.navigate', {url:'http://localhost/preview/index.html'});
    await delay(700);
    await evaluate("document.querySelector('.hero-image').decode()");
    await delay(700);
    results.push(await evaluate(`({
      width: innerWidth, overflow: document.documentElement.scrollWidth > innerWidth,
      image: document.querySelector('.hero-image').getAttribute('src'),
      natural: [document.querySelector('.hero-image').naturalWidth, document.querySelector('.hero-image').naturalHeight],
      heroHeight: document.querySelector('.hero').offsetHeight,
      title: document.querySelector('#hero-title').innerText,
      slogan: document.querySelector('.hero-slogan').textContent,
      cta: [...document.querySelectorAll('.hero .button-row a')].map(a=>a.textContent.trim()),
      blogImage: document.querySelectorAll('.blog-card img')[1].getAttribute('src')
    })`));
    const shot=await send('Page.captureScreenshot',{format:'png'});
    await writeFile(new URL('./'+name+'-hero.png',import.meta.url),Buffer.from(shot.data,'base64'));
  }
  await writeFile(new URL('./hero-report.json',import.meta.url),JSON.stringify({results,errors},null,2));
  console.log(JSON.stringify({results,errors},null,2));
} finally { await send('Browser.close').catch(()=>{}); socket.close(); }

