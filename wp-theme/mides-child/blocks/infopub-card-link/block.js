(function () {
  var registerBlockType = wp.blocks.registerBlockType;
  var el                = wp.element.createElement;
  var useState          = wp.element.useState;
  var useEffect         = wp.element.useEffect;
  var useBlockProps     = wp.blockEditor.useBlockProps;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var PanelBody         = wp.components.PanelBody;
  var TextControl       = wp.components.TextControl;
  var TextareaControl   = wp.components.TextareaControl;
  var SelectControl     = wp.components.SelectControl;
  var ToggleControl     = wp.components.ToggleControl;
  var Spinner           = wp.components.Spinner;

  var ICON_OPTIONS = [
    { label: 'Documento / archivo',         value: 'document' },
    { label: 'Globo (idiomas / global)',    value: 'globe' },
    { label: 'Edificio / institución',      value: 'home' },
    { label: 'Personas / grupo',            value: 'users' },
    { label: 'Moneda / presupuesto',        value: 'dollar' },
    { label: 'Teléfono / dispositivo',      value: 'phone' },
    { label: 'Correo electrónico',          value: 'mail' },
    { label: 'Verificación / compromisos',  value: 'check' },
    { label: 'Lengua de señas / copa',      value: 'coffee' },
    { label: 'Libro / memoria',             value: 'book' },
    { label: 'Calendario',                  value: 'calendar' },
  ];

  var COLOR_OPTIONS = [
    { label: 'Azul claro (predeterminado)', value: 'default' },
    { label: 'Azul marino',                 value: 'amber' },
    { label: 'Teal',                        value: 'teal' },
  ];

  var BOTON_OPTIONS = [
    { label: 'Descarga ↓',        value: 'download' },
    { label: 'Enlace externo ↗',  value: 'external' },
    { label: 'Sin ícono',         value: 'none' },
  ];

  registerBlockType('mides/infopub-card-link', {

    edit: function (props) {
      var attrs      = props.attributes;
      var setAttrs   = props.setAttributes;
      var blockProps = useBlockProps({ className: 'mides-editor-placeholder' });

      var docsState  = useState([]);
      var docs       = docsState[0];
      var setDocs    = docsState[1];

      var loadState  = useState(false);
      var isLoading  = loadState[0];
      var setLoading = loadState[1];

      useEffect(function () {
        if ( ! attrs.modoDocumento ) return;
        setLoading(true);
        wp.apiFetch({ path: '/wp/v2/documento?per_page=100&orderby=title&order=asc' })
          .then(function (data) { setDocs(data); setLoading(false); })
          .catch(function () { setLoading(false); });
      }, [attrs.modoDocumento]);

      var docOptions = [{ label: '— Seleccionar documento —', value: 0 }].concat(
        docs.map(function (d) { return { label: d.title.rendered, value: d.id }; })
      );

      var docLabel = !attrs.modoDocumento
        ? attrs.urlManual || '(sin URL)'
        : attrs.documentoId > 0
          ? (docs.find(function (d) { return d.id === attrs.documentoId; }) || {}).title
            ? (docs.find(function (d) { return d.id === attrs.documentoId; })).title.rendered
            : 'ID ' + attrs.documentoId
          : '(sin documento)';

      return el(
        'div', blockProps,

        el(InspectorControls, null,

          el(PanelBody, { title: 'Fuente del link', initialOpen: true },
            el(ToggleControl, {
              label: 'Usar documento del CPT',
              help: attrs.modoDocumento
                ? 'La URL se obtiene del archivo del documento seleccionado.'
                : 'Ingresa la URL manualmente.',
              checked: attrs.modoDocumento,
              onChange: function (v) { setAttrs({ modoDocumento: v }); }
            }),
            attrs.modoDocumento
              ? ( isLoading
                  ? el(Spinner)
                  : el(SelectControl, {
                      label: 'Documento',
                      value: attrs.documentoId,
                      options: docOptions,
                      onChange: function (v) { setAttrs({ documentoId: parseInt(v, 10) }); }
                    })
                )
              : el(TextControl, {
                  label: 'URL del enlace',
                  value: attrs.urlManual,
                  placeholder: 'https://…',
                  onChange: function (v) { setAttrs({ urlManual: v }); }
                })
          ),

          el(PanelBody, { title: 'Contenido de la card', initialOpen: true },
            el(SelectControl, {
              label: 'Ícono',
              value: attrs.icono,
              options: ICON_OPTIONS,
              onChange: function (v) { setAttrs({ icono: v }); }
            }),
            el(SelectControl, {
              label: 'Color del ícono',
              value: attrs.colorIcono,
              options: COLOR_OPTIONS,
              onChange: function (v) { setAttrs({ colorIcono: v }); }
            }),
            el(TextControl, {
              label: 'Etiqueta (tag)',
              value: attrs.tag,
              onChange: function (v) { setAttrs({ tag: v }); }
            }),
            el(TextControl, {
              label: 'Título',
              value: attrs.titulo,
              onChange: function (v) { setAttrs({ titulo: v }); }
            }),
            el(TextareaControl, {
              label: 'Descripción (opcional)',
              value: attrs.descripcion,
              onChange: function (v) { setAttrs({ descripcion: v }); }
            })
          ),

          el(PanelBody, { title: 'Botón', initialOpen: false },
            el(TextControl, {
              label: 'Texto del botón',
              value: attrs.textoBoton,
              onChange: function (v) { setAttrs({ textoBoton: v }); }
            }),
            el(SelectControl, {
              label: 'Ícono del botón',
              value: attrs.tipoBoton,
              options: BOTON_OPTIONS,
              onChange: function (v) { setAttrs({ tipoBoton: v }); }
            })
          )
        ),

        el('span', { style: { fontSize: '28px' } }, '🪪'),
        el('strong', { style: { display: 'block', marginTop: '8px' } },
          attrs.titulo || attrs.tag || 'Card de información pública'
        ),
        el('span', { style: { fontSize: '13px', color: '#64748B' } },
          (attrs.modoDocumento ? 'Documento CPT: ' : 'Link: ') + docLabel
        )
      );
    }
  });
})();
