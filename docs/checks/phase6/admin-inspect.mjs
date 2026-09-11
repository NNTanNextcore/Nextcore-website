import {browser,delay} from './cdp.mjs';
const b=await browser(9224);
try {
 await b.send('Page.navigate',{url:'http://localhost/wp-admin/admin.php?page=nextcore-settings'});await delay(2000);
 console.log(JSON.stringify(await b.evaluate("({path:location.pathname,login:!!document.querySelector('#loginform'),groups:[...document.querySelectorAll('.postbox h2')].map(e=>e.textContent),fields:[...document.querySelectorAll('.acf-field[data-name]')].map(e=>e.dataset.name),buttons:[...document.querySelectorAll('input[type=submit],button[type=submit]')].map(e=>({id:e.id,text:e.value||e.textContent})),headerValue:document.querySelector('[data-name=nc_header_contact_label] input[type=text]')?.value})"),null,2));
} finally {await b.close();}
