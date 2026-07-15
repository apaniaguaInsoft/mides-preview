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
  var SelectControl     = wp.components.SelectControl;
  var Button            = wp.components.Button;
  var Placeholder       = wp.components.Placeholder;

  var LABEL_OPTIONS = [
    { label: 'Programa',           value: 'Programa' },
    { label: 'Intervención',       value: 'Intervención' },
    { label: 'Beca Social de',     value: 'Beca Social de' },
    { label: 'Servicio',           value: 'Servicio' },
  ];

  registerBlockType('mides/programas-sociales', {

    edit: function (props) {
      var programs        = props.attributes.programs;
      var titleBefore     = props.attributes.titleBefore;
      var titleHighlight  = props.attributes.titleHighlight;
      var blockProps      = useBlockProps({ className: 'mides-programas-editor' });

      function setProgram(index, key, value) {
        props.setAttributes({
          programs: programs.map(function (p, i) {
            if (i !== index) return p;
            var copy = Object.assign({}, p);
            copy[key] = value;
            return copy;
          })
        });
      }

      function addProgram() {
        props.setAttributes({
          programs: programs.concat([{
            iconId: 0, iconUrl: '', iconAlt: '',
            label: 'Programa', name: '', nameBold: '', modal: ''
          }])
        });
      }

      function removeProgram(index) {
        if (!window.confirm('¿Eliminar el programa "' + (programs[index].name || (index + 1)) + '"?')) return;
        props.setAttributes({ programs: programs.filter(function (_, i) { return i !== index; }) });
      }

      function moveProgram(index, direction) {
        var newIndex = index + direction;
        if (newIndex < 0 || newIndex >= programs.length) return;
        var reordered = programs.slice();
        var tmp = reordered[index];
        reordered[index] = reordered[newIndex];
        reordered[newIndex] = tmp;
        props.setAttributes({ programs: reordered });
      }

      /* ── Panel lateral ── */
      var inspector = el(InspectorControls, null,

        el(PanelBody, { title: 'Título de sección', initialOpen: true },
          el(TextControl, {
            label: 'Texto inicial (sin resaltar)',
            value: titleBefore,
            onChange: function (v) { props.setAttributes({ titleBefore: v }); },
            placeholder: 'Programas e'
          }),
          el(TextControl, {
            label: 'Texto resaltado (con línea debajo)',
            value: titleHighlight,
            onChange: function (v) { props.setAttributes({ titleHighlight: v }); },
            placeholder: 'Intervenciones Sociales'
          })
        ),

        el(PanelBody, { title: '+ Agregar programa', initialOpen: programs.length === 0 },
          el(Button, {
            onClick: addProgram,
            variant: 'primary',
            style: { width: '100%' }
          }, '+ Agregar programa')
        ),

        programs.map(function (prog, i) {
          var panelTitle = (prog.label ? prog.label + ' ' : '') + (prog.name || '') + (prog.nameBold ? ' ' + prog.nameBold : '') || ('Programa ' + (i + 1));
          return el(PanelBody, {
            key: i,
            title: (i + 1) + '. ' + panelTitle,
            initialOpen: false
          },

            /* Icono / imagen */
            el(MediaUploadCheck, null,
              el(MediaUpload, {
                onSelect: function (media) {
                  setProgram(i, 'iconId', media.id);
                  setProgram(i, 'iconUrl', media.url);
                  setProgram(i, 'iconAlt', media.alt || media.title || '');
                },
                allowedTypes: ['image'],
                value: prog.iconId || undefined,
                render: function (ref) {
                  return el(Fragment, null,
                    prog.iconUrl && el('img', {
                      src: prog.iconUrl,
                      style: { width: '60px', height: '60px', objectFit: 'contain', display: 'block', marginBottom: '8px', border: '1px solid #ddd', padding: '4px', borderRadius: '4px' }
                    }),
                    el(Button, {
                      onClick: ref.open,
                      variant: 'secondary',
                      style: { width: '100%', marginBottom: '12px' }
                    }, prog.iconUrl ? 'Cambiar ícono' : 'Seleccionar ícono (SVG/imagen)')
                  );
                }
              })
            ),

            el(SelectControl, {
              label: 'Tipo de programa',
              value: prog.label,
              options: LABEL_OPTIONS,
              onChange: function (v) { setProgram(i, 'label', v); }
            }),

            el(TextControl, {
              label: 'Nombre (primera línea)',
              value: prog.name,
              onChange: function (v) { setProgram(i, 'name', v); },
              placeholder: 'Bono'
            }),

            el(TextControl, {
              label: 'Nombre en negrita (segunda línea)',
              value: prog.nameBold,
              onChange: function (v) { setProgram(i, 'nameBold', v); },
              placeholder: 'Social'
            }),

            el(TextControl, {
              label: 'ID de modal (slug)',
              value: prog.modal,
              onChange: function (v) { setProgram(i, 'modal', v); },
              placeholder: 'bono-social',
              help: 'Identificador para abrir el modal al hacer clic.'
            }),

            /* Botones mover / eliminar */
            el('div', { style: { display: 'flex', gap: '8px', marginTop: '8px' } },
              el(Button, {
                onClick: function () { moveProgram(i, -1); },
                variant: 'secondary',
                disabled: i === 0,
                style: { flex: 1 }
              }, '↑ Subir'),
              el(Button, {
                onClick: function () { moveProgram(i, 1); },
                variant: 'secondary',
                disabled: i === programs.length - 1,
                style: { flex: 1 }
              }, '↓ Bajar')
            ),

            el(Button, {
              onClick: function () { removeProgram(i); },
              variant: 'link',
              isDestructive: true,
              style: { marginTop: '8px', width: '100%' }
            }, 'Eliminar programa')
          );
        }),

      );

      /* ── Preview en el editor ── */
      var preview = programs.length === 0
        ? el(Placeholder, {
            icon: 'grid-view',
            label: 'Programas Sociales',
            instructions: 'Agrega el primer programa desde el panel derecho →'
          },
            el(Button, { onClick: addProgram, variant: 'primary' }, '+ Agregar programa')
          )
        : el(Fragment, null,
            el('p', { className: 'mides-programas-editor__title' },
              'Programas Sociales — ' + programs.length + ' tarjeta' + (programs.length !== 1 ? 's' : '')
            ),
            el('p', { className: 'mides-programas-editor__count' },
              '"' + titleBefore + ' ' + titleHighlight + '"'
            ),
            el('div', { className: 'mides-programas-editor__grid' },
              programs.map(function (prog, i) {
                return el('div', { key: i, className: 'mides-programas-editor__card' },
                  el('div', { className: 'mides-programas-editor__card-icon' },
                    prog.iconUrl
                      ? el('img', { src: prog.iconUrl, alt: prog.iconAlt })
                      : el('span', null, '🖼')
                  ),
                  el('span', { className: 'mides-programas-editor__card-label' }, prog.label),
                  el('div', { className: 'mides-programas-editor__card-name' },
                    (prog.name ? prog.name + ' ' : '') + (prog.nameBold || '')
                  )
                );
              }),
              el('div', {
                className: 'mides-programas-editor__card',
                style: { background: 'transparent', border: '2px dashed #192854', cursor: 'pointer' },
                onClick: addProgram
              },
                el('div', { className: 'mides-programas-editor__card-icon', style: { background: 'transparent', color: '#192854', fontSize: '1.6rem' } }, '+'),
                el('span', { className: 'mides-programas-editor__card-label', style: { color: '#192854' } }, 'Agregar')
              )
            ),
          );

      return el(Fragment, null,
        inspector,
        el('div', blockProps, preview)
      );
    },

    save: function () { return null; }
  });
})();
