// Local responsive check: node --experimental-websocket preview/checks/team-check.mjs
import { writeFile, readFile } from 'node:fs/promises';
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

const results=[];
await send('Page.enable'); await send('Runtime.enable');
for(const width of [1440,1200,1024,768,480,375]) {
 const variants=[];
 for(const file of ['index.html','index-light.html']) {
  await send('Emulation.setDeviceMetricsOverride',{width,height:900,deviceScaleFactor:1,mobile:false});
  await send('Page.navigate',{url:'http://localhost/preview/'+file}); await delay(500);
  await evaluate("document.querySelector('#testimonials').scrollIntoView()"); await delay(700);
  const info=await evaluate(`(()=>{const s=document.querySelector('#testimonials'),t=s.querySelector('.testimonial-track'),c=t.firstElementChild;return {width:s.offsetWidth,height:s.offsetHeight,cardWidth:c.offsetWidth,cards:t.children.length,previous:s.previousElementSibling.id,overflow:document.documentElement.scrollWidth>innerWidth,images:[...s.querySelectorAll('img')].every(i=>i.complete&&i.naturalWidth>0)}})()`);
  await evaluate("document.querySelector('.testimonial-next').click()"); await delay(600);
  info.nextWorks=await evaluate("document.querySelector('.testimonial-track').scrollLeft>0");
  await evaluate("document.querySelector('.testimonial-more').click()");
  info.modalWorks=await evaluate("document.querySelector('.testimonial-dialog').open && document.querySelector('.testimonial-dialog blockquote').textContent===document.querySelector('.testimonial-card blockquote').textContent");
  await evaluate("document.querySelector('.testimonial-close').click()");
  variants.push(info);
  if([1440,375].includes(width)){const shot=await send('Page.captureScreenshot',{format:'png'});await writeFile(new URL('./testimonials-'+file+'-'+width+'.png',import.meta.url),Buffer.from(shot.data,'base64'));}
 }
 results.push({width,variants,matchingLayout:JSON.stringify(variants[0])===JSON.stringify(variants[1])});
}
console.log(JSON.stringify({results,errors},null,2));
await writeFile(new URL('./testimonials-report.json',import.meta.url),JSON.stringify({results,errors},null,2));
await send('Browser.close');socket.close();