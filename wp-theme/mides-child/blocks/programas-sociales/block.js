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
  var TextareaControl   = wp.components.TextareaControl;
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
            label: 'Programa', name: '', nameBold: '', modal: '',
            desc: '', page: '', chips: [], sections: []
          }])
        });
      }

      function setChip(progIndex, chipIndex, value) {
        var chips = (programs[progIndex].chips || []).slice();
        chips[chipIndex] = value;
        setProgram(progIndex, 'chips', chips);
      }
      function addChip(progIndex) {
        setProgram(progIndex, 'chips', (programs[progIndex].chips || []).concat(['']));
      }
      function removeChip(progIndex, chipIndex) {
        setProgram(progIndex, 'chips', (programs[progIndex].chips || []).filter(function (_, i) { return i !== chipIndex; }));
      }

      function setSection(progIndex, secIndex, key, value) {
        var sections = (programs[progIndex].sections || []).slice();
        sections[secIndex] = Object.assign({}, sections[secIndex]);
        sections[secIndex][key] = value;
        setProgram(progIndex, 'sections', sections);
      }
      function setSectionItem(progIndex, secIndex, itemIndex, value) {
        var sections = (programs[progIndex].sections || []).slice();
        sections[secIndex] = Object.assign({}, sections[secIndex]);
        var items = (sections[secIndex].items || []).slice();
        items[itemIndex] = value;
        sections[secIndex].items = items;
        setProgram(progIndex, 'sections', sections);
      }
      function addSection(progIndex) {
        setProgram(progIndex, 'sections', (programs[progIndex].sections || []).concat([{ titulo: '', items: [] }]));
      }
      function removeSection(progIndex, secIndex) {
        setProgram(progIndex, 'sections', (programs[progIndex].sections || []).filter(function (_, i) { return i !== secIndex; }));
      }
      function addSectionItem(progIndex, secIndex) {
        var sections = (programs[progIndex].sections || []).slice();
        sections[secIndex] = Object.assign({}, sections[secIndex]);
        sections[secIndex].items = (sections[secIndex].items || []).concat(['']);
        setProgram(progIndex, 'sections', sections);
      }
      function removeSectionItem(progIndex, secIndex, itemIndex) {
        var sections = (programs[progIndex].sections || []).slice();
        sections[secIndex] = Object.assign({}, sections[secIndex]);
        sections[secIndex].items = (sections[secIndex].items || []).filter(function (_, i) { return i !== itemIndex; });
        setProgram(progIndex, 'sections', sections);
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

            /* Contenido del modal */
            el('hr', { style: { margin: '12px 0', borderColor: '#e0e0e0' } }),
            el('p', { style: { fontWeight: 600, marginBottom: '8px', fontSize: '11px', textTransform: 'uppercase', color: '#555' } }, 'Contenido del modal'),

            el(TextareaControl, {
              label: 'Descripción',
              value: prog.desc || '',
              onChange: function (v) { setProgram(i, 'desc', v); },
              placeholder: 'Descripción del programa...',
              rows: 3,
              help: 'Acepta HTML básico (<strong>, <em>).'
            }),

            el(TextControl, {
              label: 'URL "Más información"',
              value: prog.page || '',
              onChange: function (v) { setProgram(i, 'page', v); },
              placeholder: 'bono-social.html'
            }),

            /* Chips */
            el('p', { style: { fontWeight: 600, marginBottom: '4px', marginTop: '8px', fontSize: '12px' } }, 'Chips / etiquetas'),
            (prog.chips || []).map(function (chip, ci) {
              return el('div', { key: ci, style: { display: 'flex', gap: '4px', marginBottom: '4px' } },
                el(TextControl, {
                  value: chip,
                  onChange: function (v) { setChip(i, ci, v); },
                  placeholder: 'Ej: Salud',
                  style: { flex: 1 }
                }),
                el(Button, {
                  onClick: function () { removeChip(i, ci); },
                  variant: 'link', isDestructive: true
                }, '✕')
              );
            }),
            el(Button, {
              onClick: function () { addChip(i); },
              variant: 'secondary',
              style: { width: '100%', marginBottom: '12px' }
            }, '+ Chip'),

            /* Secciones del modal */
            el('p', { style: { fontWeight: 600, marginBottom: '4px', fontSize: '12px' } }, 'Secciones'),
            (prog.sections || []).map(function (sec, si) {
              return el('div', { key: si, style: { border: '1px solid #ddd', borderRadius: '4px', padding: '8px', marginBottom: '8px' } },
                el('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '6px' } },
                  el('strong', { style: { fontSize: '12px' } }, 'Sección ' + (si + 1)),
                  el(Button, { onClick: function () { removeSection(i, si); }, variant: 'link', isDestructive: true }, '✕ Eliminar')
                ),
                el(TextControl, {
                  label: 'Título',
                  value: sec.titulo || '',
                  onChange: function (v) { setSection(i, si, 'titulo', v); },
                  placeholder: '¿Quién aplica?'
                }),
                el('p', { style: { fontSize: '11px', fontWeight: 600, marginBottom: '4px' } }, 'Ítems'),
                (sec.items || []).map(function (item, ii) {
                  return el('div', { key: ii, style: { display: 'flex', gap: '4px', marginBottom: '4px' } },
                    el(TextControl, {
                      value: item,
                      onChange: function (v) { setSectionItem(i, si, ii, v); },
                      placeholder: 'Ítem...'
                    }),
                    el(Button, { onClick: function () { removeSectionItem(i, si, ii); }, variant: 'link', isDestructive: true }, '✕')
                  );
                }),
                el(Button, {
                  onClick: function () { addSectionItem(i, si); },
                  variant: 'secondary',
                  style: { width: '100%' }
                }, '+ Ítem')
              );
            }),
            el(Button, {
              onClick: function () { addSection(i); },
              variant: 'secondary',
              style: { width: '100%', marginBottom: '8px' }
            }, '+ Sección'),

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
