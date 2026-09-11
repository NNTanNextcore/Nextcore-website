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

const checks={}; const assert=(name,value)=>{checks[name]=!!value;if(!value)console.log('FAIL: '+name);};
try {
 await send('Emulation.setDeviceMetricsOverride',{width:1440,height:900,deviceScaleFactor:1,mobile:false});
 await send('Emulation.setEmulatedMedia',{features:[{name:'prefers-reduced-motion',value:'no-preference'}]});
 await send('Page.addScriptToEvaluateOnNewDocument',{source:"localStorage.setItem('nextcore-theme-mode','dark');"});
 await send('Page.navigate',{url:origin+'/docs/checks/phase4/rendered-plugins-menu.html'}); await delay(1200);
 await evaluate("document.documentElement.style.scrollBehavior='auto'");
 assert('single_homepage',await evaluate("document.querySelectorAll('main').length===1 && document.querySelectorAll('.hero').length===1"));
 assert('unique_ids',await evaluate("(()=>{let ids=[...document.querySelectorAll('[id]')].map(e=>e.id);return new Set(ids).size===ids.length})()"));
 assert('portal_waits_for_relationship',await evaluate("document.querySelector('.project-card').tagName==='ARTICLE' && !document.querySelector('.project-card a')"));
 assert('other_projects_have_real_links',await evaluate("document.querySelectorAll('a.project-card[href]').length===2"));
 assert('team_contacts',await evaluate("document.querySelectorAll('.team-contacts a[href^=mailto]').length===3 && document.querySelectorAll('.team-contacts a[href^=tel]').length===3"));
 assert('desktop_click_open',await evaluate("(()=>{const b=document.querySelector('.nextcore-desktop-nav .nextcore-submenu-toggle');b.click();b.focus();return b.getAttribute('aria-expanded')==='true'&&!document.getElementById(b.getAttribute('aria-controls')).hidden})()"));
 await send('Input.dispatchKeyEvent',{type:'keyDown',key:'ArrowDown',code:'ArrowDown',windowsVirtualKeyCode:40});
 assert('keyboard_arrow_focus',await evaluate("!!document.activeElement.closest('.sub-menu')"));
 await send('Input.dispatchKeyEvent',{type:'keyDown',key:'Escape',code:'Escape',windowsVirtualKeyCode:27});
 assert('keyboard_escape',await evaluate("document.activeElement.classList.contains('nextcore-submenu-toggle') && document.activeElement.getAttribute('aria-expanded')==='false'"));
 const point=await evaluate("(()=>{const r=document.querySelector('.nextcore-desktop-nav .menu-item-has-children').getBoundingClientRect();return {x:r.x+10,y:r.y+10}})()");
 await send('Input.dispatchMouseEvent',{type:'mouseMoved',...point}); await delay(100);
 assert('desktop_hover_open',await evaluate("document.querySelector('.nextcore-desktop-nav .nextcore-submenu-toggle').getAttribute('aria-expanded')==='true'"));
 const subpoint=await evaluate("(()=>{const b=document.querySelector('.nextcore-desktop-nav .nextcore-submenu-toggle');const r=document.getElementById(b.getAttribute('aria-controls')).getBoundingClientRect();return {x:r.x+20,y:r.y+20}})()");
 await send('Input.dispatchMouseEvent',{type:'mouseMoved',...subpoint});await delay(250);
 assert('submenu_pointer_transfer',await evaluate("document.querySelector('.nextcore-desktop-nav .nextcore-submenu-toggle').getAttribute('aria-expanded')==='true'"));
 await evaluate("document.querySelector('#about').scrollIntoView()");await delay(3500);
 assert('dark_video_only',await evaluate("document.querySelector('video').currentSrc.includes('nextcore-danang.mp4')"));
 assert('video_playing_dark',await evaluate("!document.querySelector('video').paused && document.querySelector('#about').classList.contains('is-playing')"));
 const text=await evaluate("document.querySelector('main').textContent");
 assert('mode_hides_old_frame_immediately',await evaluate("(()=>{NextcoreTheme.set('light');return !document.querySelector('#about').classList.contains('is-playing') && document.querySelector('.hero-image').src.includes('hero-corporate-light')})()"));
 await delay(3500);
 assert('light_video_only',await evaluate("document.querySelector('video').currentSrc.includes('caurongquay-light-video.mp4')"));
 assert('video_playing_light',await evaluate("!document.querySelector('video').paused && document.querySelector('#about').classList.contains('is-playing')"));
 assert('mode_keeps_editorial_dom',text===await evaluate("document.querySelector('main').textContent"));
 assert('light_media_pair',await evaluate("document.querySelector('.company-video-poster').src.includes('cauronglight.png') && document.querySelector('.cta-image').src.includes('cta-light.png')"));
 await evaluate("document.querySelector('#team').scrollIntoView()");await delay(250);
 assert('offscreen_pause',await evaluate("document.querySelector('video').paused"));
 await send('Emulation.setEmulatedMedia',{features:[{name:'prefers-reduced-motion',value:'reduce'}]});await delay(100);
 assert('reduced_motion_no_video_src',await evaluate("!document.querySelector('video').hasAttribute('src')"));
 await evaluate("document.querySelector('.testimonial-more').click()");
 assert('full_quote_modal',await evaluate("document.querySelector('.testimonial-dialog').open && document.querySelector('.testimonial-dialog blockquote').textContent===document.querySelector('.testimonial-card blockquote').textContent"));
 await send('Input.dispatchKeyEvent',{type:'keyDown',key:'Escape',code:'Escape',windowsVirtualKeyCode:27});await delay(100);
 assert('modal_escape',await evaluate("!document.querySelector('.testimonial-dialog').open"));
 await send('Emulation.setDeviceMetricsOverride',{width:375,height:900,deviceScaleFactor:1,mobile:false});
 await evaluate("scrollTo(0,0);document.querySelector('.nextcore-mobile-toggle').click()");
 assert('mobile_menu_open',await evaluate("!document.querySelector('.nextcore-mobile-panel').hidden"));
 assert('mobile_accordion',await evaluate("(()=>{const b=document.querySelector('.nextcore-mobile-panel .nextcore-submenu-toggle');b.click();return b.getAttribute('aria-expanded')==='true'&&b.parentElement.querySelector('a').getAttribute('href').includes('#services')})()"));
 assert('mobile_extra_sections',await evaluate("document.querySelectorAll('.nextcore-mobile-panel a[href$=\\\"#partner\\\"],.nextcore-mobile-panel a[href$=\\\"#testimonials\\\"]').length===2"));
 await evaluate("document.querySelector('.nextcore-mobile-toggle').click();document.querySelector('#testimonials').scrollIntoView();document.querySelector('.testimonial-next').click()");await delay(250);
 assert('carousel_next',await evaluate("document.querySelector('.testimonial-track').scrollLeft>0"));
 assert('carousel_dots',await evaluate("document.querySelectorAll('.testimonial-dots button').length===4"));
 assert('mobile_no_overflow',await evaluate("document.documentElement.scrollWidth<=innerWidth"));
 await send('Page.navigate',{url:origin+'/docs/checks/phase4/rendered-plugins-en-menu.html'});await delay(800);
 assert('english_route',await evaluate("document.documentElement.lang==='en-US'"));
 assert('english_contact_url',await evaluate("document.querySelector('.nextcore-header-cta').getAttribute('href').includes('/en/lien-he/')"));
 assert('language_switch_present',await evaluate("document.querySelectorAll('.nextcore-language-switch a').length===4"));
 await writeFile(new URL('./interaction-results.json',import.meta.url),JSON.stringify({checks,errors,requests:[...new Set(requests)],blocked:[...new Set(blocked)]},null,2));
 console.log(JSON.stringify({checks,errors},null,2));
}finally{socket.close();}
