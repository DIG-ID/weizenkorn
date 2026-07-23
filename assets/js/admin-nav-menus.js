/**
 * Nav menu item "Image" field (Appearance > Menus): wires the media uploader
 * to the hidden input rendered by weizenkorn_nav_menu_item_image_field().
 * Plain JS (no jQuery) so this small admin-only bundle stays lean.
 */

/* globals wp */
document.addEventListener('click', function (e) {
  const selectButton = e.target.closest('.weizenkorn-menu-item-image-select');
  const removeButton = e.target.closest('.weizenkorn-menu-item-image-remove');

  if (selectButton) {
    e.preventDefault();

    const field = selectButton.parentElement.querySelector('input[type="hidden"]');
    const preview = selectButton.parentElement.querySelector('.weizenkorn-menu-item-image-preview');
    const remove = selectButton.parentElement.querySelector('.weizenkorn-menu-item-image-remove');

    const frame = wp.media({
      title: selectButton.dataset.title,
      multiple: false,
    });

    frame.on('select', function () {
      const attachment = frame.state().get('selection').first().toJSON();
      field.value = attachment.id;
      preview.innerHTML = '<img src="' + attachment.url + '" alt="" />';
      remove.style.display = '';
    });

    frame.open();
  }

  if (removeButton) {
    e.preventDefault();

    const field = removeButton.parentElement.querySelector('input[type="hidden"]');
    const preview = removeButton.parentElement.querySelector('.weizenkorn-menu-item-image-preview');

    field.value = '';
    preview.innerHTML = '';
    removeButton.style.display = 'none';
  }
});
