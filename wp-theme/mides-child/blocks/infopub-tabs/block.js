(function () {
  var registerBlockType = wp.blocks.registerBlockType;
  var el                = wp.element.createElement;
  var useBlockProps     = wp.blockEditor.useBlockProps;
  var InnerBlocks       = wp.blockEditor.InnerBlocks;

  var TEMPLATE = [
    ['mides/infopub-tab-panel', { label: 'Ley de Acceso',              icono: 'document'   }],
    ['mides/infopub-tab-panel', { label: 'Información de Oficio',      icono: 'briefcase'  }],
    ['mides/infopub-tab-panel', { label: 'Solicitud',                  icono: 'chat'       }],
    ['mides/infopub-tab-panel', { label: 'Transparencia',              icono: 'search'     }],
  ];

  registerBlockType('mides/infopub-tabs', {

    edit: function (props) {
      var blockProps = useBlockProps({ className: 'infopub-tabs-editor' });

      return el(
        'div', blockProps,

        el('div', { className: 'infopub-tabs-editor__label' },
          el('span', { style: { fontSize: '20px' } }, '🗂️'),
          el('strong', null, ' Tabs — Información Pública'),
          el('p', { style: { margin: '4px 0 0', fontSize: '12px', color: '#64748B' } },
            'Agrega bloques "Info Pública — Panel de Tab" aquí abajo. Cada panel se convierte en una pestaña.'
          )
        ),

        el(InnerBlocks, {
          allowedBlocks: ['mides/infopub-tab-panel'],
          template: TEMPLATE,
          templateLock: false,
          orientation: 'vertical'
        })
      );
    },

    save: function () {
      return el(InnerBlocks.Content);
    }
  });
})();
