import {writeFile, mkdir} from 'node:fs/promises';
import {browser, delay, urlFor} from './cdp.mjs';
const out=new URL('./technology-icons/',import.meta.url);
await mkdir(out,{recursive:true});
const b=await browser();
const results=[];
try {
 await b.send('Page.navigate',{url:urlFor('/')}); await delay(2000);
 await b.evaluate('document.fonts.ready.then(()=>true)');
 await b.send('Emulation.setEmulatedMedia',{features:[{name:'prefers-reduced-motion',value:'reduce'}]});
 for(const [width,height] of [[1920,1080],[1440,900],[1200,900],[1024,768],[768,1024],[480,900],[375,812],[1920,1440]]) {
  await b.send('Emulation.setDeviceMetricsOverride',{width,height,deviceScaleFactor:1,mobile:false});
  for(const mode of ['light','dark']) {
   await b.evaluate(`window.NextcoreTheme.set('${mode}');document.querySelector('#technology').scrollIntoView({behavior:'instant'})`);
   await delay(350);
   await b.evaluate('Promise.all([...document.querySelectorAll(".tech-node img")].map(i=>i.decode().catch(()=>{}))).then(()=>true)');
   const metrics=await b.evaluate(`(()=>{
    const rect=e=>{const r=e.getBoundingClientRect();return {x:r.x,y:r.y,width:r.width,height:r.height,right:r.right,bottom:r.bottom}};
    const nodes=[...document.querySelectorAll('.tech-node')];
    const parts=nodes.flatMap(n=>[n,n.querySelector('.tech-node-label')]);
    const overlaps=[];
    for(let i=0;i<parts.length;i++)for(let j=i+1;j<parts.length;j++){
     if(parts[i].closest('button')===parts[j].closest('button'))continue;
     const a=rect(parts[i]),b=rect(parts[j]);
     if(Math.min(a.right,b.right)-Math.max(a.x,b.x)>1&&Math.min(a.bottom,b.bottom)-Math.max(a.y,b.y)>1)overlaps.push([parts[i].textContent.trim(),parts[j].textContent.trim()]);
    }
    const labels=nodes.map(n=>({name:n.dataset.techLabel,font:getComputedStyle(n.querySelector('.tech-node-label')).fontSize}));
    const section=rect(document.querySelector('#technology'));
    return {width:innerWidth,mode:document.documentElement.dataset.theme,nodes:nodes.length,images:nodes.filter(n=>n.querySelector('img')?.naturalWidth>0).length,fallbacks:nodes.filter(n=>n.querySelector('.tech-node-wordmark')).map(n=>n.dataset.techLabel),labels,overlaps,overflow:document.documentElement.scrollWidth-innerWidth,iconSize:rect(nodes[0].querySelector('.tech-node-icon')).width,nodeSize:rect(nodes[0]).width,connectors:document.querySelectorAll('.constellation-connectors path').length,connectorsVisible:getComputedStyle(document.querySelector('.constellation-connectors')).display!=='none',section:{x:section.x,y:section.y+scrollY,width:section.width,height:section.height}};
   })()`);
   const geometry=await b.evaluate(`(()=>{const c=document.querySelector('.technology-constellation').getBoundingClientRect();return {height:innerHeight,sectionHeight:document.querySelector('#technology').getBoundingClientRect().height,chrome:parseFloat(getComputedStyle(document.querySelector('#technology')).getPropertyValue('--technology-chrome-height')),internalLines:document.querySelectorAll('.connector--node').length,endpoints:[...document.querySelectorAll('.connector--cluster')].map((p,i)=>{const r=document.querySelectorAll('.tech-cluster')[i].getBoundingClientRect(),e=p.getPointAtLength(p.getTotalLength()),d=document.querySelectorAll('.constellation-signal')[i];return {borderError:Math.abs(Math.hypot((e.x-r.x+c.x-r.width/2)/((r.width-1)/2),(e.y-r.y+c.y-r.height/2)/((r.height-1)/2))-1),dotError:Math.hypot(e.x-d.cx.baseVal.value,e.y-d.cy.baseVal.value)}})}})()`);
   if (!metrics.connectorsVisible) geometry.endpoints=[];
   Object.assign(metrics,geometry);
   if(metrics.overlaps.length || metrics.overflow>0 || metrics.connectors!==6 || geometry.internalLines!==0 || geometry.sectionHeight+1<height-geometry.chrome || geometry.endpoints.some(e=>e.borderError>0.001||e.dotError>0.01)) throw Error('Geometry failed: '+JSON.stringify(metrics));
   const shot=await b.send('Page.captureScreenshot',{format:'png',captureBeyondViewport:true,clip:{...metrics.section,scale:1}});
   await writeFile(new URL(`${width}-${height}-${mode}.png`,out),Buffer.from(shot.data,'base64'));
   results.push(metrics); console.log(JSON.stringify({...metrics,labels:undefined,section:undefined}));
  }
 }
 await b.send('Emulation.setDeviceMetricsOverride',{width:1440,height:1000,deviceScaleFactor:1,mobile:false});
 await b.evaluate("document.querySelector('#technology').scrollIntoView({behavior:'instant'})");await delay(300);
 const point=await b.evaluate("(()=>{const r=document.querySelector('.tech-node--react').getBoundingClientRect();return {x:r.x+r.width/2,y:r.y+r.height/2}})()");
 await b.send('Input.dispatchMouseEvent',{type:'mouseMoved',...point});await delay(200);
 const hover=await b.evaluate("document.querySelectorAll('.constellation-connectors path.is-active').length");
 await b.send('Input.dispatchMouseEvent',{type:'mousePressed',...point,button:'left',clickCount:1});
 await b.send('Input.dispatchMouseEvent',{type:'mouseMoved',x:point.x+100,y:point.y,button:'left',buttons:1});await delay(100);
 const drag=await b.evaluate(`(()=>{const n=document.querySelector('.tech-node--react');return {dragX:n.style.getPropertyValue('--drag-x'),dragY:n.style.getPropertyValue('--drag-y'),dragging:n.classList.contains('is-dragging'),path:document.querySelectorAll('.connector--cluster')[3].getAttribute('d')}})()`);
 await b.send('Input.dispatchMouseEvent',{type:'mouseReleased',x:point.x+100,y:point.y,button:'left',clickCount:1});await delay(600);
 const released=await b.evaluate("(()=>{const n=document.querySelector('.tech-node--react');return {x:n.style.getPropertyValue('--drag-x'),dragging:n.classList.contains('is-dragging')}})()");
 await b.evaluate("document.querySelector('.tech-node--python').click()");
 const active=await b.evaluate("document.querySelector('.tech-node--python').classList.contains('is-active')");
 await b.send('Input.dispatchKeyEvent',{type:'keyDown',key:'Escape',code:'Escape'});
 const cleared=await b.evaluate("!document.querySelector('.tech-node.is-active')");
 const interactions={hover,drag,released,active,cleared,errors:b.errors,failed:b.failed};
 console.log(JSON.stringify(interactions));
 await writeFile(new URL('results.json',out),JSON.stringify({results,interactions},null,2));
}finally{await b.close()}
