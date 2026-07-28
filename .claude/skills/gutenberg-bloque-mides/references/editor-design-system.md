# Sistema de Diseño de Editor MIDES

Archivo fuente del CSS compartido: `wp-theme/mides-child/blocks/editor-shared.css`
Cargado via: `functions.php` → hook `enqueue_block_editor_assets`

Este archivo ya está encolado en el editor. No es necesario referenciarlo en `block.json` — aplica globalmente a todos los bloques MIDES.

---

## Paleta de colores MIDES (usar SIEMPRE estos valores exactos)

| Rol | Valor |
|-----|-------|
| Azul principal | `#192854` |
| Naranja acento | `#F2A119` |
| Borde / separador | `#E2E8F0` |
| Fondo claro panel | `#F9FAFB` |
| Fondo site | `#F4F6F9` |
| Texto secundario | `#718096` |
| Fondo placeholder | `#F0F4FF` |

---

## Clases disponibles (vienen de editor-shared.css)

### Preview wrapper

| Clase | Descripción |
|-------|-------------|
| `.mides-editor-preview` | Wrapper del bloque en el canvas del editor. Fondo blanco, padding `32px 24px`, border-radius `6px`. |
| `.mides-editor-preview--dark` | Modificador: fondo `#192854` (secciones con fondo azul). |
| `.mides-editor-preview--light` | Modificador: fondo `#F4F6F9` (secciones con fondo gris suave). |

### Tipografía del preview

| Clase | Descripción |
|-------|-------------|
| `.mides-editor-preview__title` | Título de sección. `font-size: 20px; font-weight: 700; color: #192854`. Con `--dark` cambia a `#F2A119` automáticamente. |
| `.mides-editor-preview__title--orange` | Forzar color naranja (útil sin `--dark`). |
| `.mides-editor-preview__intro` | Subtítulo / texto introductorio. `font-size: 13px; color: #718096`. Con `--dark` cambia a `rgba(255,255,255,0.7)`. |

### Inspector Controls (panel lateral)

| Clase | Descripción |
|-------|-------------|
| `.mides-panel-item` | Contenedor de un ítem en la lista del inspector (tarjeta, autoridad, mesa, etc.). Borde `#E2E8F0`, fondo `#F9FAFB`, padding `12px`, `border-radius: 6px`. |
| `.mides-panel-item__label` | Label del ítem: "1. Nombre del ítem". `font-size: 12px; font-weight: 700; color: #192854`. |
| `.mides-editor-media-label` | Label para campo de foto/ícono dentro del inspector ("Foto", "Ícono", etc.). `font-size: 11px; font-weight: 600; color: #718096`. |

### Placeholders de imagen

| Clase | Descripción |
|-------|-------------|
| `.mides-editor-img-placeholder` | Rectángulo gris para imagen aún no subida. Requiere `width` y `height` en el `editor.css` del bloque. |
| `.mides-editor-avatar-placeholder` | Círculo gris para foto de persona. Requiere `width` y `height` en el `editor.css` del bloque. |

### Placeholder de bloque dinámico

| Clase | Descripción |
|-------|-------------|
| `.mides-editor-placeholder` | Tarjeta con borde punteado azul `#192854`. Para bloques sin preview visual. |
| `.mides-editor-placeholder__icon` | Emoji/ícono grande (48px) dentro del placeholder. |
| `.mides-editor-placeholder__title` | Título dentro del placeholder. `font-size: 1.1rem; font-weight: 700; color: #192854`. |
| `.mides-editor-placeholder__desc` | Descripción. `font-size: 0.85rem; color: #718096`. |

---

## Contenido completo de editor-shared.css

Este es el CSS que ya vive en `wp-theme/mides-child/blocks/editor-shared.css`.
Si necesitas agregar clases comunes nuevas, modifica ese archivo (no el `editor.css` de cada bloque).

```css
/* MIDES Editor Design System
   Cargado globalmente en el editor vía enqueue_block_editor_assets.
   No importar desde block.json — aplica a todos los bloques MIDES automáticamente. */

/* ── Preview wrapper ──────────────────────────────────────────────────── */
.mides-editor-preview {
  padding: 32px 24px;
  background: #fff;
  border-radius: 6px;
}
.mides-editor-preview--dark  { background: #192854; }
.mides-editor-preview--light { background: #F4F6F9; }

/* Neutralizar el margin-bottom de Astra en párrafos dentro del editor */
.mides-editor-preview p { margin-bottom: 0 !important; }

/* ── Tipografía del preview ───────────────────────────────────────────── */
.mides-editor-preview__title {
  font-size: 20px;
  font-weight: 700;
  color: #192854;
  margin: 0 0 12px;
}
.mides-editor-preview__title--orange         { color: #F2A119; }
.mides-editor-preview--dark .mides-editor-preview__title { color: #F2A119; }

.mides-editor-preview__intro {
  font-size: 13px;
  color: #718096;
  margin: 0 0 20px;
}
.mides-editor-preview--dark .mides-editor-preview__intro { color: rgba(255,255,255,0.7); }

/* ── Inspector panel items ────────────────────────────────────────────── */
.mides-panel-item {
  border: 1px solid #E2E8F0;
  border-radius: 6px;
  padding: 12px;
  margin-bottom: 10px;
  background: #F9FAFB;
}
.mides-panel-item__label {
  font-size: 12px;
  font-weight: 700;
  color: #192854;
  margin: 0 0 8px;
}

/* ── Media field label ────────────────────────────────────────────────── */
.mides-editor-media-label {
  font-size: 11px;
  font-weight: 600;
  color: #718096;
  margin: 8px 0 2px;
  display: block;
}

/* ── Imagen / avatar placeholder (tamaño lo define el bloque) ─────────── */
.mides-editor-img-placeholder,
.mides-editor-avatar-placeholder {
  background: #E2E8F0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  color: #718096;
}
.mides-editor-img-placeholder    { border-radius: 6px; }
.mides-editor-avatar-placeholder { border-radius: 50%; }

/* ── Placeholder para bloques dinámicos ───────────────────────────────── */
.mides-editor-placeholder {
  background: #F0F4FF;
  border: 2px dashed #192854;
  border-radius: 8px;
  padding: 32px 24px;
  text-align: center;
  color: #192854;
}
.mides-editor-placeholder__icon {
  font-size: 48px;
  display: block;
  margin-bottom: 12px;
}
.mides-editor-placeholder__title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #192854;
  margin: 0 0 8px;
}
.mides-editor-placeholder__desc {
  font-size: 0.85rem;
  color: #718096;
  margin: 0;
}
```

---

## Cómo editor-shared.css está cargado

En `functions.php`, dentro del hook `enqueue_block_editor_assets`:

```php
add_action( 'enqueue_block_editor_assets', function () {
    wp_enqueue_style(
        'mides-editor-shared',
        get_stylesheet_directory_uri() . '/blocks/editor-shared.css',
        [],
        filemtime( get_stylesheet_directory() . '/blocks/editor-shared.css' )
    );
    // ... resto del hook (inline script de MIDES_THEME_URL)
} );
```

---

## Cuándo agregar algo a editor-shared.css vs editor.css de un bloque

| ¿Es un patrón que usarán ≥2 bloques? | → `editor-shared.css` |
| ¿Es el layout específico de este bloque (grilla, tamaño de card)? | → `editor.css` del bloque |
| ¿Es el color del fondo del preview de este bloque? | → Usar modificador `--dark`/`--light` de `.mides-editor-preview`; no agregar un nuevo color |
