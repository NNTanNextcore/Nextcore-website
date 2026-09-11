// Run with Node 20: node --experimental-websocket preview/checks/browser-check.mjs
// Requires a separate headless Chrome instance at 127.0.0.1:9223.
import { writeFile } from 'node:fs/promises';
import { setTimeout as delay } from 'node:timers/promises';

const target = (await (await fetch('http://127.0.0.1:9223/json/list')).json()).find((item) => item.type === 'page');
const socket = new WebSocket(target.webSocketDebuggerUrl);
await new Promise((resolve) => socket.addEventListener('open', resolve, { once: true }));
let nextId = 0;
const pending = new Map();
const errors = [];
socket.addEventListener('message', ({ data }) => {
  const message = JSON.parse(data);
  if (message.id) {
    const task = pending.get(message.id);
    pending.delete(message.id);
    if (message.error) task.reject(message.error); else task.resolve(message.result);
  }
  if (message.method === 'Runtime.exceptionThrown') errors.push(message.params.exceptionDetails.text);
  if (message.method === 'Network.loadingFailed') errors.push(message.params.errorText);
});
function send(method, params = {}) {
  return new Promise((resolve, reject) => {
    const id = ++nextId;
    pending.set(id, { resolve, reject });
    socket.send(JSON.stringify({ id, method, params }));
  });
}
async function evaluate(expression) {
  const result = await send('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true });
  if (result.exceptionDetails) throw new Error(JSON.stringify(result.exceptionDetails));
  return result.result.value;
}
await send('Page.enable');
await send('Runtime.enable');
await send('Network.enable');
const results = [];
try {
  for (const [name, width, height] of [['desktop', 1440, 900], ['tablet', 768, 1024], ['mobile', 390, 844], ['small-mobile', 320, 720]]) {
    await send('Emulation.setDeviceMetricsOverride', { width, height, deviceScaleFactor: 1, mobile: false });
    await send('Page.navigate', { url: 'http://localhost/preview/index.html' });
    await delay(1200);
    await evaluate(`(async () => {
      document.documentElement.style.scrollBehavior = 'auto';
      for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 90)); }
      await Promise.all([...document.images].map(i => i.decode().catch(() => {})));
      window.scrollTo(0, 0);
    })()`);
    await delay(750);
    const layout = await evaluate(`({
      width: innerWidth,
      companyLines: [...document.querySelectorAll('.company-line')].map(e => ({ text: e.textContent, width: e.getBoundingClientRect().width, contentWidth: e.scrollWidth })),
      titleSize: getComputedStyle(document.querySelector('h1')).fontSize,
      sloganSize: getComputedStyle(document.querySelector('.hero-slogan')).fontSize,
      sectionHeights: [...document.querySelectorAll('main > section')].map(e => ({ section: e.id || 'stats', height: Math.round(e.getBoundingClientRect().height) })),
      scrollWidth: document.documentElement.scrollWidth,
      missingImages: [...document.images].filter(i => !i.complete || !i.naturalWidth).map(i => i.getAttribute('src')),
      sections: document.querySelectorAll('main > section').length,
      h1: document.querySelectorAll('h1').length,
      brokenAnchors: [...document.querySelectorAll('a[href^="#"]')].filter(a => !document.getElementById(a.hash.slice(1))).map(a => a.hash),
      externalRequests: performance.getEntriesByType('resource').filter(e => !e.name.startsWith(location.origin)).map(e => e.name),
      servicesColumns: getComputedStyle(document.querySelector('.services-grid')).gridTemplateColumns,
      projectsColumns: getComputedStyle(document.querySelector('.projects-grid')).gridTemplateColumns
    })`);
    if (width < 1024) {
      layout.menu = await evaluate(`(() => {
        const b = document.querySelector('.menu-toggle'); b.click();
        const opened = b.getAttribute('aria-expanded') === 'true' && getComputedStyle(document.querySelector('.main-nav')).display !== 'none';
        document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));
        return { opened, closedWithEscape: b.getAttribute('aria-expanded') === 'false', focusReturned: document.activeElement === b };
      })()`);
    }
    layout.dialog = await evaluate(`(() => {
      const b = document.querySelector('[data-detail="portal"]'); b.click();
      const opened = document.querySelector('#detail-dialog').open;
      document.querySelector('#detail-dialog .dialog-close').click();
      return { opened, closed: !document.querySelector('#detail-dialog').open };
    })()`);
    layout.search = await evaluate(`(() => {
      document.querySelector('[data-open="search"]').click();
      const input = document.querySelector('#site-search'); input.value = 'phan mem'; input.dispatchEvent(new Event('input'));
      const found = document.querySelector('#search-results a') !== null;
      document.querySelector('#search-dialog').close(); return found;
    })()`);
    await delay(100);
    await evaluate('window.scrollTo(0, 0); document.activeElement.blur()');
    if (name !== 'small-mobile') {
      const heroShot = await send('Page.captureScreenshot', { format: 'png' });
      await writeFile(new URL(`./${name}-hero.png`, import.meta.url), Buffer.from(heroShot.data, 'base64'));
      const metrics = await send('Page.getLayoutMetrics');
      const screenshot = await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: true, clip: { x: 0, y: 0, width, height: metrics.cssContentSize.height, scale: 1 } });
      await writeFile(new URL(`./${name}.png`, import.meta.url), Buffer.from(screenshot.data, 'base64'));
    }
    results.push({ name, ...layout });
  }
  await send('Emulation.setEmulatedMedia', { features: [{ name: 'prefers-reduced-motion', value: 'reduce' }] });
  const reducedMotion = await evaluate(`({ animation: getComputedStyle(document.querySelector('.marquee-track')).animationName, overflow: document.documentElement.scrollWidth > innerWidth })`);
  await send('Emulation.setScriptExecutionDisabled', { value: true });
  await send('Page.navigate', { url: 'file:///C:/xampp/htdocs/preview/index.html' });
  await delay(1000);
  await send('Emulation.setScriptExecutionDisabled', { value: false });
  const fileWithoutJs = await evaluate(`({ title: document.title, visibleHero: getComputedStyle(document.querySelector('.hero-copy')).opacity, loadedHero: document.querySelector('.hero-image').naturalWidth > 0 })`);
  const report = { results, reducedMotion, fileWithoutJs, errors };
  await writeFile(new URL('./report.json', import.meta.url), JSON.stringify(report, null, 2));
  console.log(JSON.stringify(report, null, 2));
} finally {
  socket.close();
}
