import {browser,delay} from './cdp.mjs';
const b=await browser();
try {
 await b.send('Page.navigate',{url:'http://localhost/en/'});await delay(2200);
 console.log('before',JSON.stringify(await b.evaluate("({card:document.querySelector('.testimonial-card').outerHTML,dialog:document.querySelector('.testimonial-dialog').outerHTML})")));
 await b.evaluate("document.querySelector('.testimonial-card button').click()");await delay(2000);
 console.log('after',JSON.stringify(await b.evaluate("({dialog:document.querySelector('.testimonial-dialog').outerHTML,errors:document.querySelector('.testimonial-dialog').innerText})")));
}finally{await b.close()}
