import {readFile,writeFile,mkdir} from 'node:fs/promises';
import {browser,delay,urlFor} from './cdp.mjs';
const widths=[1440,1200,1024,768,480,375];
const existing=JSON.parse(await readFile(new URL('./after-native-routes.json',import.meta.url)));
const routes=existing.filter(r=>r.route!='/wp-admin/').map(r=>r.route);
routes.push('/dich-vu/he-thong-cham-cong-bang-guong-mat/','/en/ve-chung-toi/','/en/dich-vu/','/en/danh-muc-dich-vu/tu-van-doanh-nghiep/','/en/dich-vu/lark/','/en/?s=wordpress');
const responsive=new Set([0,1,2,3,4,5,6,7,13,14,15,16,17]);
await mkdir(new URL('./visual/',import.meta.url),{recursive:true});
const b=await browser(),rows=[];
try {
 await b.send('Emulation.setEmulatedMedia',{features:[{name:'prefers-reduced-motion',value:'reduce'}]});
 for(let i=0;i<routes.length;i++) {
  if(process.argv[2]&&!process.argv[2].split(',').map(Number).includes(i))continue;
  const route=routes[i],url=urlFor(route);
  const response=await fetch(url),html=await response.text();
  await writeFile(new URL('./final-'+i+'.html',import.meta.url),html);
  b.errors.length=0;b.failed.length=0;b.requests.length=0;
  await b.send('Page.navigate',{url});await delay(1500);
  await b.send('Page.bringToFront');
  await b.evaluate("document.fonts.ready.then(()=>true)");
  for(const width of responsive.has(i)?widths:[1440,375]) {
   await b.send('Emulation.setDeviceMetricsOverride',{width,height:900,deviceScaleFactor:1,mobile:false});
   for(let attempt=0;attempt<20;attempt++){await delay(100);if(await b.evaluate('innerWidth')===width)break;}
   for(const mode of ['light','dark']) {
    await b.evaluate(`(()=>{window.NextcoreTheme.set('${mode}');window.scrollTo(0,0)})()`);
    // Traverse the actual page to trigger lazy media and reveal observers.
    await b.evaluate("(async()=>{for(let y=0;y<document.documentElement.scrollHeight;y+=700){window.scrollTo({top:y,behavior:'instant'});await new Promise(r=>setTimeout(r,20))}window.scrollTo({top:0,behavior:'instant'})})()");
    await delay(250);
    await b.evaluate("Promise.race([Promise.all([...document.images].filter(e=>e.getClientRects().length).map(e=>e.decode().catch(()=>{}))),new Promise(r=>setTimeout(r,5000))]).then(()=>true)");
    const metrics=await b.evaluate(`(()=>{
     const visible=e=>e.getClientRects().length>0&&getComputedStyle(e).visibility!=='hidden';
     const ids=[...document.querySelectorAll('[id]')].map(e=>e.id);
     const d=document.documentElement;
     return {url:location.href,mode:d.dataset.theme,width:innerWidth,overflow:Math.max(0,d.scrollWidth-d.clientWidth),height:d.scrollHeight,
      h1:[...document.querySelectorAll('h1')].map(e=>e.innerText),date:document.querySelector('.company-founded')?.innerText,
      companyColor:document.querySelector('.company-name')?getComputedStyle(document.querySelector('.company-name')).color:null,
      headings:[...document.querySelectorAll('main h2,main .eyebrow,#blog .quiet-label')].map(e=>e.textContent.trim()),
      duplicateIds:[...new Set(ids.filter((id,j)=>ids.indexOf(id)!==j))],
      brokenImages:[...document.images].filter(e=>visible(e)&&e.complete&&!e.naturalWidth).map(e=>e.currentSrc||e.src),
      rawShortcodes:document.body.innerText.match(/\\[(?:\\/?)(?:section|row|col|ux_[a-z_]+)[\\s\\]][^\\n]{0,100}/g),
      markerLeak:document.body.innerText.includes('#!trp'),
      header:!!document.querySelector('.nextcore-site-header'),footer:!!document.querySelector('footer'),
      visibleThemeSwitches:[...document.querySelectorAll('.nextcore-theme-switch')].filter(visible).length,
      pagination:[...document.querySelectorAll('.page-numbers')].map(e=>({text:e.textContent,href:e.href})),
      cf7:document.querySelectorAll('.wpcf7 form').length,
      integrations:[...document.scripts].map(e=>e.src).filter(s=>/zalo|umami|connect.facebook.net.*sdk/i.test(s)),
      overflowElements:[...document.querySelectorAll('main *')].filter(e=>visible(e)&&e.getBoundingClientRect().right>d.clientWidth+2&&getComputedStyle(e).position!=='absolute').slice(0,10).map(e=>({tag:e.tagName,cls:e.className,right:Math.round(e.getBoundingClientRect().right)}))};})()`);
    let screenshot=null;
    if(i<2||width===1440||width===375){
     screenshot='visual/'+i+'-'+width+'-'+mode+'.png';
     const shot=await b.send('Page.captureScreenshot',{format:'png',captureBeyondViewport:true,clip:{x:0,y:0,width,height:metrics.height,scale:Math.min(1,14000/metrics.height)}});
     await writeFile(new URL('./'+screenshot,import.meta.url),Buffer.from(shot.data,'base64'));
    }
    rows.push({route,http:response.status,requestedWidth:width,...metrics,screenshot,errors:[...b.errors],failed:[...b.failed]});
   }
  }
  console.log(`${i+1}/${routes.length} ${route}: ${rows.filter(r=>r.route===route&&r.overflow>0).length} overflow cases`);
  await writeFile(new URL(process.argv[2]?'./responsive-retest-results.json':'./responsive-results.json',import.meta.url),JSON.stringify(rows,null,2));
 }
}finally{await b.close()}
