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

try {
  await send('Page.enable'); await send('Runtime.enable');
  const darkSource=await readFile(new URL('../index.html',import.meta.url),'utf8');
  const lightSource=await readFile(new URL('../index-light.html',import.meta.url),'utf8');
  const restored=lightSource.replace('<body class="theme-light">','<body>').replace('<meta name="theme-color" content="#ffffff">','<meta name="theme-color" content="#070b0d">').replace(/  <link rel="stylesheet" href="assets\/css\/light.css">\r?\n/,'');
  const sourceIdentical=restored.replace(/\r\n/g,'\n')===darkSource.replace(/\r\n/g,'\n');
  const results=[];
  for(const width of [1440,1200,1024,768,480,375]) {
    const height=width>=1024?900:width===768?1024:844;
    await send('Emulation.setDeviceMetricsOverride',{width,height,deviceScaleFactor:1,mobile:false});
    const variants={};
    for(const variant of ['dark','light']) {
      await send('Page.navigate',{url:'http://localhost/preview/'+(variant==='light'?'index-light.html':'index.html')});
      await delay(700);
      await evaluate(`(async()=>{
        document.documentElement.style.scrollBehavior='auto';
        for(let y=0;y<document.body.scrollHeight;y+=650){scrollTo(0,y);await new Promise(r=>setTimeout(r,45));}
        await Promise.all([...document.images].map(i=>i.decode().catch(()=>{})));
        scrollTo(0,0);
      })()`);
      await delay(750);
      variants[variant]=await evaluate(`(()=>{
        const selectors='header.site-header,main>section,.container,.service-card,.project-card,.team-card,.blog-card,.site-footer,.company-title,.hero-slogan';
        const geometry=[...document.querySelectorAll(selectors)].map(e=>{const r=e.getBoundingClientRect();return [e.id||e.className,...[r.x,r.y+scrollY,r.width,r.height].map(v=>Math.round(v*10)/10)];});
        const menu=document.querySelector('.menu-toggle');let menuWorks=true;
        if(innerWidth<1024){menu.click();menuWorks=menu.getAttribute('aria-expanded')==='true';document.dispatchEvent(new KeyboardEvent('keydown',{key:'Escape'}));menuWorks=menuWorks&&menu.getAttribute('aria-expanded')==='false';}
        const pause=document.querySelector('.marquee-control');pause.click();const pauseWorks=pause.getAttribute('aria-pressed')==='true';pause.click();
        document.querySelector('[data-open="search"]').click();const searchWorks=document.querySelector('#search-dialog').open;document.querySelector('#search-dialog').close();
        return {geometry,overflow:document.documentElement.scrollWidth>innerWidth,missingImages:[...document.images].filter(i=>!i.naturalWidth).map(i=>i.src),menuWorks,pauseWorks,searchWorks,
          marqueeDuration:getComputedStyle(document.querySelector('.marquee-track')).animationDuration,
          assets:[...document.querySelectorAll('img,video source')].map(e=>e.getAttribute('src')),
          links:[...document.querySelectorAll('a')].map(e=>e.getAttribute('href'))};
      })()`);
      if(variant==='light' && [1440,768,375].includes(width)){
        await delay(100);
        const hero=await send('Page.captureScreenshot',{format:'png'});
        await writeFile(new URL('./light-'+width+'-hero.png',import.meta.url),Buffer.from(hero.data,'base64'));
        const h=await evaluate('document.documentElement.scrollHeight');
        const shot=await send('Page.captureScreenshot',{format:'png',captureBeyondViewport:true,clip:{x:0,y:0,width,height:h,scale:1}});
        await writeFile(new URL('./light-'+width+'.png',import.meta.url),Buffer.from(shot.data,'base64'));
      }
    }
    results.push({width,layoutIdentical:JSON.stringify(variants.dark.geometry)===JSON.stringify(variants.light.geometry),
      differences:variants.light.geometry.filter((v,i)=>JSON.stringify(v)!==JSON.stringify(variants.dark.geometry[i])),
      assetsIdentical:JSON.stringify(variants.dark.assets)===JSON.stringify(variants.light.assets),
      linksIdentical:JSON.stringify(variants.dark.links)===JSON.stringify(variants.light.links),
      motionIdentical:variants.dark.marqueeDuration===variants.light.marqueeDuration,
      overflow:variants.light.overflow,missingImages:variants.light.missingImages,
      menuWorks:variants.light.menuWorks,pauseWorks:variants.light.pauseWorks,searchWorks:variants.light.searchWorks});
  }
  await send('Emulation.setEmulatedMedia',{features:[{name:'prefers-reduced-motion',value:'reduce'}]});
  await evaluate("document.querySelector('#about').scrollIntoView()");
  await delay(500);
  const reducedMotion=await evaluate(`({videoPaused:document.querySelector('#about video').paused,marquee:getComputedStyle(document.querySelector('.marquee-track')).animationName,overflow:document.documentElement.scrollWidth>innerWidth})`);
  const report={sourceIdentical,results,reducedMotion,errors};
  await writeFile(new URL('./light-report.json',import.meta.url),JSON.stringify(report,null,2));
  console.log(JSON.stringify(report,null,2));
} finally {await send('Browser.close').catch(()=>{});socket.close();}

