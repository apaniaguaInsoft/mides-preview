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
  var CheckboxControl   = wp.components.CheckboxControl;
  var Spinner           = wp.components.Spinner;

  registerBlockType('mides/noticias-recientes', {

    edit: function (props) {
      var attrs       = props.attributes;
      var setAttrs    = props.setAttributes;
      var blockProps  = useBlockProps({ className: 'mides-noticias-editor' });

      var categorias = useState([]);
      var cats       = categorias[0];
      var setCats    = categorias[1];

      var loading = useState(true);
      var isLoading = loading[0];
      var setLoading = loading[1];

      useEffect(function () {
        wp.apiFetch({ path: '/wp/v2/categories?per_page=100&hide_empty=false' })
          .then(function (data) {
            setCats(data);
            setLoading(false);
          })
          .catch(function () { setLoading(false); });
      }, []);

      function toggleCategoria(id) {
        var current = attrs.categorias.slice();
        var idx = current.indexOf(id);
        if (idx > -1) {
          current.splice(idx, 1);
        } else {
          current.push(id);
        }
        setAttrs({ categorias: current });
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
              label: 'Subtítulo',
              value: attrs.subtitulo,
              onChange: function (v) { setAttrs({ subtitulo: v }); }
            })
          ),

          el(PanelBody, { title: 'Consulta de posts', initialOpen: true },
            el(RangeControl, {
              label: 'Cantidad de noticias',
              value: attrs.cantidad,
              min: 1,
              max: 12,
              onChange: function (v) { setAttrs({ cantidad: v }); }
            }),
            el('p', { style: { fontSize: '12px', fontWeight: '600', marginBottom: '8px' } },
              'Filtrar por categoría (vacío = todas)'
            ),
            isLoading
              ? el(Spinner)
              : cats.map(function (cat) {
                  return el(CheckboxControl, {
                    key: cat.id,
                    label: cat.name + ' (' + cat.count + ')',
                    checked: attrs.categorias.indexOf(cat.id) > -1,
                    onChange: function () { toggleCategoria(cat.id); }
                  });
                })
          ),

          el(PanelBody, { title: 'Botón "Ver todas"', initialOpen: false },
            el(TextControl, {
              label: 'Texto del botón',
              value: attrs.textoVerTodas,
              onChange: function (v) { setAttrs({ textoVerTodas: v }); }
            }),
            el(TextControl, {
              label: 'URL del botón',
              value: attrs.urlVerTodas,
              type: 'url',
              placeholder: 'https://',
              onChange: function (v) { setAttrs({ urlVerTodas: v }); }
            })
          )
        ),

        el('div', { className: 'mides-noticias-editor__header' },
          el('h2', { className: 'mides-noticias-editor__title' }, attrs.titulo),
          el('p', { className: 'mides-noticias-editor__subtitle' }, attrs.subtitulo)
        ),

        el('div', { className: 'mides-noticias-editor__preview' },
          Array.from({ length: Math.min(attrs.cantidad, 3) }, function (_, i) {
            return el('div', { key: i, className: 'mides-noticias-editor__card' },
              'Noticia ' + (i + 1)
            );
          })
        ),

        attrs.cantidad > 3 && el('p', { className: 'mides-noticias-editor__footer' },
          '+ ' + (attrs.cantidad - 3) + ' noticia(s) más en el carrusel'
        )
      );
    },

    save: function () { return null; }
  });
})();
