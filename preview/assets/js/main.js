/* Progressive enhancement: content and anchor navigation remain static HTML. */
(() => {
  'use strict';
  const header = document.querySelector('.site-header');
  const menuButton = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');
  const desktop = window.matchMedia('(min-width: 1024px)');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  // Keep the poster visible until playback succeeds. No fade or transform on video.
  const companySection = document.querySelector('.company-video');
  const companyVideo = companySection?.querySelector('video');
  if (companyVideo) {
    let inView = false;
    const showPoster = () => companySection.classList.remove('is-playing');
    const syncVideo = () => {
      if (reducedMotion.matches || !inView || document.hidden) {
        companyVideo.pause();
        showPoster();
        return;
      }
      companyVideo.play().catch(showPoster);
    };
    companyVideo.muted = true;
    companyVideo.addEventListener('playing', () => {
      if (!reducedMotion.matches && inView && !document.hidden) companySection.classList.add('is-playing');
      else syncVideo();
    });
    companyVideo.addEventListener('error', showPoster);
    companyVideo.addEventListener('pause', showPoster);
    // Keep the current frame during buffering, including at the loop boundary.
    // Before first playback the poster is already visible.
    reducedMotion.addEventListener('change', syncVideo);
    document.addEventListener('visibilitychange', syncVideo);
    if ('IntersectionObserver' in window) {
      const videoObserver = new IntersectionObserver(([entry]) => {
        inView = entry.isIntersecting;
        syncVideo();
      }, { threshold: 0 });
      videoObserver.observe(companySection);
    } else {
      inView = true;
    }
    syncVideo();
  }

  function setMenu(open, returnFocus = false) {
    menuButton.setAttribute('aria-expanded', String(open));
    menuButton.setAttribute('aria-label', open ? 'Đóng menu' : 'Mở menu');
    nav.classList.toggle('is-open', open);
    header.classList.toggle('menu-open', open);
    if (returnFocus) menuButton.focus();
  }
  menuButton.addEventListener('click', () => setMenu(menuButton.getAttribute('aria-expanded') !== 'true'));
  nav.addEventListener('click', (event) => { if (event.target.closest('a')) setMenu(false); });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && menuButton.getAttribute('aria-expanded') === 'true') setMenu(false, true);
  });
  document.addEventListener('click', (event) => { if (!header.contains(event.target)) setMenu(false); });
  desktop.addEventListener('change', () => setMenu(false));
  const syncHeader = () => header.classList.toggle('is-scrolled', window.scrollY > 24);
  window.addEventListener('scroll', syncHeader, { passive: true });
  syncHeader();

  if ('IntersectionObserver' in window) {
    const sections = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        nav.querySelectorAll('a').forEach((link) => link.classList.toggle('is-active', link.hash === `#${entry.target.id}`));
      });
    }, { rootMargin: '-15% 0px -65% 0px' });
    document.querySelectorAll('main section[id]').forEach((section) => sections.observe(section));
    if (!reducedMotion.matches) {
      const reveal = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add('is-visible');
          reveal.unobserve(entry.target);
        });
      }, { threshold: 0.08 });
      document.querySelectorAll('[data-reveal]').forEach((element) => {
        element.classList.add('reveal-ready');
        reveal.observe(element);
      });
    }
  }

  const marquee = document.querySelector('.marquee');
  const track = document.querySelector('.marquee-track');
  const duplicate = track.firstElementChild.cloneNode(true);
  duplicate.setAttribute('aria-hidden', 'true');
  track.append(duplicate);
  const pauseButton = document.querySelector('.marquee-control');
  pauseButton.addEventListener('click', () => {
    const paused = marquee.classList.toggle('is-paused');
    pauseButton.setAttribute('aria-pressed', String(paused));
    pauseButton.setAttribute('aria-label', paused ? 'Tiếp tục chuyển động công nghệ' : 'Tạm dừng chuyển động công nghệ');
    pauseButton.textContent = paused ? '▷' : 'Ⅱ';
  });

  const details = {
    portal: ['Nextcore Portal', 'Hệ thống quản lý doanh nghiệp thông minh. Giao diện minh họa chức năng báo cáo và quản lý chấm công.'],
    olympia: ['Trường Doanh nhân Top Olympia', 'Website đào tạo doanh nhân, kết nối chương trình học với học viên.'],
    affiliate: ['WordPress Plugin - Affiliate', 'Giải pháp tiếp thị liên kết với cấu hình và quản lý ngay trong WordPress.'],
    partner: ['GM Solutions', 'Đối tác chiến lược của Nextcore. Cùng nhau kiến tạo những giá trị bền vững.'],
    'blog-digital': ['Chuyển đổi số cho doanh nghiệp', 'Bài viết minh họa cho bố cục homepage. Nội dung bài viết chính thức sẽ được kết nối ở giai đoạn WordPress.'],
    'blog-software': ['Vì sao doanh nghiệp nên đầu tư vào phần mềm tùy chỉnh?', 'Bài viết minh họa cho bố cục homepage. Nội dung bài viết chính thức sẽ được kết nối ở giai đoạn WordPress.'],
    'blog-trends': ['Xu hướng công nghệ 2026', 'Bài viết minh họa cho bố cục homepage. Nội dung bài viết chính thức sẽ được kết nối ở giai đoạn WordPress.'],
    social: ['Kết nối với Nextcore', 'Liên kết mạng xã hội chính thức sẽ được bổ sung sau khi xác nhận.']
  };
  const detailDialog = document.querySelector('#detail-dialog');
  function openDialog(dialog) {
    setMenu(false);
    dialog.showModal();
    document.body.classList.add('modal-open');
  }
  document.querySelectorAll('[data-detail]').forEach((button) => {
    button.addEventListener('click', () => {
      const [title, description] = details[button.dataset.detail];
      document.querySelector('#detail-title').textContent = title;
      document.querySelector('#detail-description').textContent = description;
      openDialog(detailDialog);
    });
  });
  document.querySelectorAll('[data-open]').forEach((button) => {
    button.addEventListener('click', () => {
      openDialog(document.querySelector(`#${button.dataset.open}-dialog`));
      if (button.dataset.open === 'search') document.querySelector('#site-search').focus();
    });
  });
  document.querySelectorAll('dialog').forEach((dialog) => {
    dialog.querySelectorAll('.dialog-close, [data-close], [data-dialog-contact]').forEach((button) => button.addEventListener('click', () => dialog.close()));
    dialog.addEventListener('click', (event) => {
      if (event.target !== dialog) return;
      const bounds = dialog.getBoundingClientRect();
      if (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom) dialog.close();
    });
    dialog.addEventListener('close', () => document.body.classList.remove('modal-open'));
  });

  const searchEntries = [...document.querySelectorAll('main section[id]')].map((section) => ({
    id: section.id,
    title: section.querySelector('h1, h2')?.textContent.replace(/\s+/g, ' ').trim() || '',
    text: section.textContent
  }));
  const normalize = (text) => text.toLocaleLowerCase('vi').normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd');
  const searchInput = document.querySelector('#site-search');
  const searchResults = document.querySelector('#search-results');
  function search() {
    const query = normalize(searchInput.value.trim());
    const matches = searchEntries.filter((entry) => !query || normalize(entry.text).includes(query));
    searchResults.replaceChildren();
    matches.forEach((entry) => {
      const item = document.createElement('li');
      const link = document.createElement('a');
      link.href = `#${entry.id}`;
      link.textContent = entry.title;
      link.addEventListener('click', () => document.querySelector('#search-dialog').close());
      item.append(link);
      searchResults.append(item);
    });
    if (!matches.length) {
      const item = document.createElement('li');
      item.textContent = 'Không tìm thấy nội dung phù hợp.';
      searchResults.append(item);
    }
  }
  searchInput.addEventListener('input', search);
  search();
})();
