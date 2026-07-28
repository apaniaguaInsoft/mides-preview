---
name: gutenberg-bloque-mides
description: Crear Y revisar bloques Gutenberg para el tema MIDES WordPress. Usar siempre que el usuario pida "crear un bloque", "nuevo bloque gutenberg", "agregar bloque", "revisar bloque", "estandarizar el editor", "auditar bloque", "actualizar estilo del editor", "el bloque no se ve bien en el editor", "aplicar estándar", o cualquier tarea relacionada con bloques del tema. También invocar proactivamente cuando se detecte que un bloque nuevo o existente no sigue el estándar visual de edición MIDES.
user-invocable: true
---

# Bloques Gutenberg MIDES — Creación y Revisión

Este skill cubre dos flujos:
1. **Crear** un nuevo bloque desde cero siguiendo los estándares MIDES
2. **Revisar/actualizar** un bloque existente para alinearlo con el estándar de editor

Lee [`references/editor-design-system.md`](references/editor-design-system.md) antes de escribir cualquier CSS o JS del editor. Contiene el CSS compartido completo y la lista de clases disponibles.

## Estructura de archivos

```
wp-theme/mides-child/
├── blocks/
│   ├── editor-shared.css          ← CSS compartido de todos los bloques (NO editar por bloque)
│   └── nombre-del-bloque/
│       ├── block.json             ← Metadatos, editorScript, editorStyle
│       ├── block.js               ← UI del editor (vanilla JS, sin JSX/webpack)
│       ├── block.asset.php        ← Dependencias JS (CRÍTICO — sin esto falla en silencio)
│       ├── editor.css             ← Solo estilos ESPECÍFICOS de este bloque
│       └── render.php             ← HTML del frontend (PHP dinámico)
└── js/
    └── nombre-bloque.js           ← JS del frontend (si el bloque tiene interactividad)
```

## Regla de oro del editor

**Todo bloque DEBE tener un preview visual** que muestre la estructura del bloque en el canvas del editor. El usuario tiene que poder entender qué contiene el bloque sin abrir el panel lateral. El fidelidad del preview no tiene que ser perfecta, pero la estructura (grilla, cards, título + texto, etc.) tiene que ser reconocible.

**Dos excepciones** donde el placeholder estandarizado (`.mides-editor-placeholder`) es aceptable:
- Bloques que renderizan contenido 100% dinámico sin atributos editables (e.g., lista de posts filtrada por taxonomía)
- Bloques con InnerBlocks — el preview son los bloques anidados

## Checklist de creación (nuevo bloque)

1. Preguntar al usuario: nombre, propósito, atributos, si tiene JS en frontend
2. Crear carpeta `blocks/{nombre}/`
3. `block.json` — prefijo `mides/` siempre, incluir `editorScript` y `editorStyle`
4. `block.asset.php` — dependencias: `wp-blocks`, `wp-element`, `wp-block-editor`, `wp-components`
5. `block.js` — `useBlockProps` obligatorio en `edit()`, `save: () => null`, preview visual siempre
6. `editor.css` — solo clases específicas del bloque; los patrones comunes vienen de `editor-shared.css`
7. `render.php` — sanitizar siempre: `esc_html()`, `esc_url()`, `esc_attr()`
8. Si tiene JS frontend: crear `js/{nombre}.js`
9. Registrar en `functions.php` con `register_block_type()` + render callback
10. Desplegar y verificar

## Checklist de revisión (bloque existente)

Al revisar un bloque, verificar y corregir:
- [ ] El editor muestra un **preview visual** (no solo un placeholder de texto genérico cuando podría mostrar estructura)
- [ ] Colores exactos: `#192854` (azul MIDES), `#F2A119` (naranja), `#E2E8F0` (borde)
- [ ] Los contenedores de items en el inspector usan `.mides-panel-item` y `.mides-panel-item__label` (no clases propias que duplican esto)
- [ ] Labels de foto/ícono en el inspector usan `.mides-editor-media-label`
- [ ] El wrapper del preview usa `.mides-editor-preview` (y modificador `--dark` o `--light` según el fondo)
- [ ] Los títulos del preview usan `.mides-editor-preview__title`
- [ ] No hay inline styles para colores o tipografía que debería manejar CSS
- [ ] Botones "Eliminar" usan `variant='tertiary'` + `isDestructive: true`
- [ ] Botones "Agregar" usan `variant='secondary'` + `style={{marginTop:'8px', width:'100%'}}`
- [ ] Se añade `margin-bottom: 0 !important` a `p` dentro del preview (contrarresta Astra)

## Patrones de preview por tipo de bloque

### Sección con grilla de cards
```js
el('div', bp,
  el(InspectorControls, null, /* ... */),
  el('div', { className: 'mides-editor-preview mides-editor-preview--light' },
    el('h2', { className: 'mides-editor-preview__title' }, attrs.titulo),
    el('p', { className: 'mides-editor-preview__intro' }, attrs.intro),
    el('div', { className: 'mides-NOMBRE-editor__grid' },
      items.map(function(item, i) {
        return el('div', { key: i, className: 'mides-NOMBRE-editor__card' },
          /* preview del card */
        );
      })
    )
  )
)
```

### Sección con fondo azul MIDES
Usar `.mides-editor-preview mides-editor-preview--dark` en el wrapper. Los títulos dentro usan `.mides-editor-preview__title` (automáticamente se colorean de naranja por el CSS compartido).

### Bloque simple (título + texto + imagen)
No hace falta grilla — mostrar el título, el texto truncado y un placeholder de imagen si aún no hay imagen.

### Bloque dinámico (posts, documentos por taxonomía)
Usar `.mides-editor-placeholder` con:
```js
el('div', bp,
  el(InspectorControls, null, /* controles */),
  el('div', { className: 'mides-editor-placeholder' },
    el('span', { className: 'mides-editor-placeholder__icon' }, '📄'),
    el('h3', { className: 'mides-editor-placeholder__title' }, 'Nombre del Bloque'),
    el('p', { className: 'mides-editor-placeholder__desc' }, 'Descripción de lo que renderiza')
  )
)
```

### Panel items en InspectorControls (lista de cards/autoridades/etc.)
```js
el(PanelBody, { title: 'Items', initialOpen: true },
  items.map(function(item, i) {
    return el('div', { key: i, className: 'mides-panel-item' },
      el('p', { className: 'mides-panel-item__label' }, (i+1) + '. ' + (item.nombre || 'Sin nombre')),
      el(TextControl, { label: 'Nombre', value: item.nombre, onChange: /* ... */ }),
      /* Label para imagen: */
      el('span', { className: 'mides-editor-media-label' }, 'Foto'),
      /* MediaUpload... */
      el(Button, { onClick: /* remove */, variant: 'tertiary', isDestructive: true, style: { fontSize: '11px', marginTop: '4px' } }, 'Eliminar')
    );
  }),
  el(Button, { onClick: /* add */, variant: 'secondary', style: { marginTop: '8px', width: '100%' } }, '+ Agregar')
)
```

## Reglas importantes

- **NO** usar `viewScript` con rutas relativas `../../` en `block.json` — falla silenciosamente
- `useBlockProps` es **obligatorio** en `edit()` — sin él el bloque no es clickeable en el editor
- `save()` siempre retorna `null` (render dinámico via PHP)
- Para arrays de items usar `.concat([nuevoItem])` — nunca mutar el array directamente
- El CSS del frontend viene de `styles.css` → `mides.css`; el `editor.css` solo aplica en el editor
- Para imágenes guardar `imgId` (para PHP) e `imgUrl` (para preview en editor)
- Siempre sanitizar en `render.php`: `esc_html()` para texto, `esc_url()` para URLs, `esc_attr()` para atributos

## Despliegue al Docker

```bash
docker cp wp-theme/mides-child/. wordpress_app:/var/www/html/wp-content/themes/mides-child/
docker exec wordpress_app wp cache flush --allow-root
```

## Insertar bloque en una página

```bash
docker exec wordpress_app wp post update PAGE_ID \
  --post_content='<!-- wp:mides/nombre-bloque {"attr":"valor"} /-->' \
  --allow-root
```
