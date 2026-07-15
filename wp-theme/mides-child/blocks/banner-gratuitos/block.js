(function () {
  var registerBlockType = wp.blocks.registerBlockType;
  var el                = wp.element.createElement;
  var Fragment          = wp.element.Fragment;
  var useBlockProps     = wp.blockEditor.useBlockProps;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var MediaUpload       = wp.blockEditor.MediaUpload;
  var MediaUploadCheck  = wp.blockEditor.MediaUploadCheck;
  var PanelBody         = wp.components.PanelBody;
  var TextControl       = wp.components.TextControl;
  var Button            = wp.components.Button;

  registerBlockType('mides/banner-gratuitos', {

    edit: function (props) {
      var imageId   = props.attributes.imageId;
      var imageUrl  = props.attributes.imageUrl;
      var imageAlt  = props.attributes.imageAlt;
      var pillar1   = props.attributes.pillar1;
      var pillar2   = props.attributes.pillar2;
      var pillar3   = props.attributes.pillar3;
      var blockProps = useBlockProps({ className: 'mides-banner-gratuitos-editor' });

      var inspector = el(InspectorControls, null,

        el(PanelBody, { title: 'Imagen lateral', initialOpen: true },
          el(MediaUploadCheck, null,
            el(MediaUpload, {
              onSelect: function (media) {
                props.setAttributes({
                  imageId: media.id,
                  imageUrl: media.url,
                  imageAlt: media.alt || media.title || ''
                });
              },
              allowedTypes: ['image'],
              value: imageId || undefined,
              render: function (ref) {
                return el(Fragment, null,
                  imageUrl && el('img', {
                    src: imageUrl,
                    style: { width: '100%', height: '80px', objectFit: 'cover', borderRadius: '4px', marginBottom: '8px', display: 'block' }
                  }),
                  el(Button, {
                    onClick: ref.open,
                    variant: 'secondary',
                    style: { width: '100%', marginBottom: '8px' }
                  }, imageUrl ? 'Cambiar imagen' : 'Seleccionar imagen lateral'),
                  el(TextControl, {
                    label: 'Texto alternativo',
                    value: imageAlt,
                    onChange: function (v) { props.setAttributes({ imageAlt: v }); }
                  })
                );
              }
            })
          )
        ),

        el(PanelBody, { title: 'Pilares', initialOpen: false },
          el(TextControl, {
            label: 'Pilar 1',
            value: pillar1,
            onChange: function (v) { props.setAttributes({ pillar1: v }); }
          }),
          el(TextControl, {
            label: 'Pilar 2',
            value: pillar2,
            onChange: function (v) { props.setAttributes({ pillar2: v }); }
          }),
          el(TextControl, {
            label: 'Pilar 3',
            value: pillar3,
            onChange: function (v) { props.setAttributes({ pillar3: v }); }
          })
        )
      );

      var preview = el('div', blockProps,
        el('div', { className: 'mides-banner-gratuitos-editor__text' },
          el('p', { className: 'mides-banner-gratuitos-editor__top' }, 'TODOS NUESTROS'),
          el('p', { className: 'mides-banner-gratuitos-editor__programs' }, 'PROGRAMAS SOCIALES'),
          el('p', { className: 'mides-banner-gratuitos-editor__son' }, 'SON'),
          el('p', { className: 'mides-banner-gratuitos-editor__free' }, 'GRATUITOS'),
          el('p', { className: 'mides-banner-gratuitos-editor__note' },
            'No debes pagar por ', el('strong', null, 'NADA')
          ),
          el('span', { className: 'mides-banner-gratuitos-editor__btn' }, 'DENUNCIA AQUÍ')
        ),
        el('div', { className: 'mides-banner-gratuitos-editor__img-wrap' },
          imageUrl
            ? el('img', { src: imageUrl, alt: imageAlt })
            : el('span', { className: 'mides-banner-gratuitos-editor__img-placeholder' }, '🖼')
        )
      );

      return el(Fragment, null, inspector, preview);
    },

    save: function () { return null; }
  });
})();
