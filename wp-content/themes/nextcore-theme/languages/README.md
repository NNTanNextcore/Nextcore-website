# Nextcore Theme Languages

Text domain: `nextcore-theme`.

## Quy uoc

- PHP chi khai bao chuoi nguon tieng Viet bang cac ham WordPress i18n.
- Khong kiem tra locale va khong khai bao cap chuoi VI/EN truc tiep trong template.
- Chuoi dich tieng Anh duoc quan ly trong `nextcore-theme-en_US.po`.
- WordPress doc ban dich runtime tu `nextcore-theme-en_US.mo`.
- `nextcore-theme.pot` la template chuoi nguon de cap nhat catalog.

TranslatePress giu nguyen WordPress locale tren URL da dich. Vi vay
`nextcore_load_current_language_catalog()` trong `inc/multilingual.php` nap catalog
phu hop voi ngon ngu hien tai cua TranslatePress.

Sau khi sua file PO, can bien dich lai file MO truoc khi kiem tra giao dien.
