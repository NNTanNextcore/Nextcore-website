<?php
/**
 * Template Name: Plety SceneAI Landing
 * Template Post Type: page
 *
 * Single-file WordPress landing page template adapted from a SceneAI prompt.
 * Uses Tailwind CDN for rapid prototyping and vanilla JavaScript for interactions.
 */
if (!defined('ABSPATH')) exit;
?><!doctype html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#000000" />
  <?php wp_head(); ?>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter','ui-sans-serif','system-ui','-apple-system','BlinkMacSystemFont','Segoe UI','sans-serif'],
            serif: ['Georgia','Times New Roman','serif']
          },
          keyframes: {
            marquee: {
              '0%': { transform: 'translateX(0)' },
              '100%': { transform: 'translateX(-33.333333%)' }
            }
          }
        }
      }
    }
  </script>
  <style>
    html { scroll-behavior: smooth; background:#000; }
    body { margin:0; background:#000; color:#fff; overflow-x:hidden; }
    * { box-sizing:border-box; }
    .reveal-up { opacity:0; transform:translateY(2.5rem); transition:opacity 1000ms ease, transform 1000ms ease; }
    .reveal-up.is-visible { opacity:1; transform:translateY(0); }
    .marquee-mask { -webkit-mask-image:linear-gradient(to right,transparent,black 8%,black 92%,transparent); mask-image:linear-gradient(to right,transparent,black 8%,black 92%,transparent); }
    .animate-marquee { animation: marquee 30s linear infinite; }
    @keyframes marquee { from { transform:translateX(0); } to { transform:translateX(-33.333333%); } }
    .faq-grid { display:grid; grid-template-rows:0fr; transition:grid-template-rows 350ms ease; }
    .faq-item.is-open .faq-grid { grid-template-rows:1fr; }
    .faq-grid > div { overflow:hidden; }
    .faq-plus { transition:transform 300ms ease; }
    .faq-item.is-open .faq-plus { transform:rotate(45deg); }
    .mobile-menu { max-height:0; opacity:0; overflow:hidden; transition:max-height 400ms ease, opacity 250ms ease; }
    .mobile-menu.is-open { max-height:420px; opacity:1; }
    .nav-solid { background:rgba(0,0,0,.8); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px); border-bottom:1px solid rgba(255,255,255,.05); }
    .video-cover { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
    .glass-card { background:rgba(28,28,30,.90); backdrop-filter:blur(24px); -webkit-backdrop-filter:blur(24px); }
  </style>
</head>
<body <?php body_class('bg-black text-white antialiased'); ?>>
<?php wp_body_open(); ?>

<div id="plety-app" class="min-h-screen bg-black text-white">
  <!-- NAV -->
  <header id="siteNav" class="fixed inset-x-0 top-0 z-50 transition-all duration-300">
    <div class="mx-auto max-w-7xl px-6">
      <div class="flex h-20 items-center justify-between">
        <a href="#about" class="flex items-center gap-3" aria-label="Plety home">
          <svg width="34" height="34" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M8 29L20 7L32 29" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12.5 22.5H27.5" stroke="white" stroke-width="2.2" stroke-linecap="round"/>
            <path d="M15 29L20 20L25 29" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" opacity=".55"/>
          </svg>
          <span class="text-xl font-bold tracking-tight">Plety</span>
        </a>

        <nav class="hidden items-center gap-8 md:flex" aria-label="Primary">
          <a class="nav-link text-sm font-medium text-gray-300 transition-colors hover:text-white" href="#about">About</a>
          <a class="nav-link text-sm font-medium text-gray-300 transition-colors hover:text-white" href="#features">Features</a>
          <a class="nav-link text-sm font-medium text-gray-300 transition-colors hover:text-white" href="#faq">FAQ</a>
          <a class="nav-link text-sm font-medium text-gray-300 transition-colors hover:text-white" href="#contact">Contact</a>
        </nav>

        <div class="hidden md:block">
          <a href="#features" class="rounded-full border border-white/5 bg-[#1F1F22] px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#2A2A2D]">Get started</a>
        </div>

        <button id="menuBtn" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 md:hidden" aria-label="Open menu" aria-expanded="false">
          <span class="relative block h-4 w-5">
            <span class="absolute left-0 top-0 h-px w-5 bg-white"></span>
            <span class="absolute left-0 top-[7px] h-px w-5 bg-white"></span>
            <span class="absolute left-0 top-[14px] h-px w-5 bg-white"></span>
          </span>
        </button>
      </div>

      <div id="mobileMenu" class="mobile-menu border-t border-white/5 md:hidden">
        <div class="flex flex-col gap-1 py-4">
          <a class="mobile-link rounded-xl px-4 py-3 text-sm text-gray-300 hover:bg-white/5 hover:text-white" href="#about">About</a>
          <a class="mobile-link rounded-xl px-4 py-3 text-sm text-gray-300 hover:bg-white/5 hover:text-white" href="#features">Features</a>
          <a class="mobile-link rounded-xl px-4 py-3 text-sm text-gray-300 hover:bg-white/5 hover:text-white" href="#faq">FAQ</a>
          <a class="mobile-link rounded-xl px-4 py-3 text-sm text-gray-300 hover:bg-white/5 hover:text-white" href="#contact">Contact</a>
          <a class="mt-2 rounded-full border border-white/5 bg-[#1F1F22] px-5 py-3 text-center text-sm font-medium text-white" href="#features">Get started</a>
        </div>
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section id="about" class="relative z-0 flex min-h-screen flex-col items-center justify-center overflow-hidden pb-20 pt-32">
    <video autoplay muted loop playsinline class="video-cover -z-10 min-h-full min-w-full opacity-90" preload="metadata">
      <source src="https://cdn.sceneai.art/Hero%20Section%20Video/50b4f304-cdca-4e12-8735-580d225834be.mp4" type="video/mp4" />
    </video>
    <div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-b from-black/30 via-transparent to-black"></div>

    <div class="mx-auto flex w-full max-w-7xl flex-col items-center px-6 text-center">
      <div class="reveal-up mb-8 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-gray-300 backdrop-blur-sm">✨ Announcing API 2.0</div>
      <h1 class="reveal-up mb-6 whitespace-pre-line text-center text-5xl font-medium tracking-tight md:text-7xl">The intelligence layer<br>for clear <span class="font-serif font-normal italic">decisions.</span></h1>
      <p class="reveal-up max-w-2xl text-center text-[16px] leading-7 text-gray-400">Our platform integrates seamlessly into your stack to deliver real-time understanding, not just predictions.</p>
      <div class="reveal-up mt-8 flex flex-wrap items-center justify-center gap-3">
        <a href="#features" class="rounded-full bg-white px-6 py-3 text-sm font-medium text-black transition hover:bg-gray-200">Get started</a>
        <a href="#features" class="rounded-full border border-white/5 bg-[#1F1F22] px-6 py-3 text-sm font-medium text-white transition hover:bg-[#2A2A2D]">Learn more</a>
      </div>

      <div class="mt-24 w-full">
        <p class="mb-8 text-center text-sm font-medium text-gray-500">Trusted by industry leaders</p>
        <div class="marquee-mask overflow-hidden">
          <div class="animate-marquee flex w-max items-center">
            <?php
              $brands = ['Springfield','Orbitc','Cloud','Amster','Nexus'];
              for ($r=0; $r<4; $r++) :
                foreach ($brands as $i => $brand) :
            ?>
              <div class="flex flex-shrink-0 items-center gap-3 px-8 text-gray-400">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <?php if ($i===0): ?><circle cx="14" cy="14" r="9" stroke="currentColor" stroke-width="1.5"/><path d="M9 14h10M14 9v10" stroke="currentColor" stroke-width="1.5"/>
                  <?php elseif ($i===1): ?><path d="M6 14c0-4.4 3.6-8 8-8s8 3.6 8 8-3.6 8-8 8-8-3.6-8-8Z" stroke="currentColor" stroke-width="1.5"/><path d="M10 14h8" stroke="currentColor" stroke-width="1.5"/>
                  <?php elseif ($i===2): ?><path d="M8.5 19h10a4 4 0 0 0 .5-7.97A6 6 0 0 0 7.4 12.7 3.5 3.5 0 0 0 8.5 19Z" stroke="currentColor" stroke-width="1.5"/>
                  <?php elseif ($i===3): ?><path d="M14 5 23 14 14 23 5 14 14 5Z" stroke="currentColor" stroke-width="1.5"/><circle cx="14" cy="14" r="3" stroke="currentColor" stroke-width="1.5"/>
                  <?php else: ?><path d="M6 20V8l8 7 8-7v12" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><?php endif; ?>
                </svg>
                <span class="text-base font-medium tracking-tight"><?php echo esc_html($brand); ?></span>
              </div>
            <?php endforeach; endfor; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURE 1 -->
  <section id="features" class="mx-auto grid max-w-7xl gap-16 px-6 py-24 lg:grid-cols-2 lg:items-center">
    <div class="reveal-up">
      <div class="mb-5 text-sm font-medium text-yellow-300">✨ AI chat</div>
      <h2 class="text-4xl font-semibold tracking-tight md:text-5xl">Where speed meets intelligent conversation.</h2>
      <p class="mt-6 max-w-xl leading-7 text-gray-400">A conversational AI assistant that understands your questions, provides intelligent answers, and helps you get things done fast from casual chats to complex tasks.</p>
      <a href="#contact" class="mt-8 inline-flex rounded-full bg-white px-6 py-3 text-sm font-medium text-black transition hover:bg-gray-200">Get started</a>
    </div>

    <div class="reveal-up relative min-h-[520px] overflow-hidden rounded-3xl border border-white/10 p-8">
      <video autoplay muted loop playsinline class="video-cover" preload="metadata">
        <source src="https://cdn.sceneai.art/Hero%20Section%20Video/1bcc8fa3-37f6-4c53-8591-0347e4c7f8ac.mp4" type="video/mp4" />
      </video>
      <div class="absolute inset-0 bg-black/20"></div>
      <div class="glass-card relative z-10 mt-28 rounded-2xl border border-white/10 p-4 shadow-2xl shadow-black/40">
        <div class="flex flex-wrap gap-2">
          <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-gray-300">Create image</span>
          <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-gray-300">Summarize</span>
          <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-gray-300">Brainstorm</span>
        </div>
        <div class="mt-28 flex items-center gap-3 rounded-2xl border border-white/10 bg-black/30 px-4 py-3">
          <span class="flex-1 text-sm text-gray-500">Ask anything...</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="text-gray-300"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v3"/></svg>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="text-gray-300"><path d="M4 12h1M8 9v6M12 6v12M16 9v6M20 12h1"/></svg>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURE 2 -->
  <section class="mx-auto grid max-w-7xl gap-16 px-6 py-24 lg:grid-cols-2 lg:items-center">
    <div class="reveal-up relative min-h-[520px] overflow-hidden rounded-3xl border border-white/10 p-8 lg:order-1">
      <video autoplay muted loop playsinline class="video-cover" preload="metadata">
        <source src="https://cdn.sceneai.art/Hero%20Section%20Video/736fd4a0-70ac-4f44-9633-55769ead6aca.mp4" type="video/mp4" />
      </video>
      <div class="absolute inset-0 bg-black/20"></div>
      <div class="glass-card relative z-10 mt-24 rounded-2xl border border-white/10 p-5 shadow-2xl shadow-black/40">
        <div class="flex items-center gap-4">
          <button class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-black" aria-label="Play audio">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7-11-7Z"/></svg>
          </button>
          <div>
            <div class="text-sm font-medium text-white">11:06 AM – Chris</div>
            <div class="mt-1 text-xs text-gray-500">Meeting recording</div>
          </div>
        </div>
        <div class="mt-6 flex h-20 items-center gap-1 overflow-hidden">
          <?php for($i=0;$i<52;$i++): $h = 12 + (($i*17)%52); ?>
            <span class="block w-1 rounded-full bg-white/40" style="height:<?php echo esc_attr($h); ?>px"></span>
          <?php endfor; ?>
        </div>
        <div class="mt-6 border-t border-white/10 pt-5 text-sm leading-6 text-gray-300">
          “The key is to reduce repetitive work while keeping the team in control. We can automate the first pass, then review only the moments that matter.”
        </div>
      </div>
    </div>

    <div class="reveal-up lg:order-2">
      <div class="mb-5 text-sm font-medium text-green-300">✨ AI transcription</div>
      <h2 class="text-4xl font-semibold tracking-tight md:text-5xl">Turn speech into text with speed and precision.</h2>
      <p class="mt-6 max-w-xl leading-7 text-gray-400">Automatically convert speech into accurate, editable text in real time. Perfect for meetings, interviews, voice notes, and more, powered by advanced speech recognition technology.</p>
      <a href="#contact" class="mt-8 inline-flex rounded-full bg-white px-6 py-3 text-sm font-medium text-black transition hover:bg-gray-200">Get started</a>
    </div>
  </section>

  <!-- FAQ -->
  <section id="faq" class="mx-auto max-w-3xl px-6 py-32">
    <h2 class="reveal-up mb-12 text-center text-4xl font-semibold tracking-tight md:text-5xl">We've got answers</h2>
    <div class="reveal-up overflow-hidden rounded-xl border border-white/10 bg-transparent">
      <?php
        $faqs = [
          ['Is my data safe with Plety?', 'Yes. Plety is designed around secure data handling, encrypted transport, and clear access controls so your team stays in control of sensitive information.'],
          ['Can Plety integrate with my existing stack?', 'Yes. The platform is designed to fit into existing workflows and can be connected to common business tools, APIs, and internal systems.'],
          ['Does Plety work for real-time use cases?', 'Yes. Plety is designed for responsive AI interactions including chat, transcription, and decision-support workflows where low-latency feedback matters.'],
          ['Can I customize the experience for my team?', 'Yes. You can tailor prompts, workflows, roles, and interface behavior to match how your team works.'],
          ['How do I get started?', 'Start with a focused workflow, connect your data or tools, and expand from there. The goal is to deliver useful automation without adding unnecessary complexity.']
        ];
        foreach ($faqs as $index => $faq):
      ?>
        <div class="faq-item <?php echo $index < count($faqs)-1 ? 'border-b border-white/10' : ''; ?>">
          <button type="button" class="faq-toggle flex w-full items-center justify-between gap-6 px-6 py-6 text-left" aria-expanded="false">
            <span class="text-base font-medium text-white"><?php echo esc_html($faq[0]); ?></span>
            <span class="faq-plus relative h-5 w-5 flex-shrink-0">
              <span class="absolute left-1/2 top-1/2 h-px w-5 -translate-x-1/2 -translate-y-1/2 bg-white"></span>
              <span class="absolute left-1/2 top-1/2 h-5 w-px -translate-x-1/2 -translate-y-1/2 bg-white"></span>
            </span>
          </button>
          <div class="faq-grid">
            <div>
              <p class="px-6 pb-6 text-sm leading-6 text-gray-400"><?php echo esc_html($faq[1]); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- FOOTER -->
  <footer id="contact" class="relative z-0 overflow-hidden border-t border-white/5 px-6 pb-10 pt-32">
    <video autoplay muted loop playsinline class="video-cover -z-10 opacity-40" preload="metadata">
      <source src="https://cdn.sceneai.art/Hero%20Section%20Video/50b4f304-cdca-4e12-8735-580d225834be.mp4" type="video/mp4" />
    </video>
    <div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-b from-black via-black/60 to-black"></div>

    <div class="reveal-up mx-auto mb-32 max-w-4xl text-center">
      <h2 class="text-4xl font-semibold tracking-tight md:text-6xl">Ready to automate <span class="font-serif font-normal italic">everything?</span></h2>
      <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
        <a href="#features" class="rounded-full bg-white px-6 py-3 text-sm font-medium text-black transition hover:bg-gray-200">Get started</a>
        <a href="#about" class="rounded-full border border-white/5 bg-[#1F1F22] px-6 py-3 text-sm font-medium text-white transition hover:bg-[#2A2A2D]">Learn more</a>
      </div>
    </div>

    <div class="mx-auto mb-24 grid max-w-7xl grid-cols-1 gap-8 md:grid-cols-4">
      <div>
        <div class="flex items-center gap-3">
          <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 29L20 7L32 29" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.5 22.5H27.5" stroke="white" stroke-width="2.2" stroke-linecap="round"/></svg>
          <span class="text-xl font-bold">Plety</span>
        </div>
        <p class="mt-4 max-w-xs text-sm leading-6 text-gray-400">Speed, scale, and smarts — deployed.</p>
      </div>

      <div>
        <h3 class="mb-4 text-sm font-medium text-white">Product</h3>
        <div class="flex flex-col gap-3">
          <a class="text-sm text-gray-400 transition-colors hover:text-white" href="#about">About</a>
          <a class="text-sm text-gray-400 transition-colors hover:text-white" href="#features">Pricing</a>
          <a class="text-sm text-gray-400 transition-colors hover:text-white" href="#features">Changelog</a>
          <a class="text-sm text-gray-400 transition-colors hover:text-white" href="#contact">Contact</a>
        </div>
      </div>

      <div>
        <h3 class="mb-4 text-sm font-medium text-white">Legal</h3>
        <div class="flex flex-col gap-3">
          <a class="text-sm text-gray-400 transition-colors hover:text-white" href="#">Terms of service</a>
          <a class="text-sm text-gray-400 transition-colors hover:text-white" href="#">Privacy policy</a>
          <a class="text-sm text-gray-400 transition-colors hover:text-white" href="#">404</a>
        </div>
      </div>

      <div>
        <h3 class="mb-4 text-sm font-medium text-white">Connect</h3>
        <div class="flex flex-col gap-3">
          <a class="text-sm text-gray-400 transition-colors hover:text-white" href="#">Instagram</a>
          <a class="text-sm text-gray-400 transition-colors hover:text-white" href="#">YouTube</a>
          <a class="text-sm text-gray-400 transition-colors hover:text-white" href="#">LinkedIn</a>
          <a class="text-sm text-gray-400 transition-colors hover:text-white" href="#">Twitter / X</a>
        </div>
      </div>
    </div>

    <div class="mx-auto flex max-w-7xl flex-col items-center justify-center gap-4 border-t border-white/5 pt-8 text-center text-xs text-gray-500 md:flex-row">
      <span>© 2026 Plety. All rights reserved</span>
      <span class="hidden md:inline">•</span>
      <span>by <span class="text-gray-300">Re-text</span></span>
      <span class="hidden md:inline">•</span>
      <span>Made in <span class="text-gray-300">Gemini</span></span>
    </div>
  </footer>
</div>

<script>
(function(){
  const nav = document.getElementById('siteNav');
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');

  function syncNav(){
    if(window.scrollY > 20) nav.classList.add('nav-solid');
    else nav.classList.remove('nav-solid');
  }
  syncNav();
  window.addEventListener('scroll', syncNav, {passive:true});

  menuBtn?.addEventListener('click', function(){
    const open = mobileMenu.classList.toggle('is-open');
    menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  document.querySelectorAll('.mobile-link').forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu.classList.remove('is-open');
      menuBtn.setAttribute('aria-expanded','false');
    });
  });

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, {threshold:0.15, rootMargin:'0px 0px -40px 0px'});
  document.querySelectorAll('.reveal-up').forEach(el => observer.observe(el));

  document.querySelectorAll('.faq-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.faq-item');
      const willOpen = !item.classList.contains('is-open');
      document.querySelectorAll('.faq-item.is-open').forEach(openItem => {
        if(openItem !== item){
          openItem.classList.remove('is-open');
          openItem.querySelector('.faq-toggle')?.setAttribute('aria-expanded','false');
        }
      });
      item.classList.toggle('is-open', willOpen);
      btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    });
  });
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
