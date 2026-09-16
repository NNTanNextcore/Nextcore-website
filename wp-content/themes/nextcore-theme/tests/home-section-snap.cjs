/* Run against a dedicated Chrome debugging instance with Node 20 --experimental-websocket.
 * NC_CDP_URL defaults to http://127.0.0.1:9223; NC_SITE_URL overrides the local homepage.
 * Diagnostics are stored in the OS temporary directory, never in the theme.
 */
const fs=require('fs');
const path=require('path');
const output=path.join(require('os').tmpdir(),'nextcore-snap-qa');
fs.mkdirSync(output,{recursive:true});
const sleep=ms=>new Promise(resolve=>setTimeout(resolve,ms));
async function connect() {
 const endpoint = process.env.NC_CDP_URL || 'http://127.0.0.1:9223';
 const page = await fetch(endpoint + '/json/new?about:blank', {method:'PUT'}).then(r=>r.json());
 const ws = new WebSocket(page.webSocketDebuggerUrl);
 await new Promise(r=>ws.addEventListener('open',r,{once:true}));
 let id=0; const pending=new Map();
 ws.addEventListener('message',e=>{const m=JSON.parse(e.data); if(pending.has(m.id)){const p=pending.get(m.id);pending.delete(m.id);m.error?p.reject(m.error):p.resolve(m.result);}});
 const call=(method,params={})=>new Promise((resolve,reject)=>{pending.set(++id,{resolve,reject});ws.send(JSON.stringify({id,method,params}));});
 const evaluate=async expression=>{const r=await call('Runtime.evaluate',{expression,returnByValue:true,awaitPromise:true});if(r.exceptionDetails)throw Error(JSON.stringify(r.exceptionDetails));return r.result.value;};
 return {call,evaluate,close:async()=>{ws.close();await fetch(endpoint+'/json/close/'+page.id);}};
}
(async()=>{
 const c=await connect(),ev=c.evaluate,results=[];
 const check=(name,pass,data)=>{results.push({name,pass,data});console.log((pass?'PASS ':'FAIL ')+name+' '+JSON.stringify(data??''));};
 await c.call('Page.enable');await c.call('Emulation.setDeviceMetricsOverride',{width:1440,height:900,deviceScaleFactor:1,mobile:false});await c.call('Emulation.setTouchEmulationEnabled',{enabled:false});
 await c.call('Page.navigate',{url:process.env.NC_SITE_URL || 'http://localhost/nextcore-website/'});await sleep(1800);await c.call('Page.reload',{ignoreCache:true});await sleep(1800);
 await ev("document.documentElement.setAttribute('data-nc-snap-debug','');window.snapTrace=[];window.allSnapTraces=[];document.addEventListener('nc:snap-debug',e=>snapTrace.push(e.detail))");
 const jump=async y=>{await ev(`dispatchEvent(new PointerEvent('pointerdown'));scrollTo({top:${y},behavior:'instant'});allSnapTraces.push(snapTrace);snapTrace=[]`);await sleep(230);};
 const wheel=async(d,selector='body',mode=0)=>ev(`(()=>{let e=new WheelEvent('wheel',{deltaY:${d},deltaMode:${mode},clientX:15,clientY:300,bubbles:true,cancelable:true});document.querySelector('${selector}').dispatchEvent(e);return e.defaultPrevented})()`);
 const trace=()=>ev('snapTrace');
 const summary=async()=>{let t=await trace();return {starts:t.filter(x=>x.reason==='start'),complete:t.filter(x=>x.reason==='complete'),frames:t.filter(x=>x.reason==='frame')};};
 await jump(0);for(let i=0;i<4;i++)await wheel(10);check('Collect 40px without native pre-scroll',await ev('scrollY===0')&&(await summary()).starts.length===0);await wheel(5);await sleep(1300);let t=await summary();
 check('45px starts exactly one animation',t.starts.length===1&&t.complete.length===1,t.starts);
 check('One screen duration 800ms',Math.abs(t.starts[0].duration-800)<2);
 check('Downward frame positions monotonic',t.frames.every((f,i,a)=>!i||f.y>=a[i-1].y));
 check('No stationary plateau before last 90ms',!t.frames.some((f,i,a)=>i>4&&f.time<t.complete[0].time-90&&f.y===a[i-4].y));
 for(const delay of [90,450,970]){
  await jump(0);await wheel(90);await sleep(delay);let before=await ev('scrollY');await wheel(-45);let immediate=await ev('scrollY');await sleep(1350);t=await summary();check('Reverse at '+delay+'ms',t.starts.length===2&&await ev('scrollY===0')&&Math.abs(immediate-t.starts[1].from)<5,{before,immediate,starts:t.starts});
 }
 await jump(0);await wheel(90);await sleep(300);await wheel(-10);await sleep(30);await wheel(-10);await sleep(1100);check('Reverse noise ignored',(await summary()).starts.length===1);
 await jump(0);await wheel(90);await sleep(200);await wheel(-25);await sleep(180);await wheel(-25);await sleep(1100);check('Reverse threshold limited to 160ms',(await summary()).starts.length===1);
 await jump(0);await wheel(90);await sleep(250);await wheel(90,'.company-more');await sleep(350);await wheel(90,'[data-tech-node]');await sleep(700);t=await summary();check('Content crossing stationary cursor does not cancel',t.starts.length===1&&t.complete.length===1);
 await jump(0);for(let i=0;i<4;i++){await wheel(10);await sleep(30);}await sleep(230);await wheel(10);check('Intent resets after 200ms',(await summary()).starts.length===0);
 let about=await ev("document.getElementById('about').offsetTop-82");await ev("document.getElementById('technology').style.minHeight=(innerHeight+300)+'px'");await sleep(50);let techInterior=await ev("document.getElementById('technology').offsetTop-82+50");await jump(techInterior);await wheel(90);await ev("scrollTo({top:document.getElementById('technology').offsetTop+document.getElementById('technology').offsetHeight-innerHeight,behavior:'instant'})");await wheel(90);check('Native gesture cannot become snap at boundary',(await summary()).starts.length===0);await sleep(230);await wheel(45);await sleep(1350);check('Fresh gesture snaps at tall bottom',(await summary()).starts.length===1);await ev("document.getElementById('technology').style.removeProperty('min-height')");await sleep(230);
 // Short remaining distance to an anchor uses the same engine but a shorter duration.
 let tech=await ev("document.getElementById('technology').offsetTop-82");await jump(tech-80);await ev("document.querySelector('.nextcore-site-header a[href$=\"#technology\"]').click()");await sleep(600);t=await summary();check('Short 80px transition scales duration',t.starts.length===1&&t.starts[0].duration<400&&t.complete.length===1,t.starts);
 await ev('snapTrace=[]');await ev("document.querySelector('.nextcore-site-header a[href$=\"#technology\"]').click()");await sleep(50);check('Identical destination has no animation or lock',(await summary()).starts.length===0&&await ev("snapTrace.some(e=>e.reason==='no-op')"));
 await jump(0);await wheel(90);for(let i=0;i<20;i++){await sleep(75);await wheel(90);}t=await trace();check('Continuous input not locked indefinitely',t.some(x=>x.reason==='unlock')&&t.filter(x=>x.reason==='start').length>1,t.filter(x=>x.reason!=='frame'));
 // A continuous upward gesture must retain snap animation across the slightly
 // oversized Technology scene instead of falling back to native scrolling.
 let partner=await ev("document.getElementById('partner').offsetTop-82");
 let upwardTargets=await ev("['projects','technology','services'].map(id=>{let n=document.getElementById(id);return Math.max(n.offsetTop-82,n.offsetTop+n.offsetHeight-innerHeight)})");
 await jump(partner);for(let i=0;i<60;i++){await wheel(-90);await sleep(75);}await sleep(1350);t=await trace();
 let upwardStarts=t.filter(x=>x.reason==='start');check('Continuous Partner -> Projects -> Technology -> Services stays animated',upwardStarts.length>=3&&upwardTargets.every((target,index)=>Math.abs(upwardStarts[index].target-target)<2)&&!t.some(x=>x.reason==='native-gesture'&&x.time<upwardStarts[2].time),t.filter(x=>x.reason!=='frame'));
 // Vertical wheel gestures over interactive homepage visuals still belong to the
 // page. Horizontal carousel gestures and active Technology drags remain native.
 let projects=await ev("document.getElementById('projects').offsetTop-82");
 await jump(tech);let prevented=await wheel(90,'[data-tech-node]');await sleep(1350);check('Technology node wheel snaps to Projects',prevented&&Math.abs(await ev('scrollY')-projects)<2);
 let testimonials=await ev("document.getElementById('testimonials').offsetTop-82"),team=await ev("document.getElementById('team').offsetTop-82");
 await jump(testimonials);prevented=await wheel(90,'.testimonial-carousel');await sleep(1350);check('Testimonial carousel vertical wheel snaps to Team',prevented&&Math.abs(await ev('scrollY')-team)<2);
 let stats=await ev("document.querySelector('.stats').offsetTop-82");
 await jump(about);prevented=await wheel(90,'.company-video-media');await sleep(1350);t=await summary();check('About video wheel animates to independent Statistics scene',prevented&&t.starts.length===1&&t.complete.length===1&&Math.abs(await ev('scrollY')-stats)<2,t.starts);
 // Every logical scene can be traversed in both directions without stale indices.
 const scenes=await ev("[...document.querySelectorAll('[data-nc-snap-section]:not([data-nc-snap-group])')].map(n=>({id:n.id||(n.classList.contains('stats')?'stats':'footer'),top:n.getBoundingClientRect().top+scrollY,bottom:Math.max(...[n,...document.querySelectorAll('[data-nc-snap-group=\"'+n.id+'\"]')].map(e=>e.getBoundingClientRect().bottom+scrollY))}))");
 for(let i=0;i<scenes.length-1;i++){
  let a=scenes[i],b=scenes[i+1];let from=Math.max(0,a.top-82,a.bottom-900);await jump(from);await wheel(90);await sleep(1350);let expected=Math.min(await ev('document.documentElement.scrollHeight-innerHeight'),b.top-82);check('Down '+a.id+' -> '+b.id,Math.abs(await ev('scrollY')-expected)<2);
  await jump(Math.max(0,b.top-82));await wheel(-90);await sleep(1350);check('Up '+b.id+' -> '+a.id,Math.abs(await ev('scrollY')-from)<2);
 }
 fs.writeFileSync(path.join(output,'motion-results.json'),JSON.stringify(results,null,2));fs.writeFileSync(path.join(output,'frame-traces.json'),JSON.stringify(await ev('[...allSnapTraces,snapTrace]'),null,2));await c.close();if(results.some(r=>!r.pass)){process.exitCode=1;}console.log('TOTAL '+results.filter(r=>r.pass).length+'/'+results.length);
})().catch(e=>{console.error(e);process.exit(1)});
