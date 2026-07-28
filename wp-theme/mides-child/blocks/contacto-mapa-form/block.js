(function () {
  var registerBlockType = wp.blocks.registerBlockType;
  var el                = wp.element.createElement;
  var useBlockProps     = wp.blockEditor.useBlockProps;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var PanelBody         = wp.components.PanelBody;
  var TextControl       = wp.components.TextControl;
  var TextareaControl   = wp.components.TextareaControl;

  registerBlockType('mides/contacto-mapa-form', {
    edit: function (props) {
      var attrs    = props.attributes;
      var setAttrs = props.setAttributes;
      var bProps   = useBlockProps({ className: 'mides-editor-placeholder' });

      return el(
        'div', bProps,

        el(InspectorControls, null,

          el(PanelBody, { title: 'Mapa', initialOpen: true },
            el(TextControl, {
              label: 'Título del mapa',
              value: attrs.tituloMapa,
              onChange: function (v) { setAttrs({ tituloMapa: v }); }
            }),
            el(TextareaControl, {
              label: 'URL del embed de Google Maps',
              value: attrs.mapaUrl,
              help: 'URL del iframe de Google Maps (output=embed)',
              onChange: function (v) { setAttrs({ mapaUrl: v }); }
            }),
            el(TextControl, {
              label: 'Texto del pie de mapa',
              value: attrs.mapaPie,
              onChange: function (v) { setAttrs({ mapaPie: v }); }
            })
          ),

          el(PanelBody, { title: 'Formulario', initialOpen: true },
            el(TextControl, {
              label: 'ID del formulario WPForms',
              type: 'number',
              value: attrs.formId,
              onChange: function (v) { setAttrs({ formId: parseInt(v, 10) || 0 }); }
            }),
            el(TextControl, {
              label: 'Título del formulario',
              value: attrs.formTitulo,
              onChange: function (v) { setAttrs({ formTitulo: v }); }
            }),
            el(TextareaControl, {
              label: 'Descripción del formulario',
              value: attrs.formDescripcion,
              onChange: function (v) { setAttrs({ formDescripcion: v }); }
            })
          )
        ),

        el('span', { style: { fontSize: '28px' } }, '🗺️'),
        el('strong', { style: { display: 'block', marginTop: '8px' } }, 'Mapa + Formulario de Contacto'),
        el('span', { style: { fontSize: '13px', color: '#64748B' } },
          'WPForms ID: ' + attrs.formId + ' · ' + attrs.mapaPie
        )
      );
    }
  });
})();
