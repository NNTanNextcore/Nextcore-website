import {readFile,writeFile} from 'node:fs/promises';
const get=async name=>JSON.parse(await readFile(new URL('./'+name+'.json',import.meta.url)));
const before=await get('current-state-before'),resume=await get('resume-state-before'),after=await get('current-state-after');
const canonical=x=>JSON.stringify(Array.isArray(x)?[...x].sort((a,b)=>JSON.stringify(a).localeCompare(JSON.stringify(b))):x);
const keys=['theme','template','frontpage','posts_page','plugins','permalink','acf_options','frontpage_acf','content','legacy_meta'];
const report={baseline:{},resume:{},menu69Unchanged:null};
for(const key of keys){report.baseline[key]=canonical(before[key])===canonical(after[key]);report.resume[key]=canonical(resume[key])===canonical(after[key])}
report.menuAssignments=after.theme_mods.nav_menu_locations;
report.menu69Unchanged=canonical(before.menus.find(m=>Number(m.term.term_id)===69))===canonical(after.menus.find(m=>Number(m.term.term_id)===69));
report.dictionaryCounts={baseline:before.dictionary_counts,after:after.dictionary_counts};
await writeFile(new URL('./data-comparison.json',import.meta.url),JSON.stringify(report,null,2));
console.log(JSON.stringify(report,null,2));
