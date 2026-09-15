import {writeFile} from 'node:fs/promises';
const root = new URL('../../../wp-content/themes/nextcore-theme/assets/images/technology/', import.meta.url);
const icons = {drupal:'drupal',salesforce:'salesforce',python:'python',csharp:'csharp',java:'java',php:'php',javascript:'javascript',mysql:'mysql',postgresql:'postgresql',sqlite:'sqlite',flask:'flask',dotnet:'dot-net',laravel:'laravel',react:'react',vue:'vuejs',aws:'amazonwebservices',azure:'azure',gcp:'googlecloud',windows:'windows11',ubuntu:'ubuntu',centos:'centos'};
const base='https://raw.githubusercontent.com/devicons/devicon/v2.17.0/';
const sources=[];
for(const [mark, name] of Object.entries(icons)) {
 const path=`icons/${name}/${name}-original${mark==='aws'?'-wordmark':''}.svg`;
 const response=await fetch(base+path);
 if(!response.ok)throw Error(`${mark}: ${response.status}`);
 const svg=await response.text();
 if(!svg.includes('<svg') || /<script|<foreignObject|onload=|href=["']https?:/i.test(svg))throw Error(`Unsafe SVG: ${mark}`);
 await writeFile(new URL(mark+'.svg',root),svg);
 sources.push(`- ${mark}.svg: ${base+path}`);
 console.log(mark);
}
const license=await fetch(base+'LICENSE');
if(!license.ok)throw Error('License download failed');
await writeFile(new URL('LICENSE.devicon',root),await license.text());
await writeFile(new URL('SOURCES.md',root),'# Technology assets\n\nVendored from Devicon v2.17.0. MIT license retained in LICENSE.devicon. Brand marks belong to their respective owners. No runtime external requests.\n\n'+sources.join('\n')+'\n');
