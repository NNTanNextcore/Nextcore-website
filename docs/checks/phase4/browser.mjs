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
const results=[];
try {
    for(const width of [1440,1200,1024,768,480,375]){
        for(const mode of ['dark','light']){
            await send('Emulation.setDeviceMetricsOverride',{width,height:900,deviceScaleFactor:1,mobile:false});
            await send('Page.addScriptToEvaluateOnNewDocument',{source:`localStorage.setItem('nextcore-theme-mode','${mode}');`});
            await send('Page.navigate',{url:origin+'/docs/checks/phase4/rendered-plugins-menu.html'});
            await delay(1100);
            await evaluate('document.fonts.ready');
            await evaluate(`(async()=>{document.documentElement.style.scrollBehavior='auto';for(let y=0;y<document.body.scrollHeight;y+=700){scrollTo(0,y);await new Promise(r=>setTimeout(r,35));}scrollTo(0,0);})()`);
            await delay(200);
            const metrics=await evaluate(`(()=>{
                const size=e=>{const r=e.getBoundingClientRect();return {width:r.width,height:r.height,x:r.x,y:r.y}};
                return {
                    width:innerWidth,mode:document.documentElement.dataset.theme,overflow:document.documentElement.scrollWidth-innerWidth,
                    sections:[...document.querySelectorAll('main>section')].map(e=>({id:e.id,...size(e)})),
                    font:getComputedStyle(document.querySelector('.company-title')).fontFamily,
                    hero:document.querySelector('.hero-image').getAttribute('src'),
                    video:document.querySelector('video').getAttribute('src'),
                    poster:document.querySelector('.company-video-poster').getAttribute('src'),
                    broken:[...document.images].filter(i=>i.src&&!i.closest('noscript')&&(!i.complete||i.naturalWidth===0)).map(i=>i.src),
                    menu: [...document.querySelectorAll('.nextcore-desktop-nav a')].map(e=>({text:e.textContent,...size(e)})),
                    cards:{services:document.querySelectorAll('.service-card').length,projects:document.querySelectorAll('.project-card').length,team:document.querySelectorAll('.team-card').length,blog:document.querySelectorAll('.blog-card').length},
                    contacts:[...document.querySelectorAll('.team-contacts a')].map(e=>e.getAttribute('href'))
                };
            })()`);
            results.push(metrics);
            const shot=await send('Page.captureScreenshot',{format:'png',captureBeyondViewport:true});
            await writeFile(new URL(`./${mode}-${width}.png`,import.meta.url),Buffer.from(shot.data,'base64'));
            await send('Page.navigate',{url:origin+'/preview/index'+(mode==='light'?'-light':'')+'.html'});
            await delay(500);
            await evaluate('document.fonts.ready');
            metrics.reference=await evaluate(`[...document.querySelectorAll('main>section')].map(e=>{const r=e.getBoundingClientRect();return {id:e.id,width:r.width,height:r.height,x:r.x,y:r.y}})`);
        }
    }
    await writeFile(new URL('./browser-results.json',import.meta.url),JSON.stringify({results,errors,requests:[...new Set(requests)],blocked:[...new Set(blocked)]},null,2));
    console.log(JSON.stringify({cases:results.length,overflows:results.filter(r=>r.overflow>1).map(r=>[r.width,r.mode,r.overflow]),broken:results.flatMap(r=>r.broken),errors},null,2));
} finally {socket.close();}
