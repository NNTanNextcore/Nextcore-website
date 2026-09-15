import {writeFile} from 'node:fs/promises';
import {browser, delay, urlFor} from './cdp.mjs';
const b = await browser();
try {
  await b.send('Page.navigate', {url: urlFor('/')});
  await delay(1800);
  for (const width of [320, 375, 430, 1440]) {
    await b.send('Emulation.setDeviceMetricsOverride', {width, height: 812, deviceScaleFactor: 1, mobile: false});
    for (const mode of ['light', 'dark']) {
      await b.evaluate(`window.NextcoreTheme.set('${mode}'); window.scrollTo(0,0)`);
      await delay(250);
      console.log(JSON.stringify(await b.evaluate(`({width:innerWidth, mode:document.documentElement.dataset.theme, opacity:getComputedStyle(document.querySelector('.hero-image')).opacity, overflow:document.documentElement.scrollWidth-innerWidth})`)));
      if (width === 375) {
        const shot = await b.send('Page.captureScreenshot', {format:'png'});
        await writeFile(new URL(`./hero-mobile-${mode}.png`, import.meta.url), Buffer.from(shot.data, 'base64'));
      }
    }
  }
  console.log(JSON.stringify({errors:b.errors}));
} finally { await b.close(); }
