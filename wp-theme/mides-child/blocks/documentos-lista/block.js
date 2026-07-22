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
  var RangeControl      = wp.components.RangeControl;
  var ToggleControl     = wp.components.ToggleControl;
  var CheckboxControl   = wp.components.CheckboxControl;
  var Spinner           = wp.components.Spinner;

  registerBlockType('mides/documentos-lista', {

    edit: function (props) {
      var attrs      = props.attributes;
      var setAttrs   = props.setAttributes;
      var blockProps = useBlockProps({ className: 'mides-docs-editor' });

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

      function toggleCategoria(id) {
        var current = attrs.categorias.slice();
        var idx = current.indexOf(id);
        if (idx > -1) current.splice(idx, 1);
        else current.push(id);
        setAttrs({ categorias: current });
      }

      var catLabel = attrs.categorias.length === 0
        ? 'Todas las categorías'
        : attrs.categorias.length + ' categoría(s) seleccionada(s)';

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
              label: 'Subtítulo',
              value: attrs.subtitulo,
              onChange: function (v) { setAttrs({ subtitulo: v }); }
            })
          ),

          el(PanelBody, { title: 'Categorías — ' + catLabel, initialOpen: true },
            isLoading
              ? el(Spinner)
              : cats.length === 0
                ? el('p', { style: { color: '#94A3B8', fontSize: '13px' } }, 'No hay categorías creadas aún.')
                : el('div', null,
                    el('p', { style: { fontSize: '12px', color: '#64748B', marginBottom: '10px' } },
                      'Sin selección = muestra todas.'
                    ),
                    cats.map(function (cat) {
                      return el(CheckboxControl, {
                        key: cat.id,
                        label: cat.name + ' (' + cat.count + ')',
                        checked: attrs.categorias.indexOf(cat.id) > -1,
                        onChange: function () { toggleCategoria(cat.id); }
                      });
                    })
                  )
          ),

          el(PanelBody, { title: 'Visualización', initialOpen: false },
            el(RangeControl, {
              label: 'Máximo de documentos a cargar',
              value: attrs.maxDocumentos,
              min: 10,
              max: 500,
              step: 10,
              onChange: function (v) { setAttrs({ maxDocumentos: v }); }
            }),
            el(RangeControl, {
              label: 'Documentos por página',
              value: attrs.porPagina,
              min: 3,
              max: 30,
              step: 3,
              onChange: function (v) { setAttrs({ porPagina: v }); }
            }),
            el(ToggleControl, {
              label: 'Mostrar buscador',
              checked: attrs.mostrarBuscador,
              onChange: function (v) { setAttrs({ mostrarBuscador: v }); }
            }),
            el(ToggleControl, {
              label: 'Mostrar filtros por categoría',
              checked: attrs.mostrarFiltros,
              onChange: function (v) { setAttrs({ mostrarFiltros: v }); }
            })
          )
        ),

        el('span', { className: 'mides-docs-editor__icon' }, '📄'),
        el('h3', null, attrs.titulo || 'Documentos'),
        el('p', null, catLabel),
        el('p', null,
          el('small', null,
            attrs.porPagina + ' por página · ' +
            (attrs.mostrarBuscador ? 'con buscador' : 'sin buscador') + ' · ' +
            (attrs.mostrarFiltros ? 'con filtros' : 'sin filtros')
          )
        )
      );
    }
  });
})();
