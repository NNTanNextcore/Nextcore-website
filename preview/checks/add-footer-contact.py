from pathlib import Path
root=Path(__file__).resolve().parents[1]
icons=[ '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>', '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 6 9 7 9-7"/>', '<path d="m7 3 3 5-3 3a16 16 0 0 0 6 6l3-3 5 3-1 4C10 23 1 14 3 4Z"/>' ]
svg=lambda i:'<svg viewBox="0 0 24 24" aria-hidden="true">'+icons[i]+'</svg>'
block='<div class="footer-contact"><h2>Liên hệ</h2><address><p>'+svg(0)+'<span>63 Phan Đăng Lưu Street, Hai Chau, Da Nang 550000, Viet Nam</span></p><a href="mailto:info@nextcore.vn">'+svg(1)+'<span>info@nextcore.vn</span></a><a href="tel:+84378962625">'+svg(2)+'<span>+84378962625</span></a></address></div>'
for name in ['index.html','index-light.html']:
 p=root/name
 s=p.read_text(encoding='utf-8')
 start=s.index('<footer class="site-footer">'); end=s.index('</footer>',start)+len('</footer>')
 footer=s[start:end]
 assert 'footer-contact' not in footer
 copyright='<small>© 2026 Nextcore. All rights reserved.</small>'
 footer=footer.replace(copyright,'').replace('<div><h2>Kết nối</h2>',block+'<div><h2>Kết nối</h2>')
 footer=footer.replace('</div></div><p class="footer-motto">','</div><p class="footer-motto">')
 footer=footer.replace('</p></div></footer>','</p></div><small class="footer-copyright">© 2026 Nextcore. All rights reserved.</small></div></footer>')
 s=s[:start]+footer+s[end:]
 s=s.replace('</head>','  <link rel="stylesheet" href="assets/css/footer.css">\n</head>')
 p.write_text(s,encoding='utf-8')
