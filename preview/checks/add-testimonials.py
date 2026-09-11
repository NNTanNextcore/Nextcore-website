from pathlib import Path
import html, shutil, json
root = Path(__file__).resolve().parents[1]
data = [
('Công ty An Tâm Việt', 'Dự án Plugin Affiliate', 'logo-footer-2048x509-1.png', 'Nextcore để lại ấn tượng mạnh mẽ với đội ngũ hỗ trợ chuyên nghiệp, luôn đồng hành từ khâu tư vấn ban đầu đến việc sắp xếp kế hoạch và triển khai dự án. Phản hồi nhanh chóng, thông tin rõ ràng giúp chúng tôi hoàn toàn yên tâm khi làm việc cùng họ. Đặc biệt, sự uy tín và cam kết trách nhiệm với deadline của Nextcore là yếu tố nổi bật. Không thể không nhắc đến anh Tú, Project Manager của đội, người luôn tận tâm tư vấn và hỗ trợ nhiệt tình, mang đến trải nghiệm làm việc tuyệt vời.\nNextcore không chỉ là một đối tác, mà còn là một người bạn đồng hành đáng tin cậy trên hành trình phát triển của chúng tôi!'),
('Phạm Việt Hùng', 'Dự án Tạo template ebook', '03-512.webp', 'Hoàn thành công việc nhanh và chất lượng. Nhiệt tình hỗ trợ sau khi hoàn thành công việc'),
('Nguyễn Phương Trà My', 'Dự án Convert nghiệp vụ sang sơ đồ khối', '91649367-profil-der-jungen-frau-cartoon-symbol-vektor-illustration-grafik-design.jpg', 'Công ty Phần mềm Next Core làm dự án của tôi rất chuyên nghiệp, thực hiện đúng yêu cầu của khách hàng và hoàn thành công việc sớm hơn thời gian quy định, đảm bảo chất lượng. Các bạn dev của Công ty hỗ trợ tôi rất nhiệt tình trong thời gian thực hiện yêu cầu của tôi. Highly recommend cho các khách hàng trong lĩnh vực IT, phần mềm, design.'),
('Bé khỏe bé vui', 'Dự án phục hồi website bị tấn công', 'ybadien-tu.png', 'Highly appreciate your working spirit. I am pleased to choose you as partners. We will continue to cooperate in the future when there are new projects.')]
cards = []
for i,(name,project,asset,quote) in enumerate(data):
    dest = 'testimonial-' + str(i+1) + Path(asset).suffix
    shutil.copyfile(root.parent/'wp-content/uploads/2025/02'/asset, root/'assets/images'/dest)
    cards.append(f'''<article class="testimonial-card"><div class="testimonial-person"><img src="assets/images/{dest}" alt="{html.escape(name)}" width="64" height="64" loading="lazy"><div><h3>{name}</h3><p>{project}</p></div></div><blockquote>{html.escape(quote)}</blockquote><button class="testimonial-more" type="button">Xem thêm<span class="sr-only"> — {name}</span></button></article>''')
section = '''    <section class="testimonials section" id="testimonials" aria-labelledby="testimonials-title">
      <div class="container">
        <div class="testimonial-heading" data-reveal><p class="eyebrow">KHÁCH HÀNG NÓI VỀ NEXTCORE</p><h2 id="testimonials-title">Đánh giá từ khách hàng</h2><p>Những chia sẻ thực tế từ khách hàng đang đồng hành cùng Nextcore.</p></div>
        <div class="testimonial-carousel" role="region" aria-roledescription="carousel" aria-label="Đánh giá từ khách hàng">
          <button class="testimonial-prev" type="button" aria-label="Đánh giá trước">←</button>
          <div class="testimonial-track" tabindex="0" aria-label="Vuốt để xem các đánh giá">'''+''.join(cards)+'''</div>
          <button class="testimonial-next" type="button" aria-label="Đánh giá tiếp theo">→</button>
        </div>
        <div class="testimonial-dots" aria-label="Chọn nhóm đánh giá"></div>
      </div>
    </section>
    <dialog class="testimonial-dialog" aria-labelledby="testimonial-dialog-title"><button class="testimonial-close" type="button" aria-label="Đóng đánh giá">×</button><h2 id="testimonial-dialog-title"></h2><p class="testimonial-dialog-project"></p><blockquote></blockquote></dialog>

'''
for file in ['index.html','index-light.html']:
    path=root/file
    source=path.read_text(encoding='utf-8')
    marker=source.index('    <section', source.index('id="partner"')+1)
    source=source[:marker]+section+source[marker:]
    source=source.replace('</head>', '  <link rel="stylesheet" href="assets/css/testimonials.css">\n  <script src="assets/js/testimonials.js" defer></script>\n</head>')
    path.write_text(source,encoding='utf-8')
(root/'checks/testimonial-source.json').write_text(json.dumps({'source':'https://nextcore.vn/','retrieved':'2026-09-10','items':[dict(customer_name=n,project_name=p,original_asset=a,testimonial_content=q) for n,p,a,q in data]}, ensure_ascii=False,indent=2),encoding='utf-8')
