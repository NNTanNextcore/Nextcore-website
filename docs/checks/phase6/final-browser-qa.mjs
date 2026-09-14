import {readFile,writeFile} from 'node:fs/promises';
import {browser,delay,urlFor} from './cdp.mjs';
const rows=JSON.parse(await readFile(new URL('./responsive-results.json',import.meta.url))),routes=[...new Set(rows.map(r=>r.route))],report=[];
const b=await browser();
try{
 await b.send('Emulation.setDeviceMetricsOverride',{width:1440,height:900,deviceScaleFactor:1,mobile:false});
 for(const route of routes){
  b.errors.length=0;b.failed.length=0;b.requests.length=0;b.consoleErrors.length=0;b.networkFailures.length=0;
  await b.send('Page.navigate',{url:urlFor(route)});await b.send('Page.bringToFront');await delay(1500);
  await b.evaluate("(async()=>{for(let y=0;y<document.documentElement.scrollHeight;y+=800){window.scrollTo({top:y,behavior:'instant'});await new Promise(r=>setTimeout(r,20))}await Promise.race([Promise.all([...document.images].map(e=>e.decode().catch(()=>{}))),new Promise(r=>setTimeout(r,4000))]);window.scrollTo({top:0,behavior:'instant'})})()");
  const dom=await b.evaluate(`(()=>{
   const visible=e=>e.getClientRects().length>0&&getComputedStyle(e).visibility!=='hidden';
   const ids=[...document.querySelectorAll('[id]')].map(e=>e.id);
   return {duplicateIds:ids.filter((id,i)=>ids.indexOf(id)!==i),emptyIds:ids.filter(id=>!id).length,
    pendingImages:[...document.images].filter(e=>visible(e)&&!e.complete).map(e=>e.currentSrc||e.src),brokenImages:[...document.images].filter(e=>visible(e)&&e.complete&&!e.naturalWidth).map(e=>e.currentSrc||e.src),
    languages:[...document.querySelectorAll('.nextcore-language-switch')].filter(visible).length,themeSwitches:[...document.querySelectorAll('.nextcore-theme-switch')].filter(visible).length,
    seo:{canonical:document.querySelectorAll('link[rel=canonical]').length,hreflangs:[...document.querySelectorAll('link[hreflang]')].map(e=>({lang:e.hreflang,url:e.href}))},
    integrationNodes:document.querySelectorAll('.zalo-chat-widget,.fb-customerchat,#fb-root,[data-website-id],.nextcore-floating-contact').length,
    videos:[...document.querySelectorAll('video')].map(e=>({src:e.currentSrc,ready:e.readyState,error:e.error?.code??null})),
    timeline:document.querySelector('.eae-pb-inner-line')?.style.height,
    untranslatedHomepage:/\/en\/$/.test(location.pathname)?[...document.querySelectorAll('main *')].filter(e=>!e.children.length&&/[ăâđêôơưạảãậắếệịọớợụứựỳỹ]/i.test(e.textContent)).map(e=>({tag:e.tagName,cls:e.className,text:e.textContent.trim().slice(0,250)})):[]};})()`);
  report.push({route,...dom,errors:[...b.errors],consoleErrors:[...b.consoleErrors],failed:[...b.failed],networkFailures:[...b.networkFailures],integrationRequests:b.requests.filter(s=>/zalo|umami|connect.facebook.net.*sdk/i.test(s))});
  console.log(route+' assets='+b.failed.length+' exceptions='+b.errors.length+' console='+b.consoleErrors.length);
  await writeFile(new URL('./final-browser-results.json',import.meta.url),JSON.stringify(report,null,2));
 }
}finally{await b.close()}
