(function ($) {
  'use strict';

  var slideIndex = $('#hero-slides-wrap .hero-slide-row').length;

  /* ── Plantilla de un slide vacío ───────────────────────────────────────── */
  function slideTemplate(i) {
    return `
      <div class="hero-slide-row" data-index="${i}">
        <div class="slide-thumb-wrap">
          <div class="no-img">Sin imagen</div>
          <input type="hidden" name="hero_slides[${i}][bg_id]" value="" class="slide-bg-id" />
          <button type="button" class="button slide-pick-img" style="width:100%">📷 Imagen de fondo</button>
        </div>
        <div class="slide-fields">
          <div>
            <label>Texto pre-título (pequeño, encima)</label>
            <input type="text" name="hero_slides[${i}][pre]" placeholder="ACCIONES QUE" />
          </div>
          <div>
            <label>Título principal (grande, azul)</label>
            <input type="text" name="hero_slides[${i}][title]" placeholder="CAMBIAN VIDAS" />
          </div>
          <div>
            <label>Subtítulo línea 1</label>
            <input type="text" name="hero_slides[${i}][sub1]" placeholder="Y FORTALECE EL" />
          </div>
          <div>
            <label>Subtítulo línea 2</label>
            <input type="text" name="hero_slides[${i}][sub2]" placeholder="BIENESTAR FAMILIAR" />
          </div>
        </div>
        <button type="button" class="slide-remove" title="Eliminar slide">🗑 Eliminar</button>
      </div>`;
  }

  /* ── Agregar slide ─────────────────────────────────────────────────────── */
  $('#add-hero-slide').on('click', function () {
    $('#hero-slides-wrap').append(slideTemplate(slideIndex));
    slideIndex++;
  });

  /* ── Eliminar slide ────────────────────────────────────────────────────── */
  $('#hero-slides-wrap').on('click', '.slide-remove', function () {
    if (!confirm('¿Eliminar este slide?')) return;
    $(this).closest('.hero-slide-row').remove();
    reindex();
  });

  /* ── Selector de imagen (WordPress Media Library) ─────────────────────── */
  $('#hero-slides-wrap').on('click', '.slide-pick-img', function () {
    var $row   = $(this).closest('.hero-slide-row');
    var $idInput = $row.find('.slide-bg-id');
    var $wrap  = $row.find('.slide-thumb-wrap');

    var frame = wp.media({
      title:    'Seleccionar imagen de fondo',
      button:   { text: 'Usar esta imagen' },
      multiple: false,
      library:  { type: 'image' }
    });

    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      $idInput.val(attachment.id);

      // Mostrar miniatura
      var thumb = attachment.sizes && attachment.sizes.thumbnail
        ? attachment.sizes.thumbnail.url
        : attachment.url;

      $wrap.find('.no-img, img.slide-preview-img').remove();
      $wrap.prepend('<img src="' + thumb + '" class="slide-preview-img" />');
    });

    frame.open();
  });

  /* ── Reindexar names después de eliminar ──────────────────────────────── */
  function reindex() {
    $('#hero-slides-wrap .hero-slide-row').each(function (i) {
      $(this).attr('data-index', i);
      $(this).find('[name]').each(function () {
        var name = $(this).attr('name').replace(/hero_slides\[\d+\]/, 'hero_slides[' + i + ']');
        $(this).attr('name', name);
      });
    });
    slideIndex = $('#hero-slides-wrap .hero-slide-row').length;
  }

})(jQuery);
