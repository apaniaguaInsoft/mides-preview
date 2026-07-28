(function () {
  var el                = wp.element.createElement;
  var useBlockProps     = wp.blockEditor.useBlockProps;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var MediaUpload       = wp.blockEditor.MediaUpload;
  var MediaUploadCheck  = wp.blockEditor.MediaUploadCheck;
  var PanelBody         = wp.components.PanelBody;
  var TextControl       = wp.components.TextControl;
  var TextareaControl   = wp.components.TextareaControl;
  var SelectControl     = wp.components.SelectControl;
  var ToggleControl     = wp.components.ToggleControl;
  var Button            = wp.components.Button;

  var TIPO_OPTIONS = [
    { label: 'Texto',                                   value: 'texto' },
    { label: 'Lista (un ítem por línea)',                value: 'lista' },
    { label: 'Modalidades (nombre|||descripción / línea)', value: 'modalidades' }
  ];

  wp.blocks.registerBlockType('mides/programa-detail', {

    edit: function (props) {
      var attrs = props.attributes;
      var set   = props.setAttributes;
      var bp    = useBlockProps({ className: 'mides-programa-detail-editor' });

      /* ---- helpers de secciones ---- */
      function setSec(i, key, val) {
        set({
          secciones: attrs.secciones.map(function (s, idx) {
            if (idx !== i) return s;
            var c = Object.assign({}, s); c[key] = val; return c;
          })
        });
      }
      function addSec() {
        set({ secciones: attrs.secciones.concat([{ titulo: 'Nueva sección', tipo: 'texto', contenido: '' }]) });
      }
      function removeSec(i) {
        set({ secciones: attrs.secciones.filter(function (_, idx) { return idx !== i; }) });
      }
      function moveSec(i, dir) {
        var arr = attrs.secciones.slice();
        var t = i + dir;
        if (t < 0 || t >= arr.length) return;
        var tmp = arr[i]; arr[i] = arr[t]; arr[t] = tmp;
        set({ secciones: arr });
      }

      /* ---- helpers de tarjetas sidebar ---- */
      function setCard(i, key, val) {
        set({
          sidebarCards: attrs.sidebarCards.map(function (c, idx) {
            if (idx !== i) return c;
            var copy = Object.assign({}, c); copy[key] = val; return copy;
          })
        });
      }
      function addCard() {
        set({ sidebarCards: attrs.sidebarCards.concat([{ titulo: 'Nueva tarjeta', items: '' }]) });
      }
      function removeCard(i) {
        set({ sidebarCards: attrs.sidebarCards.filter(function (_, idx) { return idx !== i; }) });
      }

      var btnStyle  = { fontSize: 11, marginBottom: 6 };
      var panelStyle = { border: '1px solid #e2e8f0', borderRadius: 6, padding: 12, marginBottom: 12, background: '#f9fafb' };

      return el('div', bp,

        /* ============ INSPECTOR ============ */
        el(InspectorControls, null,

          /* Hero */
          el(PanelBody, { title: 'Hero', initialOpen: true },
            el('p', { style: { fontSize: 11, fontWeight: 600, margin: '0 0 6px' } }, 'Ícono del programa'),
            attrs.heroIconUrl && el('img', {
              src: attrs.heroIconUrl,
              style: { width: 64, height: 64, objectFit: 'contain', display: 'block', marginBottom: 8,
                       border: '1px solid #e2e8f0', borderRadius: 6, padding: 4, background: '#192854' }
            }),
            el(MediaUploadCheck, null,
              el(MediaUpload, {
                onSelect: function (m) { set({ heroIconId: m.id, heroIconUrl: m.url, heroIconAlt: m.alt || attrs.titulo }); },
                allowedTypes: ['image'],
                value: attrs.heroIconId,
                render: function (r) {
                  return el(Button, { onClick: r.open, variant: 'secondary', style: btnStyle },
                    attrs.heroIconId ? 'Cambiar ícono' : 'Subir ícono');
                }
              })
            ),
            attrs.heroIconId && el(Button, {
              onClick: function () { set({ heroIconId: 0, heroIconUrl: '', heroIconAlt: '' }); },
              variant: 'tertiary', isDestructive: true, style: btnStyle
            }, 'Quitar ícono'),
            el(TextControl, { label: 'Eyebrow', value: attrs.eyebrow,
              onChange: function (v) { set({ eyebrow: v }); } }),
            el(TextControl, { label: 'Nombre del programa', value: attrs.titulo,
              onChange: function (v) { set({ titulo: v }); } })
          ),

          /* Imagen principal */
          el(PanelBody, { title: 'Imagen principal', initialOpen: false },
            attrs.imgUrl && el('img', { src: attrs.imgUrl,
              style: { width: '100%', borderRadius: 6, marginBottom: 8, display: 'block' } }),
            el(MediaUploadCheck, null,
              el(MediaUpload, {
                onSelect: function (m) { set({ imgId: m.id, imgUrl: m.url, imgAlt: m.alt || attrs.titulo }); },
                allowedTypes: ['image'],
                value: attrs.imgId,
                render: function (r) {
                  return el(Button, { onClick: r.open, variant: 'secondary', style: btnStyle },
                    attrs.imgId ? 'Cambiar imagen' : 'Subir imagen');
                }
              })
            ),
            attrs.imgId && el(Button, {
              onClick: function () { set({ imgId: 0, imgUrl: '', imgAlt: '' }); },
              variant: 'tertiary', isDestructive: true, style: btnStyle
            }, 'Quitar imagen'),
            el(TextControl, { label: 'Texto alternativo', value: attrs.imgAlt,
              onChange: function (v) { set({ imgAlt: v }); } })
          ),

          /* Secciones */
          el(PanelBody, { title: 'Secciones de contenido', initialOpen: true },
            attrs.secciones.map(function (sec, i) {
              return el('div', { key: i, style: panelStyle },
                el('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 8 } },
                  el('span', { style: { fontSize: 12, fontWeight: 700, color: '#192854' } },
                    (i + 1) + '. ' + (sec.titulo || 'Sin título')),
                  el('div', { style: { display: 'flex', gap: 2 } },
                    el(Button, {
                      onClick: function () { moveSec(i, -1); },
                      variant: 'tertiary', style: { padding: '2px 6px', fontSize: 12 }, disabled: i === 0
                    }, '↑'),
                    el(Button, {
                      onClick: function () { moveSec(i, 1); },
                      variant: 'tertiary', style: { padding: '2px 6px', fontSize: 12 },
                      disabled: i === attrs.secciones.length - 1
                    }, '↓'),
                    el(Button, {
                      onClick: function () { removeSec(i); },
                      variant: 'tertiary', isDestructive: true, style: { padding: '2px 6px', fontSize: 12 }
                    }, '×')
                  )
                ),
                el(TextControl, { label: 'Título', value: sec.titulo,
                  onChange: function (v) { setSec(i, 'titulo', v); } }),
                el(SelectControl, { label: 'Tipo', value: sec.tipo, options: TIPO_OPTIONS,
                  onChange: function (v) { setSec(i, 'tipo', v); } }),
                el(TextareaControl, {
                  label: sec.tipo === 'lista'
                    ? 'Ítems (uno por línea)'
                    : sec.tipo === 'modalidades'
                    ? 'Modalidades (nombre|||descripción, una por línea)'
                    : 'Contenido',
                  value: sec.contenido, rows: 5,
                  onChange: function (v) { setSec(i, 'contenido', v); }
                })
              );
            }),
            el(Button, { onClick: addSec, variant: 'secondary', style: { width: '100%', marginTop: 8 } },
              '+ Agregar sección')
          ),

          /* Monto */
          el(PanelBody, { title: 'Monto (sidebar)', initialOpen: false },
            el(ToggleControl, { label: 'Mostrar tarjeta de monto', checked: attrs.showMonto,
              onChange: function (v) { set({ showMonto: v }); } }),
            attrs.showMonto && el(ToggleControl, { label: 'Marcar como GRATUITO', checked: attrs.montoFree,
              onChange: function (v) { set({ montoFree: v }); } }),
            attrs.showMonto && el(TextControl, { label: 'Etiqueta', value: attrs.montoLabel,
              onChange: function (v) { set({ montoLabel: v }); } }),
            attrs.showMonto && el(TextControl, { label: 'Monto (ej: Q.500.00)', value: attrs.montoAmount,
              onChange: function (v) { set({ montoAmount: v }); } }),
            attrs.showMonto && el(TextControl, { label: 'Sub-texto', value: attrs.montoSub,
              onChange: function (v) { set({ montoSub: v }); } })
          ),

          /* Chips */
          el(PanelBody, { title: 'Chips / etiquetas (sidebar)', initialOpen: false },
            el(TextControl, { label: 'Etiqueta del grupo', value: attrs.chipsLabel,
              onChange: function (v) { set({ chipsLabel: v }); } }),
            el(TextareaControl, { label: 'Chips (uno por línea)', value: attrs.chips, rows: 4,
              onChange: function (v) { set({ chips: v }); } })
          ),

          /* Tarjetas sidebar */
          el(PanelBody, { title: 'Tarjetas sidebar', initialOpen: false },
            attrs.sidebarCards.map(function (card, i) {
              return el('div', { key: i, style: panelStyle },
                el('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 8 } },
                  el('span', { style: { fontSize: 12, fontWeight: 700, color: '#192854' } },
                    card.titulo || ('Tarjeta ' + (i + 1))),
                  el(Button, {
                    onClick: function () { removeCard(i); },
                    variant: 'tertiary', isDestructive: true, style: { padding: '2px 6px', fontSize: 12 }
                  }, '×')
                ),
                el(TextControl, { label: 'Título', value: card.titulo,
                  onChange: function (v) { setCard(i, 'titulo', v); } }),
                el(TextareaControl, { label: 'Ítems (uno por línea)', value: card.items, rows: 5,
                  onChange: function (v) { setCard(i, 'items', v); } })
              );
            }),
            el(Button, { onClick: addCard, variant: 'secondary', style: { width: '100%', marginTop: 8 } },
              '+ Agregar tarjeta')
          )
        ),

        /* ============ PREVIEW EN EL EDITOR ============ */
        el('div', { className: 'mides-pd-editor__hero' },
          attrs.heroIconUrl && el('img', { src: attrs.heroIconUrl, className: 'mides-pd-editor__hero-icon' }),
          el('div', null,
            el('p', { className: 'mides-pd-editor__eyebrow' }, attrs.eyebrow || 'Programa'),
            el('h1', { className: 'mides-pd-editor__title' }, attrs.titulo || 'Nombre del programa')
          )
        ),

        el('div', { className: 'mides-pd-editor__body' },

          el('div', { className: 'mides-pd-editor__main' },
            attrs.imgUrl && el('img', { src: attrs.imgUrl, className: 'mides-pd-editor__img' }),
            attrs.secciones.map(function (sec, i) {
              return el('div', { key: i, className: 'mides-pd-editor__block' },
                el('h2', { className: 'mides-pd-editor__block-title' }, sec.titulo || '—'),
                el('p', { className: 'mides-pd-editor__block-tipo' },
                  sec.tipo === 'texto' ? 'Texto'
                  : sec.tipo === 'lista' ? 'Lista'
                  : 'Modalidades'
                )
              );
            })
          ),

          el('div', { className: 'mides-pd-editor__sidebar' },
            attrs.showMonto && attrs.montoAmount && el('div', { className: 'mides-pd-editor__monto' },
              el('p', { style: { fontSize: 11, margin: 0, color: 'rgba(255,255,255,0.75)' } }, attrs.montoLabel),
              el('p', { style: { fontSize: 24, fontWeight: 700, color: '#fff', margin: '4px 0' } }, attrs.montoAmount),
              el('p', { style: { fontSize: 11, margin: 0, color: 'rgba(255,255,255,0.75)' } }, attrs.montoSub)
            ),
            attrs.chips && el('div', { className: 'mides-pd-editor__chips' },
              el('p', { style: { fontSize: 11, fontWeight: 600, margin: '0 0 6px', color: '#192854' } }, attrs.chipsLabel),
              attrs.chips.split('\n').filter(Boolean).map(function (chip, i) {
                return el('span', { key: i, className: 'mides-pd-editor__chip' }, chip.trim());
              })
            ),
            attrs.sidebarCards.map(function (card, i) {
              if (!card.titulo && !card.items) return null;
              return el('div', { key: i, className: 'mides-pd-editor__scard' },
                el('p', { style: { fontSize: 11, fontWeight: 700, margin: 0, color: '#192854' } }, card.titulo)
              );
            })
          )
        )
      );
    },

    save: function () { return null; }
  });
})();
