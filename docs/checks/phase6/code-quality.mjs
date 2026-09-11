import {readFile,writeFile,readdir} from 'node:fs/promises';
import {execFileSync} from 'node:child_process';
const root=new URL('../../../wp-content/themes/nextcore-theme/',import.meta.url);
const walk=async dir=>(await Promise.all((await readdir(dir,{withFileTypes:true})).map(async e=>e.isDirectory()?walk(new URL(e.name+'/',dir)):[new URL(e.name,dir)]))).flat();
const files=await walk(root),report={php:[],js:[],forbidden:[],changed:[],optional:null};
for(const file of files) {
 const path=decodeURIComponent(file.pathname).replace(/^\//,''),rel=file.href.slice(root.href.length);
 if(/\.(php|js)$/.test(rel)){
  const kind=rel.endsWith('.php')?'php':'js';
  try { execFileSync(kind==='php'?'C:/xampp/php/php.exe':'node',kind==='php'?['-l',path]:['--check',path],{encoding:'utf8'});report[kind].push({file:rel,pass:true}); }
  catch(e){report[kind].push({file:rel,pass:false,error:e.message})}
 }
 if(/\.(php|js|css)$/.test(rel)){
  const text=await readFile(file,'utf8');
  if(/flatsome_|\/flatsome\/|localhost|nextcore\.vn\/wp-content\/uploads|['"]\/en\//.test(text))report.forbidden.push(rel);
  let before=null;try{before=await readFile(new URL('./theme-before/'+rel,import.meta.url),'utf8')}catch{}
  if(before!==text)report.changed.push({file:rel,action:before===null?'added':'modified'});
 }
}
try{report.optional=JSON.parse(execFileSync('C:/xampp/php/php.exe',['docs/checks/phase6/optional-plugins-qa.php'],{encoding:'utf8'}))}catch(e){report.optional={error:e.message}}
await writeFile(new URL('./code-quality-results.json',import.meta.url),JSON.stringify(report,null,2));
console.log(JSON.stringify({php:report.php.length,js:report.js.length,failed:[...report.php,...report.js].filter(r=>!r.pass),forbidden:report.forbidden,changed:report.changed,optional:report.optional},null,2));
