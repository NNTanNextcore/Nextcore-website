import {writeFile} from 'node:fs/promises';
import {browser,delay} from './cdp.mjs';
const phase=process.argv[2]||'before';
const routes=['/','/en/','/ve-chung-toi/','/lien-he/','/outsource/','/dich-vu/','/tin-tuc/','/danh-muc-dich-vu/tu-van-doanh-nghiep/','/danh-muc-dich-vu/khach-hang-ca-nhan/','/danh-muc-dich-vu/san-pham/','/danh-muc-dich-vu/outsource/','/category/kien-thuc/','/category/cong-ty/','/dich-vu/lark/','/dich-vu/ung-dung-dat-san/','/dich-vu/wordpress-aff-atv/','/dich-vu/viet-plugin-wordpress-chuyen-nghiep-tuy-bien-theo-yeu-cau/','/wordpress-plugin-affiliate/','/truong-doanh-nhan-top-olympia/','/?s=wordpress','/phase6-nonexistent-page-qa/','/wp-admin/'];
const b=await browser();const rows=[];
try{
 await b.send('Emulation.setDeviceMetricsOverride',{width:1440,height:900,deviceScaleFactor:1,mobile:false});
 await b.send('Emulation.setEmulatedMedia',{features:[{name:'prefers-reduced-motion',value:'reduce'}]});
 await b.send('Page.addScriptToEvaluateOnNewDocument',{source:"localStorage.setItem('nextcore-theme-mode','light')"});
 for(let i=0;i<routes.length;i++){
  const url='http://localhost'+routes[i],r=await fetch(url);const html=await r.text();
  await writeFile(new URL('./'+phase+'-'+i+'.html',import.meta.url),html);
  b.errors.length=0;b.failed.length=0;
  await b.send('Page.navigate',{url});await delay(1200);
  const metrics=await b.evaluate("(()=>({url:location.href,title:document.title,h1:[...document.querySelectorAll('h1')].map(x=>x.textContent),overflow:document.documentElement.scrollWidth-innerWidth,hero:!!document.querySelector('.nextcore-page-hero'),sidebar:!!document.querySelector('.nextcore-sidebar'),cards:document.querySelectorAll('.nextcore-post-card').length,shortcodes:document.body.innerText.match(/\\[(?:\\/?)(?:section|row|col|ux_[a-z_]+)[\\s\\]][^\\n]{0,100}/g),login:!!document.querySelector('#loginform'),builder:document.querySelectorAll('.elementor').length}))()");
  const shot=await b.send('Page.captureScreenshot',{format:'png'});
  await writeFile(new URL('./'+phase+'-'+i+'-1440.png',import.meta.url),Buffer.from(shot.data,'base64'));
  rows.push({route:routes[i],http:r.status,...metrics,errors:[...b.errors],failed:[...b.failed]});
  console.log(routes[i]+' '+r.status+' overflow='+metrics.overflow);
  await writeFile(new URL('./'+phase+'-routes.json',import.meta.url),JSON.stringify(rows,null,2));
 }
}finally{await b.close();}
