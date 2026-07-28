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
      var pillar1      = props.attributes.pillar1;
      var pillar2      = props.attributes.pillar2;
      var pillar3      = props.attributes.pillar3;
      var textTop      = props.attributes.textTop;
      var textPrograms = props.attributes.textPrograms;
      var textSon      = props.attributes.textSon;
      var textFree     = props.attributes.textFree;
      var textNote     = props.attributes.textNote;
      var textNoteBold = props.attributes.textNoteBold;
      var textBtn      = props.attributes.textBtn;
      var textBtnSub   = props.attributes.textBtnSub;
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

        el(PanelBody, { title: 'Textos principales', initialOpen: false },
          el(TextControl, { label: 'Línea 1 (ej. "TODOS NUESTROS")',       value: textTop,      onChange: function (v) { props.setAttributes({ textTop: v }); } }),
          el(TextControl, { label: 'Línea 2 (ej. "PROGRAMAS SOCIALES")',   value: textPrograms, onChange: function (v) { props.setAttributes({ textPrograms: v }); } }),
          el(TextControl, { label: 'Conector (ej. "SON")',                 value: textSon,      onChange: function (v) { props.setAttributes({ textSon: v }); } }),
          el(TextControl, { label: 'Palabra grande (ej. "GRATUITOS")',     value: textFree,     onChange: function (v) { props.setAttributes({ textFree: v }); } }),
          el(TextControl, { label: 'Nota — texto normal',                  value: textNote,     onChange: function (v) { props.setAttributes({ textNote: v }); } }),
          el(TextControl, { label: 'Nota — palabra en negrita',            value: textNoteBold, onChange: function (v) { props.setAttributes({ textNoteBold: v }); } }),
          el(TextControl, { label: 'Botón — texto principal',              value: textBtn,      onChange: function (v) { props.setAttributes({ textBtn: v }); } }),
          el(TextControl, { label: 'Botón — subtexto',                     value: textBtnSub,   onChange: function (v) { props.setAttributes({ textBtnSub: v }); } })
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
          el('p', { className: 'mides-banner-gratuitos-editor__top' }, textTop),
          el('p', { className: 'mides-banner-gratuitos-editor__programs' }, textPrograms),
          el('p', { className: 'mides-banner-gratuitos-editor__son' }, textSon),
          el('p', { className: 'mides-banner-gratuitos-editor__free' }, textFree),
          el('p', { className: 'mides-banner-gratuitos-editor__note' },
            textNote + ' ', el('strong', null, textNoteBold)
          ),
          el('span', { className: 'mides-banner-gratuitos-editor__btn' }, textBtn)
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
