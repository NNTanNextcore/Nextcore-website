import {writeFile} from 'node:fs/promises';
import {browser,delay} from './cdp.mjs';
const b=await browser(9224),report={checks:{}};
const load=async url=>{await b.send('Page.navigate',{url});await delay(2500);};
try {
 await load('http://localhost/wp-admin/admin.php?page=nextcore-settings');
 report.checks.authenticated=await b.evaluate("!!document.querySelector('#publish')&&!document.querySelector('#loginform')");
 if(!report.checks.authenticated)throw Error('Admin login required');
 report.image_before=await b.evaluate("document.querySelector('[data-name=nc_hero_image_light] .acf-input')?.innerHTML");
 report.tabs=await b.evaluate("[...document.querySelectorAll('.acf-tab-button')].map(e=>({key:e.dataset.key,text:e.textContent}))");
 await b.evaluate("(()=>{let f=document.querySelector('[data-name=nc_hero_image_light]');let p=f.previousElementSibling;while(p&&p.dataset.type!=='tab')p=p.previousElementSibling;if(p)document.querySelector('.acf-tab-button[data-key='+p.dataset.key+']')?.click();f.scrollIntoView();f.querySelector('a[data-name=edit]').click()})()");
 await delay(3000);
 report.checks.image_picker_open=await b.evaluate("!!document.querySelector('.media-modal')&&document.querySelector('.media-modal').getClientRects().length>0");
 report.image_dialog=await b.evaluate("document.querySelector('.media-modal')?.innerText.slice(0,600)");
 await b.evaluate("document.querySelector('.media-modal-close')?.click()");
 await load('http://localhost/wp-admin/post.php?post=312&action=edit');
 for(const [name,type] of [['object','post_object'],['target_term','taxonomy']]) {
  report[type]=await b.evaluate(`(()=>{const f=[...document.querySelectorAll('[data-name=${name}][data-type=${type}]')].find(e=>!e.closest('.acf-clone'));f.scrollIntoView(); const s=f.querySelector('select');jQuery(s).select2('open');return {value:s.value,options:[...s.options].map(e=>({value:e.value,text:e.textContent}))}})()`);
  for(let attempt=0;attempt<12;attempt++) {
   await delay(500);
   if(await b.evaluate("!!document.querySelector('.select2-results__option[aria-selected],.select2-result-selectable')"))break;
  }
  report.checks[type+'_dropdown_open']=await b.evaluate("!!document.querySelector('.select2-container--open .select2-results__option,.select2-drop-active .select2-result')");
  report[type].results=await b.evaluate("[...document.querySelectorAll('.select2-container--open .select2-results__option,.select2-drop-active .select2-result')].map(e=>e.textContent).slice(0,12)");
  await b.evaluate(`(()=>{const f=[...document.querySelectorAll('[data-name=${name}][data-type=${type}]')].find(e=>!e.closest('.acf-clone'));jQuery(f.querySelector('select')).select2('close')})()`);
 }
 report.errors=[...b.errors];
}catch(e){report.error=e instanceof Error?String(e):JSON.stringify(e)}finally{
 await writeFile(new URL('./admin-followup-results.json',import.meta.url),JSON.stringify(report,null,2));
 console.log(JSON.stringify(report,null,2));await b.close();
}
