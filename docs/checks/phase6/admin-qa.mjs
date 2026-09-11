import {writeFile} from 'node:fs/promises';
import {browser,delay} from './cdp.mjs';
const b=await browser(9224), report={checks:{},writes:[]};
const url='http://localhost/wp-admin/admin.php?page=nextcore-settings';
let original='',changed=false;
const load=async u=>{await b.send('Page.navigate',{url:u});await delay(2200);};
const field="[data-name=nc_header_contact_label] input[type=text]";
const set=async value=>b.evaluate("(()=>{let e=document.querySelector("+JSON.stringify(field)+");e.value="+JSON.stringify(value)+";e.dispatchEvent(new Event('change',{bubbles:true}));})()");
const save=async()=>{await b.evaluate("document.querySelector('#publish').click()");await delay(3500);};
try{
 await load(url);
 report.checks.authenticated=await b.evaluate("!document.querySelector('#loginform')&&!!document.querySelector('#publish')");
 if(!report.checks.authenticated)throw Error('Admin session missing');
 original=await b.evaluate("document.querySelector("+JSON.stringify(field)+").value");
 report.option_groups=await b.evaluate("[...document.querySelectorAll('.postbox h2')].map(e=>e.textContent)");
 report.checks.options_groups=report.option_groups.filter(x=>/^G(11|12|13|15)/.test(x)).length===4;
 report.checks.image_controls=await b.evaluate("!!document.querySelector('[data-name=nc_hero_image_light] .acf-image-uploader')");
 report.checks.video_controls=await b.evaluate("!!document.querySelector('[data-name=nc_about_video_light] .acf-file-uploader')");
 await set('');await save();
 report.checks.required_validation=await b.evaluate("!!document.querySelector('.acf-error-message')||!!document.querySelector('.acf-notice.-error')");
 await set(original+' · QA');await save();changed=true;
 const html=await (await fetch('http://localhost/?nc_phase6_admin_check=changed')).text();
 report.checks.frontend_changed=html.includes(original+' · QA');
 report.writes.push({source:'options_nc_header_contact_label',old:original,new:original+' · QA',method:'WordPress Admin form'});
 await load(url);await set(original);await save();changed=false;
 const restored=await (await fetch('http://localhost/?nc_phase6_admin_check=restored')).text();
 report.checks.frontend_restored=restored.includes(original)&&!restored.includes(original+' · QA');
 report.writes.push({source:'options_nc_header_contact_label',old:original+' · QA',new:original,method:'WordPress Admin form restore'});
 // Open picker UI without selecting, uploading or saving another attachment.
 for(const [name,type] of [['nc_hero_image_light','image'],['nc_about_video_light','video']]){
  const clicked=await b.evaluate("(()=>{const e=document.querySelector('[data-name="+name+"] a[data-name=edit]');if(!e)return false;e.click();return true;})()");
  await delay(1200);
  report.checks[type+'_picker_open']=clicked&&await b.evaluate("!!document.querySelector('.media-modal')");
  await b.evaluate("document.querySelector('.media-modal-close')?.click()");await delay(300);
 }
 await load('http://localhost/wp-admin/post.php?post=312&action=edit');
 report.home_groups=await b.evaluate("[...document.querySelectorAll('.postbox h2')].map(e=>e.textContent)");
 report.checks.homepage_groups=report.home_groups.filter(x=>/^G(0[1-9]|10|14)/.test(x)).length===11;
 report.checks.repeater=await b.evaluate("document.querySelectorAll('[data-name=nc_testimonials] .acf-row:not(.acf-clone)').length===4");
 report.checks.relationships=await b.evaluate("!!document.querySelector('[data-name=object][data-type=post_object]')&&!!document.querySelector('[data-name=target_term][data-type=taxonomy]')");
 report.checks.portal_empty=await b.evaluate("!document.querySelector('[data-name=nc_featured_projects] [data-name=object] select')?.value");
 report.errors=[...b.errors];
}catch(e){report.error=String(e);}finally{
 if(changed&&original){await load(url);await set(original);await save();report.emergency_restore=true;}
 await writeFile(new URL('./admin-qa-results.json',import.meta.url),JSON.stringify(report,null,2));
 console.log(JSON.stringify(report,null,2));await b.close();
}
