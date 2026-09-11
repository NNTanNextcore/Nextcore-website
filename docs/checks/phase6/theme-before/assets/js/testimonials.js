/* Native scrolling keeps swipe and keyboard navigation available without a library.
   Labels and content live in HTML so a future translation layer can translate them. */
(() => {
  const section = document.querySelector('#testimonials');
  if (!section) return;
  const track = section.querySelector('.testimonial-track');
  if (!track) return;
  const cards = [...track.children];
  const prev = section.querySelector('.testimonial-prev');
  const next = section.querySelector('.testimonial-next');
  const dots = section.querySelector('.testimonial-dots');
  const dialog = document.querySelector('.testimonial-dialog');
  if (!cards.length || !prev || !next || !dots || !dialog) return;
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  let positions = [];
  const active = () => positions.reduce((best, x, i) => Math.abs(x-track.scrollLeft) < Math.abs(positions[best]-track.scrollLeft) ? i : best, 0);
  const go = i => track.scrollTo({left: positions[Math.max(0,Math.min(i,positions.length-1))], behavior: reduced.matches ? 'instant' : 'smooth'});
  const update = () => {
    const index = active();
    prev.disabled = index === 0;
    next.disabled = index === positions.length-1;
    [...dots.children].forEach((dot,i) => dot.setAttribute('aria-current', String(i === index)));
  };
  const resize = () => {
    const max = Math.max(0,track.scrollWidth-track.clientWidth);
    positions = cards.map(c => Math.min(max,c.offsetLeft-cards[0].offsetLeft))
      .filter((x,i,list) => i === 0 || x-list[i-1] > 2);
    dots.replaceChildren(...positions.map((_,i) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.setAttribute('aria-label', cards[i].querySelector('h3').textContent);
      b.addEventListener('click',() => go(i));
      return b;
    }));
    update();
  };
  prev.addEventListener('click',() => go(active()-1));
  next.addEventListener('click',() => go(active()+1));
  track.addEventListener('scroll',update,{passive:true});
  if ('ResizeObserver' in window) new ResizeObserver(resize).observe(track);
  else window.addEventListener('resize', resize);
  resize();
  // Deliberately manual: no automatic movement while reading a real review.
  cards.forEach(card => card.querySelector('button').addEventListener('click',() => {
    dialog.querySelector('h2').textContent = card.querySelector('h3').textContent;
    dialog.querySelector('.testimonial-dialog-project').textContent = card.querySelector('.testimonial-person p').textContent;
    dialog.querySelector('blockquote').textContent = card.querySelector('blockquote').textContent;
    dialog.showModal();
  }));
  dialog.querySelector('.testimonial-close').addEventListener('click',() => dialog.close());
  dialog.addEventListener('click',event => { if(event.target===dialog) { const r=dialog.getBoundingClientRect(); if(event.clientX<r.left||event.clientX>r.right||event.clientY<r.top||event.clientY>r.bottom) dialog.close(); } });
})();


