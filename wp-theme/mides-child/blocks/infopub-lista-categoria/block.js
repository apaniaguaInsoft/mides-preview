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
  var RangeControl      = wp.components.RangeControl;
  var Spinner           = wp.components.Spinner;

  var ICON_OPTIONS = [
    { label: 'Globo (idiomas / global)',    value: 'globe' },
    { label: 'Documento / archivo',         value: 'document' },
    { label: 'Calendario',                  value: 'calendar' },
    { label: 'Libro / memoria',             value: 'book' },
    { label: 'Moneda / presupuesto',        value: 'dollar' },
  ];

  var COLUMNAS_OPTIONS = [
    { label: '2 columnas', value: 2 },
    { label: '3 columnas', value: 3 },
    { label: '4 columnas', value: 4 },
    { label: '5 columnas (predeterminado)', value: 5 },
  ];

  registerBlockType('mides/infopub-lista-categoria', {

    edit: function (props) {
      var attrs      = props.attributes;
      var setAttrs   = props.setAttributes;
      var blockProps = useBlockProps({ className: 'mides-editor-placeholder' });

      var catsState  = useState([]);
      var cats       = catsState[0];
      var setCats    = catsState[1];

      var loadState  = useState(true);
      var isLoading  = loadState[0];
      var setLoading = loadState[1];

      useEffect(function () {
        wp.apiFetch({ path: '/wp/v2/categoria_documento?per_page=100&hide_empty=false' })
          .then(function (data) { setCats(data); setLoading(false); })
          .catch(function () { setLoading(false); });
      }, []);

      var catOptions = [{ label: '— Todas las categorías —', value: 0 }].concat(
        cats.map(function (c) { return { label: c.name + ' (' + c.count + ')', value: c.id }; })
      );

      var catLabel = attrs.categoriaId === 0
        ? 'Todas las categorías'
        : (cats.find(function (c) { return c.id === attrs.categoriaId; }) || {}).name || 'ID ' + attrs.categoriaId;

      return el(
        'div', blockProps,

        el(InspectorControls, null,

          el(PanelBody, { title: 'Categoría de documentos', initialOpen: true },
            isLoading
              ? el(Spinner)
              : el(SelectControl, {
                  label: 'Categoría a mostrar',
                  value: attrs.categoriaId,
                  options: catOptions,
                  onChange: function (v) { setAttrs({ categoriaId: parseInt(v, 10) }); }
                })
          ),

          el(PanelBody, { title: 'Encabezado del panel', initialOpen: true },
            el(SelectControl, {
              label: 'Ícono',
              value: attrs.icono,
              options: ICON_OPTIONS,
              onChange: function (v) { setAttrs({ icono: v }); }
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

          el(PanelBody, { title: 'Visualización', initialOpen: false },
            el(SelectControl, {
              label: 'Columnas de la grilla',
              value: attrs.columnas,
              options: COLUMNAS_OPTIONS,
              onChange: function (v) { setAttrs({ columnas: parseInt(v, 10) }); }
            })
          )
        ),

        el('span', { style: { fontSize: '28px' } }, '📋'),
        el('strong', { style: { display: 'block', marginTop: '8px' } },
          attrs.titulo || 'Lista por categoría'
        ),
        el('span', { style: { fontSize: '13px', color: '#64748B' } },
          'Categoría: ' + catLabel + ' · ' + attrs.columnas + ' columnas'
        )
      );
    }
  });
})();
