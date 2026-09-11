import {setTimeout as delay} from 'node:timers/promises';
export {delay};
export async function browser(port=9223) {
 const endpoint='http://127.0.0.1:'+port;
 const target=await (await fetch(endpoint+'/json/new?about:blank',{method:'PUT'})).json();
 const socket=new WebSocket(target.webSocketDebuggerUrl);
 await new Promise(r=>socket.addEventListener('open',r,{once:true}));
 let id=0; const pending=new Map(),errors=[],failed=[],requests=[],consoleErrors=[],networkFailures=[];
 const send=(method,params={})=>new Promise((resolve,reject)=>{const n=++id;pending.set(n,{resolve,reject});socket.send(JSON.stringify({id:n,method,params}));});
 socket.addEventListener('message',({data})=>{
  const m=JSON.parse(data);if(m.id){let p=pending.get(m.id);pending.delete(m.id);m.error?p.reject(m.error):p.resolve(m.result);}
  if(m.method==='Runtime.exceptionThrown')errors.push(m.params.exceptionDetails.exception?.description||m.params.exceptionDetails.text);
  if(m.method==='Network.responseReceived'&&m.params.response.status>=400)failed.push({url:m.params.response.url,status:m.params.response.status});
  if(m.method==='Network.requestWillBeSent')requests.push(m.params.request.url);
  if(m.method==='Log.entryAdded'&&m.params.entry.level==='error')consoleErrors.push(m.params.entry.text);
  if(m.method==='Runtime.consoleAPICalled'&&m.params.type==='error')consoleErrors.push(m.params.args.map(a=>a.value??a.description??'').join(' ').slice(0,1000));
  if(m.method==='Network.loadingFailed'&&!m.params.canceled)networkFailures.push({type:m.params.type,error:m.params.errorText,blockedReason:m.params.blockedReason});
 });
 const evaluate=async(expression)=>{let r=await send('Runtime.evaluate',{expression,returnByValue:true,awaitPromise:true});if(r.exceptionDetails)throw Error(JSON.stringify(r.exceptionDetails));return r.result.value;};
 await send('Page.enable');await send('Runtime.enable');await send('Network.enable');await send('Log.enable');
 return {send,evaluate,errors,failed,requests,consoleErrors,networkFailures,close:async()=>{socket.close();await fetch(endpoint+'/json/close/'+target.id);}};
}
