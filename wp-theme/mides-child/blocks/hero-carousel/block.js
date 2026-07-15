(function () {
  var registerBlockType = wp.blocks.registerBlockType;
  var el                = wp.element.createElement;
  var Fragment          = wp.element.Fragment;
  var useBlockProps     = wp.blockEditor.useBlockProps;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var MediaUpload       = wp.blockEditor.MediaUpload;
  var MediaUploadCheck  = wp.blockEditor.MediaUploadCheck;
  var PanelBody         = wp.components.PanelBody;
  var TextControl       = wp.components.TextControl;
  var RangeControl      = wp.components.RangeControl;
  var Button            = wp.components.Button;
  var Placeholder       = wp.components.Placeholder;

  registerBlockType('mides/hero-carousel', {

    edit: function (props) {
      var slides      = props.attributes.slides;
      var interval    = props.attributes.interval;
      var blockProps  = useBlockProps({ className: 'mides-hero-block-wrap' });

      /* ── Helpers ── */
      function setSlide(index, key, value) {
        props.setAttributes({
          slides: slides.map(function (s, i) {
            if (i !== index) return s;
            var copy = Object.assign({}, s);
            copy[key] = value;
            return copy;
          })
        });
      }

      function addSlide() {
        props.setAttributes({
          slides: slides.concat([{ bgId: 0, bgUrl: '', pre: '', title: '', sub1: '', sub2: '' }])
        });
      }

      function removeSlide(index) {
        props.setAttributes({ slides: slides.filter(function (_, i) { return i !== index; }) });
      }

      /* ── Panel lateral ── */
      var inspector = el(InspectorControls, null,

        el(PanelBody, { title: 'Configuración', initialOpen: true },
          el(RangeControl, {
            label: 'Tiempo por slide (segundos)',
            value: interval,
            onChange: function (v) { props.setAttributes({ interval: v }); },
            min: 2,
            max: 20,
            step: 1
          })
        ),

        el(PanelBody, { title: '+ Agregar slide', initialOpen: slides.length === 0 },
          el(Button, { onClick: addSlide, variant: 'primary', style: { width: '100%' } },
            '+ Agregar slide'
          )
        ),

        slides.map(function (slide, i) {
          return el(PanelBody, {
            key: i,
            title: 'Slide ' + (i + 1) + (slide.title ? ' — ' + slide.title : ''),
            initialOpen: i === 0
          },
            /* Imagen de fondo */
            el(MediaUploadCheck, null,
              el(MediaUpload, {
                onSelect: function (media) {
                  setSlide(i, 'bgId', media.id);
                  setSlide(i, 'bgUrl', media.url);
                },
                allowedTypes: ['image'],
                value: slide.bgId || undefined,
                render: function (ref) {
                  return el(Fragment, null,
                    slide.bgUrl && el('img', {
                      src: slide.bgUrl,
                      style: { width: '100%', height: '70px', objectFit: 'cover', borderRadius: '4px', marginBottom: '8px', display: 'block' }
                    }),
                    el(Button, {
                      onClick: ref.open,
                      variant: 'secondary',
                      style: { width: '100%', marginBottom: '12px' }
                    }, slide.bgUrl ? '📷 Cambiar imagen' : '📷 Seleccionar imagen de fondo')
                  );
                }
              })
            ),

            el(TextControl, { label: 'Pre-título',  value: slide.pre,   onChange: function (v) { setSlide(i, 'pre',   v); }, placeholder: 'ACCIONES QUE'     }),
            el(TextControl, { label: 'Título',       value: slide.title, onChange: function (v) { setSlide(i, 'title', v); }, placeholder: 'CAMBIAN VIDAS'     }),
            el(TextControl, { label: 'Subtítulo 1', value: slide.sub1,  onChange: function (v) { setSlide(i, 'sub1',  v); }, placeholder: 'Y FORTALECE EL'    }),
            el(TextControl, { label: 'Subtítulo 2', value: slide.sub2,  onChange: function (v) { setSlide(i, 'sub2',  v); }, placeholder: 'BIENESTAR FAMILIAR' }),

            el(Button, {
              onClick: function () { if (window.confirm('¿Eliminar slide ' + (i + 1) + '?')) removeSlide(i); },
              variant: 'link',
              isDestructive: true
            }, '🗑 Eliminar slide')
          );
        })
      );

      /* ── Preview en el editor ── */
      var preview = slides.length === 0
        ? el(Placeholder, { icon: 'images-alt2', label: 'Hero Carrusel', instructions: 'Agrega el primer slide desde el panel derecho →' },
            el(Button, { onClick: addSlide, variant: 'primary' }, '+ Agregar slide')
          )
        : el('div', { className: 'mides-hero-editor-preview' },
            el('div', {
              className: 'mides-hero-editor-preview__bg',
              style: slides[0].bgUrl ? { backgroundImage: 'url(' + slides[0].bgUrl + ')' } : {}
            }),
            el('div', { className: 'mides-hero-editor-preview__content' },
              slides[0].pre   && el('p',  { className: 'hero__pre'   }, slides[0].pre),
              slides[0].title && el('h2', { className: 'hero__title' }, slides[0].title),
              slides[0].sub1  && el('p',  { className: 'hero__sub'   }, slides[0].sub1),
              slides[0].sub2  && el('p',  { className: 'hero__sub hero__sub--bold' }, slides[0].sub2)
            ),
            slides.length > 1 && el('div', { className: 'mides-hero-editor-preview__badge' },
              '🎠 ' + slides.length + ' slides'
            )
          );

      return el(Fragment, null,
        inspector,
        el('div', blockProps, preview)
      );
    },

    save: function () { return null; }
  });
})();
