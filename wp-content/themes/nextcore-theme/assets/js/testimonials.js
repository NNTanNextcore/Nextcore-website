/* Native scrolling keeps swipe and keyboard navigation available without a library.
   Labels and content live in HTML so a future translation layer can translate them. */
(() => {
  const section = document.querySelector('#testimonials');
  if (!section) return;
  const carousel = section.querySelector('.testimonial-carousel');
  const track = section.querySelector('.testimonial-track');
  if (!carousel || !track) return;
  const cards = [...track.children];
  const prev = section.querySelector('.testimonial-prev');
  const next = section.querySelector('.testimonial-next');
  const dots = section.querySelector('.testimonial-dots');
  const dialog = document.querySelector('.testimonial-dialog');
  if (!cards.length || !prev || !next || !dots || !dialog) return;
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  const autoplayDelay = 4500;
  const resetDelay = 700;
  let positions = [];
  let slides = cards;
  let autoplayTimer = 0;
  let resetTimer = 0;
  let paused = false;

  cards.forEach(card => {
    const clone = card.cloneNode(true);
    clone.setAttribute('aria-hidden', 'true');
    clone.classList.add('testimonial-card-clone');
    clone.querySelectorAll('a,button,input,select,textarea,[tabindex]').forEach(element => element.setAttribute('tabindex', '-1'));
    track.appendChild(clone);
  });
  slides = [...track.children];

  const active = () => positions.reduce((best, x, i) => Math.abs(x-track.scrollLeft) < Math.abs(positions[best]-track.scrollLeft) ? i : best, 0);
  const originalIndex = i => ((i % cards.length) + cards.length) % cards.length;
  const resetToOriginal = i => {
    clearTimeout(resetTimer);
    const target = positions[originalIndex(i)];
    if (typeof target === 'number') track.scrollTo({left: target, behavior: 'auto'});
    update();
  };
  const go = i => {
    if (!positions.length) return;
    const index = Math.max(0, Math.min(i, positions.length - 1));
    track.scrollTo({left: positions[index], behavior: reduced.matches ? 'auto' : 'smooth'});
    if (index >= cards.length) {
      clearTimeout(resetTimer);
      resetTimer = setTimeout(() => resetToOriginal(index), reduced.matches ? 0 : resetDelay);
    }
  };
  const stopAutoplay = () => {
    if (autoplayTimer) {
      clearInterval(autoplayTimer);
      autoplayTimer = 0;
    }
  };
  const startAutoplay = () => {
    stopAutoplay();
    if (paused || dialog.open || reduced.matches || positions.length < 2) return;
    autoplayTimer = setInterval(() => {
      const index = active();
      go(index + 1);
    }, autoplayDelay);
  };
  const pauseAutoplay = () => {
    paused = true;
    stopAutoplay();
  };
  const resumeAutoplay = () => {
    paused = false;
    startAutoplay();
  };
  const update = () => {
    const index = active();
    prev.disabled = positions.length < 2;
    next.disabled = positions.length < 2;
    [...dots.children].forEach((dot,i) => dot.setAttribute('aria-current', String(i === originalIndex(index))));
  };
  const resize = () => {
    const max = Math.max(0,track.scrollWidth-track.clientWidth);
    positions = slides.map(c => Math.min(max,c.offsetLeft-cards[0].offsetLeft))
      .filter((x,i,list) => x <= max && (i === 0 || x-list[i-1] > 2));
    dots.replaceChildren(...cards.map((card,i) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.setAttribute('aria-label', card.querySelector('h3').textContent);
      b.addEventListener('click',() => {
        go(i);
        startAutoplay();
      });
      return b;
    }));
    update();
    startAutoplay();
  };
  prev.addEventListener('click',() => {
    const index = active();
    go(index <= 0 ? cards.length - 1 : index - 1);
    startAutoplay();
  });
  next.addEventListener('click',() => {
    go(active()+1);
    startAutoplay();
  });
  carousel.addEventListener('mouseenter', pauseAutoplay);
  carousel.addEventListener('mouseleave', resumeAutoplay);
  carousel.addEventListener('focusin', pauseAutoplay);
  carousel.addEventListener('focusout', event => {
    if (!carousel.contains(event.relatedTarget)) resumeAutoplay();
  });
  document.addEventListener('visibilitychange', () => document.hidden ? stopAutoplay() : startAutoplay());
  if (reduced.addEventListener) reduced.addEventListener('change', startAutoplay);
  else if (reduced.addListener) reduced.addListener(startAutoplay);
  track.addEventListener('scroll',update,{passive:true});
  if ('ResizeObserver' in window) new ResizeObserver(resize).observe(track);
  else window.addEventListener('resize', resize);
  resize();
  slides.forEach(card => card.querySelector('button').addEventListener('click',() => {
    pauseAutoplay();
    dialog.querySelector('h2').textContent = card.querySelector('h3').textContent;
    dialog.querySelector('.testimonial-dialog-project').textContent = card.querySelector('.testimonial-person p').textContent;
    dialog.querySelector('blockquote').textContent = card.querySelector('blockquote').textContent;
    dialog.showModal();
  }));
  dialog.addEventListener('close', resumeAutoplay);
  dialog.querySelector('.testimonial-close').addEventListener('click',() => dialog.close());
  dialog.addEventListener('click',event => { if(event.target===dialog) { const r=dialog.getBoundingClientRect(); if(event.clientX<r.left||event.clientX>r.right||event.clientY<r.top||event.clientY>r.bottom) dialog.close(); } });
})();


