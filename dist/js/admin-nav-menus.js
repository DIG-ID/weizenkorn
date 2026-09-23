/******/ (() => { // webpackBootstrap
/*!**************************************!*\
  !*** ./assets/js/admin-nav-menus.js ***!
  \**************************************/
/**
 * Nav menu item "Image" field (Appearance > Menus): wires the media uploader
 * to the hidden input rendered by weizenkorn_nav_menu_item_image_field().
 * Plain JS (no jQuery) so this small admin-only bundle stays lean.
 */

/* globals wp */
document.addEventListener('click', function (e) {
  var selectButton = e.target.closest('.weizenkorn-menu-item-image-select');
  var removeButton = e.target.closest('.weizenkorn-menu-item-image-remove');
  if (selectButton) {
    e.preventDefault();
    var field = selectButton.parentElement.querySelector('input[type="hidden"]');
    var preview = selectButton.parentElement.querySelector('.weizenkorn-menu-item-image-preview');
    var remove = selectButton.parentElement.querySelector('.weizenkorn-menu-item-image-remove');
    var frame = wp.media({
      title: selectButton.dataset.title,
      multiple: false
    });
    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      field.value = attachment.id;
      preview.innerHTML = '<img src="' + attachment.url + '" alt="" />';
      remove.style.display = '';
    });
    frame.open();
  }
  if (removeButton) {
    e.preventDefault();
    var _field = removeButton.parentElement.querySelector('input[type="hidden"]');
    var _preview = removeButton.parentElement.querySelector('.weizenkorn-menu-item-image-preview');
    _field.value = '';
    _preview.innerHTML = '';
    removeButton.style.display = 'none';
  }
});
/******/ })()
;
//# sourceMappingURL=admin-nav-menus.js.map