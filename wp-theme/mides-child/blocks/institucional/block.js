(function () {
  var registerBlockType = wp.blocks.registerBlockType;
  var el                = wp.element.createElement;
  var useBlockProps     = wp.blockEditor.useBlockProps;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var MediaUpload       = wp.blockEditor.MediaUpload;
  var MediaUploadCheck  = wp.blockEditor.MediaUploadCheck;
  var PanelBody         = wp.components.PanelBody;
  var TextControl       = wp.components.TextControl;
  var TextareaControl   = wp.components.TextareaControl;
  var Button            = wp.components.Button;

  var CHAR_LIMIT = 160;

  registerBlockType('mides/institucional', {

    edit: function (props) {
      var attrs    = props.attributes;
      var setAttrs = props.setAttributes;
      var cards    = attrs.cards;
      var blockProps = useBlockProps({ className: 'mides-institucional-editor' });

      function setCard(index, key, value) {
        setAttrs({
          cards: cards.map(function (c, i) {
            if (i !== index) return c;
            var copy = Object.assign({}, c);
            copy[key] = value;
            return copy;
          })
        });
      }

      function addCard() {
        setAttrs({
          cards: cards.concat([{
            nombre: 'Nueva tarjeta', descripcion: '', iconId: 0, iconUrl: '', iconAlt: '', link: '', linkText: ''
          }])
        });
      }

      function removeCard(index) {
        setAttrs({ cards: cards.filter(function (_, i) { return i !== index; }) });
      }

      return el(
        'div', blockProps,

        el(InspectorControls, null,

          el(PanelBody, { title: 'Encabezado', initialOpen: true },
            el(TextControl, {
              label: 'Título',
              value: attrs.titulo,
              onChange: function (v) { setAttrs({ titulo: v }); }
            }),
            el(TextareaControl, {
              label: 'Texto introductorio',
              value: attrs.intro,
              rows: 4,
              onChange: function (v) { setAttrs({ intro: v }); }
            })
          ),

          el(PanelBody, { title: 'Tarjetas', initialOpen: true },
            cards.map(function (card, i) {
              return el('div', { key: i, className: 'mides-card-panel' },
                el('p', { className: 'mides-card-panel__title' }, (i + 1) + '. ' + (card.nombre || 'Sin nombre')),

                el(TextControl, {
                  label: 'Nombre',
                  value: card.nombre,
                  onChange: function (v) { setCard(i, 'nombre', v); }
                }),
                el(TextareaControl, {
                  label: 'Descripción (reverso)',
                  value: card.descripcion,
                  rows: 4,
                  onChange: function (v) { setCard(i, 'descripcion', v); }
                }),
                el('p', {
                  style: {
                    fontSize: '11px',
                    margin: '-8px 0 8px',
                    color: card.descripcion.length > CHAR_LIMIT ? '#cc0000' : '#757575'
                  }
                }, card.descripcion.length + ' / ' + CHAR_LIMIT + ' caracteres' + (card.descripcion.length > CHAR_LIMIT ? ' — el texto se verá cortado en la card' : '')),

                el('p', { style: { fontSize: '11px', fontWeight: '600', margin: '4px 0' } }, 'Ícono'),
                card.iconUrl && el('img', {
                  src: card.iconUrl,
                  className: 'mides-card-panel__icon-preview'
                }),
                el(MediaUploadCheck, null,
                  el(MediaUpload, {
                    onSelect: function (media) {
                      setCard(i, 'iconId', media.id);
                      setCard(i, 'iconUrl', media.url);
                      setCard(i, 'iconAlt', media.alt || card.nombre);
                    },
                    allowedTypes: ['image'],
                    value: card.iconId,
                    render: function (ref) {
                      return el(Button, {
                        onClick: ref.open,
                        variant: 'secondary',
                        style: { fontSize: '11px', marginBottom: '4px' }
                      }, card.iconId ? 'Cambiar ícono' : 'Subir ícono');
                    }
                  })
                ),
                el(TextControl, {
                  label: 'URL ícono (alternativa)',
                  value: card.iconUrl,
                  placeholder: 'https://…',
                  onChange: function (v) { setCard(i, 'iconUrl', v); }
                }),

                el(TextControl, {
                  label: 'Enlace (opcional)',
                  value: card.link,
                  placeholder: 'https://…',
                  onChange: function (v) { setCard(i, 'link', v); }
                }),
                card.link && el(TextControl, {
                  label: 'Texto del enlace',
                  value: card.linkText,
                  onChange: function (v) { setCard(i, 'linkText', v); }
                }),

                el(Button, {
                  onClick: function () { removeCard(i); },
                  variant: 'tertiary',
                  isDestructive: true,
                  style: { fontSize: '11px', marginTop: '4px' }
                }, 'Eliminar tarjeta')
              );
            }),

            el(Button, {
              onClick: addCard,
              variant: 'secondary',
              style: { marginTop: '8px', width: '100%' }
            }, '+ Agregar tarjeta')
          ),

          el(PanelBody, { title: 'Botón "Conocer más"', initialOpen: false },
            el(TextControl, {
              label: 'Texto',
              value: attrs.textoVerMas,
              onChange: function (v) { setAttrs({ textoVerMas: v }); }
            }),
            el(TextControl, {
              label: 'URL',
              value: attrs.urlVerMas,
              type: 'url',
              placeholder: 'https://…',
              onChange: function (v) { setAttrs({ urlVerMas: v }); }
            })
          )
        ),

        /* Preview en el editor */
        el('div', { className: 'mides-institucional-editor__header' },
          el('h2', { className: 'mides-institucional-editor__title' }, attrs.titulo),
          el('p', { className: 'mides-institucional-editor__intro' }, attrs.intro)
        ),
        el('div', { className: 'mides-institucional-editor__cards' },
          cards.map(function (card, i) {
            return el('div', { key: i, className: 'mides-institucional-editor__card' },
              card.iconUrl && el('img', { src: card.iconUrl, style: { width: '36px', height: '36px', objectFit: 'contain', display: 'block', margin: '0 auto 6px' } }),
              card.nombre
            );
          })
        ),
        attrs.textoVerMas && el('div', { className: 'mides-institucional-editor__cta' },
          el('span', { style: { background: '#192854', color: '#fff', padding: '8px 20px', borderRadius: '6px', fontSize: '12px' } },
            attrs.textoVerMas
          )
        )
      );
    },

    save: function () { return null; }
  });
})();
