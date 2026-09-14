import {browser,delay,urlFor} from './cdp.mjs';
const b=await browser();
try{
 for(const route of ['/dich-vu/lark/','/dich-vu/ung-dung-dat-san/']){
 await b.send('Emulation.setDeviceMetricsOverride',{width:375,height:900,deviceScaleFactor:1,mobile:false});
 await b.send('Page.navigate',{url:urlFor(route)});await delay(2500);
 console.log(route,JSON.stringify(await b.evaluate("({height:document.documentElement.scrollHeight,width:innerWidth,images:[...document.querySelectorAll('.nextcore-lark img')].map(e=>({src:e.src,complete:e.complete,natural:e.naturalWidth,opacity:getComputedStyle(e).opacity,visibility:getComputedStyle(e).visibility,display:getComputedStyle(e).display,width:e.getBoundingClientRect().width,height:e.getBoundingClientRect().height})),errors:document.body.innerText.slice(-50)})")));
 }
}finally{await b.close()}
