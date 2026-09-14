import {browser,delay,urlFor} from './cdp.mjs';
for (const port of [9223,9224]) {
 try {
  const b=await browser(port);
  await b.send('Page.navigate',{url:port===9223?urlFor('/en/'):urlFor('/wp-admin/')});await delay(2500);
  console.log(JSON.stringify({port,result:await b.evaluate("({url:location.href,date:document.querySelector('.company-founded')?.innerHTML,blog:document.querySelector('#blog .quiet-label')?.outerHTML,login:!!document.querySelector('#loginform')})")}));
  await b.close();
 } catch(e) { console.log(port,String(e)); }
}
