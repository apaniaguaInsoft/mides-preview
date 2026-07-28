(function () {
  var registerBlockType = wp.blocks.registerBlockType;
  var el                = wp.element.createElement;
  var useBlockProps     = wp.blockEditor.useBlockProps;
  var InnerBlocks       = wp.blockEditor.InnerBlocks;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var PanelBody         = wp.components.PanelBody;
  var TextControl       = wp.components.TextControl;
  var SelectControl     = wp.components.SelectControl;

  var ICON_OPTIONS = [
    { label: 'Documento / archivo',       value: 'document'  },
    { label: 'Maletín / oficio',          value: 'briefcase' },
    { label: 'Chat / solicitud',          value: 'chat'      },
    { label: 'Lupa / transparencia',      value: 'search'    },
    { label: 'Globo (idiomas / global)',  value: 'globe'     },
    { label: 'Calendario',               value: 'calendar'  },
    { label: 'Libro / memoria',           value: 'book'      },
    { label: 'Moneda / presupuesto',      value: 'dollar'    },
    { label: 'Edificio / institución',    value: 'home'      },
    { label: 'Personas / grupo',          value: 'users'     },
    { label: 'Correo electrónico',        value: 'mail'      },
    { label: 'Verificación',             value: 'check'     },
  ];

  registerBlockType('mides/infopub-tab-panel', {

    edit: function (props) {
      var attrs      = props.attributes;
      var setAttrs   = props.setAttributes;
      var blockProps = useBlockProps({ className: 'infopub-tab-panel-editor' });

      return el(
        'div', blockProps,

        el(InspectorControls, null,
          el(PanelBody, { title: 'Configuración de la pestaña', initialOpen: true },
            el(TextControl, {
              label: 'Etiqueta de la pestaña',
              value: attrs.label,
              onChange: function (v) { setAttrs({ label: v }); }
            }),
            el(SelectControl, {
              label: 'Ícono de la pestaña',
              value: attrs.icono,
              options: ICON_OPTIONS,
              onChange: function (v) { setAttrs({ icono: v }); }
            })
          )
        ),

        el('div', { className: 'infopub-tab-panel-editor__header' },
          el('span', { className: 'infopub-tab-panel-editor__badge' },
            '🗒️ ' + (attrs.label || 'Panel')
          ),
          el('span', { style: { fontSize: '12px', color: '#94A3B8', marginLeft: '8px' } },
            'Ícono: ' + attrs.icono
          )
        ),

        el(InnerBlocks, {
          templateLock: false
        })
      );
    },

    save: function () {
      return el(InnerBlocks.Content);
    }
  });
})();
