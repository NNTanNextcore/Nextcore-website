import { readFile, writeFile } from 'node:fs/promises';
import { setTimeout as delay } from 'node:timers/promises';
const origin = 'http://localhost';
const target = await (await fetch('http://127.0.0.1:9223/json/new?about:blank', {method:'PUT'})).json();
const socket = new WebSocket(target.webSocketDebuggerUrl);
await new Promise(r => socket.addEventListener('open', r, {once:true}));
let id=0; const pending=new Map(), errors=[], requests=[], blocked=[];
socket.addEventListener('message', async ({data}) => {
    const m=JSON.parse(data);
    if(m.id){const p=pending.get(m.id);pending.delete(m.id);m.error?p.reject(m.error):p.resolve(m.result);}
    if(m.method==='Runtime.exceptionThrown') errors.push(m.params.exceptionDetails.text+': '+(m.params.exceptionDetails.exception?.description||''));
    if(m.method==='Network.requestWillBeSent') requests.push(m.params.request.url);
    if(m.method==='Fetch.requestPaused') {
        const {request,requestId}=m.params;
        const url=new URL(request.url);
        const allowed=request.method==='GET' && (url.protocol==='data:' || (url.origin===origin && /\.(html|css|js|png|jpe?g|webp|gif|svg|mp4|ttf|woff2?|ico)(\?|$)/i.test(url.pathname+url.search)));
        if(!allowed) blocked.push(request.url);
        await send(allowed?'Fetch.continueRequest':'Fetch.failRequest', allowed?{requestId}:{requestId,errorReason:'BlockedByClient'});
    }
});
function send(method,params={}){return new Promise((resolve,reject)=>{const n=++id;pending.set(n,{resolve,reject});socket.send(JSON.stringify({id:n,method,params}));});}
async function evaluate(expression){const r=await send('Runtime.evaluate',{expression,returnByValue:true,awaitPromise:true});if(r.exceptionDetails)throw Error(JSON.stringify(r.exceptionDetails));return r.result.value;}
await send('Page.enable');await send('Runtime.enable');await send('Network.enable');
await send('Fetch.enable',{patterns:[{urlPattern:'*',requestStage:'Request'}]});
await send('Emulation.setEmulatedMedia',{features:[{name:'prefers-reduced-motion',value:'reduce'}]});

const cases=[];
try {
 await send('Emulation.setScriptExecutionDisabled',{value:true});
 for(const width of [1440,375])for(const mode of ['light','dark']){
  await send('Emulation.setDeviceMetricsOverride',{width,height:900,deviceScaleFactor:1,mobile:false});
  await send('Emulation.setEmulatedMedia',{features:[{name:'prefers-color-scheme',value:mode},{name:'prefers-reduced-motion',value:'reduce'}]});
  await send('Page.navigate',{url:origin+'/docs/checks/phase4/rendered-plugins-menu.html'});await delay(700);
  cases.push(await evaluate(`({width:innerWidth,mode:'${mode}',overflow:document.documentElement.scrollWidth>innerWidth,rootMode:document.documentElement.dataset.theme||null,poster:document.querySelector('noscript picture img').currentSrc,video:document.querySelector('video').getAttribute('src'),heroVisible:getComputedStyle(document.querySelector('noscript picture img')).visibility,headerPosition:getComputedStyle(document.querySelector('header')).position})`));
 }
 await writeFile(new URL('./noscript-results.json',import.meta.url),JSON.stringify(cases,null,2));
 console.log(JSON.stringify(cases,null,2));
}finally{socket.close();}

