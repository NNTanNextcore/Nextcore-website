import {writeFile} from 'node:fs/promises';
import {browser,delay,urlFor} from './cdp.mjs';
const b=await browser(),report=[];
const key=async key=>{const windowsVirtualKeyCode=key==='Escape'?27:key==='ArrowDown'?40:9;await b.send('Input.dispatchKeyEvent',{type:'keyDown',key,code:key,windowsVirtualKeyCode});await b.send('Input.dispatchKeyEvent',{type:'keyUp',key,code:key,windowsVirtualKeyCode});await delay(100)};
const load=async(route,width=1440)=>{await b.send('Emulation.setDeviceMetricsOverride',{width,height:900,deviceScaleFactor:1,mobile:false});b.errors.length=0;await b.send('Page.navigate',{url:urlFor(route)});await delay(2000)};
try {
 for(const route of ['/','/en/','/danh-muc-dich-vu/tu-van-doanh-nghiep/','/en/danh-muc-dich-vu/tu-van-doanh-nghiep/']) {
  await load(route);const r={route,checks:{}};
  await b.evaluate("document.querySelector('.nextcore-desktop-nav .nextcore-submenu-toggle').focus()");await key('ArrowDown');
  r.checks.arrow_down_focus=await b.evaluate("document.querySelector('.nextcore-desktop-nav .sub-menu').contains(document.activeElement)&&document.querySelector('.nextcore-desktop-nav .nextcore-submenu-toggle').getAttribute('aria-expanded')==='true'");
  await key('Escape');r.checks.escape_focus=await b.evaluate("document.activeElement.matches('.nextcore-submenu-toggle')&&document.activeElement.getAttribute('aria-expanded')==='false'");
  const point=await b.evaluate("(()=>{let r=document.querySelector('.nextcore-desktop-nav .menu-item-has-children').getBoundingClientRect();return {x:r.x+r.width/2,y:r.y+r.height/2}})()");
  await b.send('Input.dispatchMouseEvent',{type:'mouseMoved',...point});await delay(150);
  r.checks.hover=await b.evaluate("document.querySelector('.nextcore-desktop-nav .nextcore-submenu-toggle').getAttribute('aria-expanded')==='true'");
  const subpoint=await b.evaluate("(()=>{let r=document.querySelector('.nextcore-desktop-nav .sub-menu a').getBoundingClientRect();return {x:r.x+20,y:r.y+r.height/2}})()");
  await b.send('Input.dispatchMouseEvent',{type:'mouseMoved',...subpoint});await delay(250);
  r.checks.pointer_transfer=await b.evaluate("!document.querySelector('.nextcore-desktop-nav .sub-menu').hidden");
  await b.evaluate("document.querySelector('.nextcore-desktop-nav .nextcore-submenu-toggle').click()");
  r.checks.click_close=await b.evaluate("document.querySelector('.nextcore-desktop-nav .sub-menu').hidden");
  await b.evaluate("document.querySelector('[data-nextcore-search]').click()");
  r.search=await b.evaluate("({open:document.querySelector('.nextcore-search-dialog').open,label:document.querySelector('#nextcore-search-title').textContent,action:document.querySelector('.nextcore-search-dialog form').action,input:document.querySelector('.nextcore-search-dialog input[type=search]').placeholder})");
  await key('Escape');r.checks.search_escape=await b.evaluate("!document.querySelector('.nextcore-search-dialog').open");
  r.links=await b.evaluate("[...document.querySelectorAll('.nextcore-desktop-nav a')].map(e=>({text:e.textContent,href:e.href}))");
  r.languages=await b.evaluate("[...document.querySelectorAll('.nextcore-desktop-actions .nextcore-language-switch a')].map(e=>({text:e.textContent,href:e.href}))");
  r.checks.switch_theme=await b.evaluate("(()=>{let old=NextcoreTheme.get();document.querySelector('.nextcore-desktop-actions .nextcore-theme-switch').click();return NextcoreTheme.get()!==old})()");
  await b.send('Emulation.setDeviceMetricsOverride',{width:375,height:900,deviceScaleFactor:1,mobile:false});await delay(250);
  await b.evaluate("document.querySelector('.nextcore-mobile-toggle').click()");
  r.checks.mobile_open=await b.evaluate("!document.querySelector('.nextcore-mobile-panel').hidden");
  await b.evaluate("let e=document.querySelector('.nextcore-mobile-panel .nextcore-submenu-toggle');e.focus();e.click()");
  r.checks.mobile_accordion=await b.evaluate("!document.querySelector('.nextcore-mobile-panel .sub-menu').hidden");
  r.mobile=await b.evaluate("({links:[...document.querySelectorAll('.nextcore-mobile-panel a')].map(e=>({text:e.textContent,href:e.href})),themeSwitches:document.querySelectorAll('.nextcore-mobile-panel .nextcore-theme-switch').length,languages:document.querySelectorAll('.nextcore-mobile-panel .nextcore-language-switch').length})");
  await key('Escape');await key('Escape');
  r.checks.mobile_escape_focus=await b.evaluate("document.querySelector('.nextcore-mobile-panel').hidden&&document.activeElement.matches('.nextcore-mobile-toggle')");
  if(route==='/'||route==='/en/') {
   await b.evaluate("document.querySelector('.testimonial-card button').click()");
   await delay(800);
   r.testimonial=await b.evaluate("({open:document.querySelector('.testimonial-dialog').open,text:document.querySelector('.testimonial-dialog').innerText})");
   await key('Escape');r.checks.testimonial_escape=await b.evaluate("!document.querySelector('.testimonial-dialog').open");
   await b.evaluate("document.querySelector('.testimonial-next').click()");await delay(700);
   r.checks.testimonial_next=await b.evaluate("document.querySelector('.testimonial-track').scrollLeft>0");
  }
  r.errors=[...b.errors];report.push(r);console.log(route,JSON.stringify(r.checks));
 }
 for(const route of ['/dich-vu/ung-dung-dat-san/','/dich-vu/wordpress-aff-atv/','/outsource/','/ve-chung-toi/']) {
  await load(route);const r={route,builder:true};
  await b.evaluate("window.scrollTo(0,document.body.scrollHeight/2)");await delay(1000);
  r.widgets=await b.evaluate("[...document.querySelectorAll('[data-widget_type]')].map(e=>e.dataset.widget_type)");
  r.swipers=await b.evaluate("[...document.querySelectorAll('.swiper,.swiper-container')].map(e=>({initialized:!!e.swiper,slides:e.querySelectorAll('.swiper-slide').length,index:e.swiper?.activeIndex}))");
  r.carousel=await b.evaluate("(()=>{let e=[...document.querySelectorAll('.elementor-swiper-button-next')].find(e=>e.getClientRects().length);let s=[...document.querySelectorAll('.swiper,.swiper-container')].map(e=>e.swiper).find(Boolean);if(!s)return null;let before=s.activeIndex;if(e)e.click();else s.slideNext();return {method:e?'arrow-click':'swiper-slideNext (no visible arrow)',before,after:s.activeIndex}})()");
  r.toggle=await b.evaluate("(()=>{let e=document.querySelector('.elementor-tab-title');if(!e)return null;let before=e.getAttribute('aria-expanded');e.click();return {before,after:e.getAttribute('aria-expanded')}})()");
  r.lightboxLink=await b.evaluate("(()=>{let e=document.querySelector('a[data-elementor-open-lightbox=\\\"yes\\\"],.elementor-image-gallery a');if(!e)return null;let href=e.href;e.click();return href})()");
  await delay(700);
  r.lightboxOpened=await b.evaluate("!!document.querySelector('.elementor-lightbox')&&getComputedStyle(document.querySelector('.elementor-lightbox')).display!=='none'");
  if(r.lightboxOpened)await key('Escape');
  r.errors=[...b.errors];report.push(r);console.log(route,'builder checked');
 }
}catch(e){report.push({error:String(e)})}finally{
 await writeFile(new URL('./interaction-results.json',import.meta.url),JSON.stringify(report,null,2));await b.close();
}
