(function () {
  var registerBlockType = wp.blocks.registerBlockType;
  var el                = wp.element.createElement;
  var useBlockProps     = wp.blockEditor.useBlockProps;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var PanelBody         = wp.components.PanelBody;
  var TextControl       = wp.components.TextControl;
  var TextareaControl   = wp.components.TextareaControl;
  var SelectControl     = wp.components.SelectControl;

  var ICONOS = [
    { label: 'Lista',     value: 'lista' },
    { label: 'Documento', value: 'documento' },
    { label: 'Personas',  value: 'personas' },
    { label: 'Búsqueda',  value: 'busqueda' },
    { label: 'Chat',      value: 'chat' },
  ];
  var COLORES = [
    { label: 'Azul',  value: 'blue' },
    { label: 'Ámbar', value: 'amber' },
  ];

  function cardPanel(label, prefix, attrs, setAttrs) {
    function set(key, val) { var o = {}; o[prefix + key] = val; setAttrs(o); }
    return el(PanelBody, { title: label, initialOpen: false },
      el(TextControl,     { label: 'Categoría',   value: attrs[prefix + 'Cat'],    onChange: function (v) { set('Cat',    v); } }),
      el(TextareaControl, { label: 'Descripción', value: attrs[prefix + 'Desc'],   onChange: function (v) { set('Desc',   v); } }),
      el(SelectControl,   { label: 'Ícono',       value: attrs[prefix + 'Icono'],  options: ICONOS,  onChange: function (v) { set('Icono',  v); } }),
      el(SelectControl,   { label: 'Color',       value: attrs[prefix + 'Color'],  options: COLORES, onChange: function (v) { set('Color',  v); } }),
      el('hr', { style: { margin: '10px 0', border: 'none', borderTop: '1px solid #E2E8F0' } }),
      el(TextControl, { label: 'Enlace 1 — Texto', value: attrs[prefix + 'L1Texto'], onChange: function (v) { set('L1Texto', v); } }),
      el(TextControl, { label: 'Enlace 1 — URL',   value: attrs[prefix + 'L1Url'],   onChange: function (v) { set('L1Url',   v); } }),
      el(TextControl, { label: 'Enlace 2 — Texto (opcional)', value: attrs[prefix + 'L2Texto'], onChange: function (v) { set('L2Texto', v); } }),
      el(TextControl, { label: 'Enlace 2 — URL (opcional)',   value: attrs[prefix + 'L2Url'],   onChange: function (v) { set('L2Url',   v); } })
    );
  }

  registerBlockType('mides/tramites-grid', {
    edit: function (props) {
      var attrs    = props.attributes;
      var setAttrs = props.setAttributes;
      var bProps   = useBlockProps({ className: 'mides-tramites-editor' });

      return el('div', bProps,
        el(InspectorControls, null,
          el(PanelBody, { title: 'Encabezado de sección', initialOpen: true },
            el(TextControl, {
              label: 'Título',
              value: attrs.tituloSeccion,
              onChange: function (v) { setAttrs({ tituloSeccion: v }); }
            }),
            el(TextareaControl, {
              label: 'Introducción',
              value: attrs.introTexto,
              onChange: function (v) { setAttrs({ introTexto: v }); }
            })
          ),
          cardPanel('Tarjeta 1 — ' + (attrs.t1Cat || 'General'),          't1', attrs, setAttrs),
          cardPanel('Tarjeta 2 — ' + (attrs.t2Cat || 'FODES'),            't2', attrs, setAttrs),
          cardPanel('Tarjeta 3 — ' + (attrs.t3Cat || 'Recursos Humanos'), 't3', attrs, setAttrs),
          cardPanel('Tarjeta 4 — ' + (attrs.t4Cat || 'Info. Pública'),    't4', attrs, setAttrs),
          cardPanel('Tarjeta 5 — ' + (attrs.t5Cat || 'Consultas'),        't5', attrs, setAttrs),
          el(PanelBody, { title: 'Nota informativa', initialOpen: false },
            el(TextareaControl, {
              label: 'Texto de nota',
              value: attrs.notaTexto,
              onChange: function (v) { setAttrs({ notaTexto: v }); }
            })
          )
        ),
        el('span', { className: 'mides-tramites-editor__icon' }, '📋'),
        el('h3', null, 'Trámites — Grid de Servicios'),
        el('p', null, '5 tarjetas editables desde el panel lateral')
      );
    }
  });
})();
