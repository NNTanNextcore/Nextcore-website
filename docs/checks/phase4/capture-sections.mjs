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

try {
 await send('Emulation.setDeviceMetricsOverride',{width:1440,height:900,deviceScaleFactor:1,mobile:false});
 await send('Page.navigate',{url:origin+'/docs/checks/phase4/rendered-plugins-menu.html'});await delay(800);
 for(const mode of ['dark','light']){
  await evaluate("NextcoreTheme.set('"+mode+"')");await delay(250);
  for(const id of ['about','services','team','contact']){
   await evaluate("document.getElementById('"+id+"').scrollIntoView()");await delay(200);
   const shot=await send('Page.captureScreenshot',{format:'png',captureBeyondViewport:false});
   await writeFile(new URL('./detail-'+mode+'-'+id+'.png',import.meta.url),Buffer.from(shot.data,'base64'));
  }
 }
 await send('Emulation.setDeviceMetricsOverride',{width:375,height:900,deviceScaleFactor:1,mobile:false});
 await evaluate("document.querySelector('footer').scrollIntoView()");await delay(200);
 const shot=await send('Page.captureScreenshot',{format:'png',captureBeyondViewport:false});
 await writeFile(new URL('./detail-light-footer-375.png',import.meta.url),Buffer.from(shot.data,'base64'));
 console.log('9 section screenshots saved');
}finally{socket.close();}

