import {writeFile} from 'node:fs/promises';
import {setTimeout as delay} from 'node:timers/promises';
const target=(await(await fetch('http://127.0.0.1:9223/json/list')).json()).find(t=>t.url.includes('/docs/checks/phase4/'));
const ws=new WebSocket(target.webSocketDebuggerUrl);await new Promise(r=>ws.addEventListener('open',r,{once:true}));
let id=0;const tasks=new Map();ws.onmessage=({data})=>{const m=JSON.parse(data);if(m.id){const p=tasks.get(m.id);tasks.delete(m.id);m.error?p.reject(m.error):p.resolve(m.result)}};
function send(method,params={}){return new Promise((resolve,reject)=>{const n=++id;tasks.set(n,{resolve,reject});ws.send(JSON.stringify({id:n,method,params}));});}
await send('Emulation.setEmulatedMedia',{features:[{name:'prefers-reduced-motion',value:'reduce'}]});
if(process.argv[3]) await send('Emulation.setDeviceMetricsOverride',{width:Number(process.argv[3]),height:900,deviceScaleFactor:1,mobile:false});
await send('Page.reload');
await delay(1200);
const expression=process.argv[2]||`[...document.querySelectorAll('body *')].map(e=>({tag:e.tagName,cls:e.className?.baseVal??e.className,text:e.textContent.slice(0,30),x:e.getBoundingClientRect().x,right:e.getBoundingClientRect().right,width:e.getBoundingClientRect().width})).filter(e=>e.right>innerWidth+2&&e.width>0).slice(0,35)`;
console.log(JSON.stringify((await send('Runtime.evaluate',{expression,returnByValue:true,awaitPromise:true})).result.value,null,2));
await send('Runtime.evaluate',{expression:'scrollTo(0,0)'});
const shot=await send('Page.captureScreenshot',{format:'png',captureBeyondViewport:false});
await writeFile(new URL('./viewport.png',import.meta.url),Buffer.from(shot.data,'base64'));ws.close();
