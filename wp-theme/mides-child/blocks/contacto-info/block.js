(function () {
  var registerBlockType = wp.blocks.registerBlockType;
  var el                = wp.element.createElement;
  var useBlockProps     = wp.blockEditor.useBlockProps;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var MediaUpload       = wp.blockEditor.MediaUpload;
  var MediaUploadCheck  = wp.blockEditor.MediaUploadCheck;
  var PanelBody         = wp.components.PanelBody;
  var TextControl       = wp.components.TextControl;
  var Button            = wp.components.Button;

  function iconControl(label, idKey, urlKey, attrs, setAttrs) {
    return el('div', { style: { marginBottom: '12px' } },
      el('p', { style: { marginBottom: '4px', fontWeight: 600 } }, label),
      el(MediaUploadCheck, null,
        el(MediaUpload, {
          onSelect: function (media) { setAttrs({ [idKey]: media.id, [urlKey]: media.url }); },
          allowedTypes: ['image'],
          value: attrs[idKey] || undefined,
          render: function (ref) {
            return el('div', null,
              attrs[urlKey] && el('img', {
                src: attrs[urlKey],
                alt: '',
                style: { display: 'block', width: '40px', height: '40px', objectFit: 'contain', marginBottom: '6px' }
              }),
              el(Button, { onClick: ref.open, variant: 'secondary', style: { marginRight: '6px' } },
                attrs[idKey] ? 'Cambiar ícono' : 'Seleccionar ícono'
              ),
              attrs[idKey] && el(Button, {
                onClick: function () { setAttrs({ [idKey]: 0, [urlKey]: '' }); },
                variant: 'link',
                isDestructive: true
              }, 'Quitar')
            );
          }
        })
      )
    );
  }

  registerBlockType('mides/contacto-info', {
    edit: function (props) {
      var attrs    = props.attributes;
      var setAttrs = props.setAttributes;
      var bProps   = useBlockProps({ className: 'mides-editor-placeholder' });

      return el(
        'div', bProps,

        el(InspectorControls, null,

          el(PanelBody, { title: 'Títulos de tarjetas', initialOpen: true },
            el(TextControl, {
              label: 'Título — Dirección',
              value: attrs.tituloDir,
              onChange: function (v) { setAttrs({ tituloDir: v }); }
            }),
            el(TextControl, {
              label: 'Título — Teléfonos',
              value: attrs.tituloTel,
              onChange: function (v) { setAttrs({ tituloTel: v }); }
            }),
            el(TextControl, {
              label: 'Título — Correo',
              value: attrs.tituloMail,
              onChange: function (v) { setAttrs({ tituloMail: v }); }
            })
          ),

          el(PanelBody, { title: 'Íconos de tarjetas', initialOpen: false },
            iconControl('Ícono Dirección',  'iconDirId',  'iconDirUrl',  attrs, setAttrs),
            iconControl('Ícono Teléfonos',  'iconTelId',  'iconTelUrl',  attrs, setAttrs),
            iconControl('Ícono Correo',     'iconMailId', 'iconMailUrl', attrs, setAttrs)
          ),

          el(PanelBody, { title: 'Dirección', initialOpen: true },
            el(TextControl, {
              label: 'Línea 1',
              value: attrs.direccion1,
              onChange: function (v) { setAttrs({ direccion1: v }); }
            }),
            el(TextControl, {
              label: 'Línea 2',
              value: attrs.direccion2,
              onChange: function (v) { setAttrs({ direccion2: v }); }
            }),
            el(TextControl, {
              label: 'Edificio',
              value: attrs.edificio,
              onChange: function (v) { setAttrs({ edificio: v }); }
            }),
            el(TextControl, {
              label: 'Horario de atención',
              value: attrs.horario,
              onChange: function (v) { setAttrs({ horario: v }); }
            })
          ),

          el(PanelBody, { title: 'Teléfonos', initialOpen: true },
            el(TextControl, {
              label: 'Teléfono 1',
              value: attrs.telefono1,
              onChange: function (v) { setAttrs({ telefono1: v }); }
            }),
            el(TextControl, {
              label: 'Teléfono 2',
              value: attrs.telefono2,
              onChange: function (v) { setAttrs({ telefono2: v }); }
            }),
            el(TextControl, {
              label: 'Teléfono 3',
              value: attrs.telefono3,
              onChange: function (v) { setAttrs({ telefono3: v }); }
            })
          ),

          el(PanelBody, { title: 'Correo electrónico', initialOpen: true },
            el(TextControl, {
              label: 'Correo',
              value: attrs.correo,
              onChange: function (v) { setAttrs({ correo: v }); }
            })
          )
        ),

        el('span', { style: { fontSize: '28px' } }, '📋'),
        el('strong', { style: { display: 'block', marginTop: '8px' } }, 'Tarjetas de Contacto'),
        el('span', { style: { fontSize: '13px', color: '#64748B' } },
          attrs.direccion1 + ' · ' + attrs.telefono1 + ' · ' + attrs.correo
        )
      );
    }
  });
})();
