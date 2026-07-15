# Cómo crear bloques Gutenberg para el tema MIDES

Guía práctica basada en el bloque `mides/hero-carousel` ya implementado.  
**Sin build tools** — vanilla JS, PHP puro, sin webpack ni npm.

---

## Estructura de archivos

Cada bloque vive en su propia carpeta dentro de `wp-theme/mides-child/blocks/`:

```
blocks/
└── nombre-del-bloque/
    ├── block.json        ← Metadatos y atributos del bloque
    ├── block.js          ← UI del editor (Gutenberg)
    ├── block.asset.php   ← Dependencias JS (OBLIGATORIO)
    ├── editor.css        ← Estilos solo en el editor
    └── render.php        ← HTML final del frontend (PHP)
```

El JS del frontend (si hay interactividad) va en `js/` a nivel del tema y se encola desde `functions.php`.

---

## Paso 1 — `block.json`

Define nombre, atributos y qué archivos usar.

```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "mides/nombre-bloque",
  "title": "Nombre Visible",
  "description": "Descripción corta.",
  "category": "media",
  "icon": "images-alt2",
  "keywords": ["palabra", "clave"],
  "supports": {
    "html": false,
    "align": ["full"]
  },
  "attributes": {
    "align": { "type": "string", "default": "full" },
    "titulo": { "type": "string", "default": "" },
    "imagen": { "type": "number", "default": 0 },
    "imagenUrl": { "type": "string", "default": "" },
    "items": {
      "type": "array",
      "default": [],
      "items": {
        "type": "object",
        "properties": {
          "texto": { "type": "string", "default": "" },
          "imgId":  { "type": "number", "default": 0 },
          "imgUrl": { "type": "string", "default": "" }
        }
      }
    }
  },
  "editorScript": "file:./block.js",
  "editorStyle":  "file:./editor.css"
}
```

**Reglas importantes:**
- `name` siempre con prefijo `mides/`
- No uses `viewScript` con rutas relativas `../../` — falla. Encola el JS del frontend desde `functions.php`.
- `"html": false` evita que el usuario edite el HTML crudo.

---

## Paso 2 — `block.asset.php` ⚠️ CRÍTICO

Sin este archivo el bloque carga sin dependencias y el editor falla silenciosamente.

```php
<?php
return [
    'dependencies' => [
        'wp-blocks',
        'wp-element',
        'wp-block-editor',
        'wp-components',
        'wp-i18n',
    ],
    'version' => '1.0.0',
];
```

---

## Paso 3 — `block.js` (UI del editor)

Vanilla JS con `wp.element.createElement`. Sin JSX, sin compilación.

```javascript
(function () {
  var registerBlockType = wp.blocks.registerBlockType;
  var el                = wp.element.createElement;
  var Fragment          = wp.element.Fragment;
  var useBlockProps     = wp.blockEditor.useBlockProps;    // OBLIGATORIO
  var InspectorControls = wp.blockEditor.InspectorControls;
  var MediaUpload       = wp.blockEditor.MediaUpload;
  var MediaUploadCheck  = wp.blockEditor.MediaUploadCheck;
  var PanelBody         = wp.components.PanelBody;
  var TextControl       = wp.components.TextControl;
  var Button            = wp.components.Button;

  registerBlockType('mides/nombre-bloque', {

    edit: function (props) {
      var attrs      = props.attributes;
      var blockProps = useBlockProps({ className: 'mi-bloque-wrap' }); // OBLIGATORIO

      /* Panel lateral (Inspector) */
      var inspector = el(InspectorControls, null,
        el(PanelBody, { title: 'Configuración', initialOpen: true },

          /* Campo de texto */
          el(TextControl, {
            label: 'Título',
            value: attrs.titulo,
            onChange: function (v) { props.setAttributes({ titulo: v }); }
          }),

          /* Selector de imagen */
          el(MediaUploadCheck, null,
            el(MediaUpload, {
              onSelect: function (media) {
                props.setAttributes({ imagen: media.id, imagenUrl: media.url });
              },
              allowedTypes: ['image'],
              value: attrs.imagen || undefined,
              render: function (ref) {
                return el(Fragment, null,
                  attrs.imagenUrl && el('img', {
                    src: attrs.imagenUrl,
                    style: { width: '100%', height: '80px', objectFit: 'cover', marginBottom: '8px' }
                  }),
                  el(Button, {
                    onClick: ref.open,
                    variant: 'secondary',
                    style: { width: '100%' }
                  }, attrs.imagenUrl ? 'Cambiar imagen' : 'Seleccionar imagen')
                );
              }
            })
          )
        )
      );

      /* Preview en el editor */
      var preview = el('div', { className: 'mi-bloque-preview' },
        attrs.imagenUrl && el('img', { src: attrs.imagenUrl, style: { width: '100%' } }),
        attrs.titulo    && el('h2', null, attrs.titulo)
      );

      /* SIEMPRE retornar Fragment con inspector + div con blockProps */
      return el(Fragment, null,
        inspector,
        el('div', blockProps, preview)
      );
    },

    /* El HTML final lo genera render.php, aquí siempre null */
    save: function () { return null; }
  });
})();
```

**Reglas importantes:**
- `useBlockProps` es **obligatorio** — sin él el bloque no es clickeable en el editor.
- `save` siempre retorna `null` porque el render es dinámico (PHP).
- Para arrays (ej. slides), usa `props.setAttributes({ items: items.concat([nuevoItem]) })` — nunca mutar el array directamente.

### Componentes disponibles

| Componente | Uso |
|---|---|
| `TextControl` | Campo de texto simple |
| `TextareaControl` | Texto multilínea |
| `ToggleControl` | Interruptor on/off |
| `SelectControl` | Dropdown de opciones |
| `ColorPicker` | Selector de color |
| `MediaUpload` + `MediaUploadCheck` | Selector de imagen/video |
| `Button` | Botón con `variant: 'primary'/'secondary'/'link'` |
| `PanelBody` | Sección colapsable en el inspector |

---

## Paso 4 — `render.php` (HTML del frontend)

WordPress llama a este archivo con las variables `$attributes` y `$content` disponibles.

```php
<?php
$titulo    = $attributes['titulo'] ?? '';
$imagen_id = $attributes['imagen'] ?? 0;
$items     = $attributes['items']  ?? [];

// Obtener URL de imagen desde su ID (respeta regeneraciones, CDN, etc.)
$imagen_url = $imagen_id
    ? wp_get_attachment_image_url( (int) $imagen_id, 'full' )
    : ( $attributes['imagenUrl'] ?? '' );

if ( empty( $titulo ) ) return; // no renderizar si no hay contenido
?>

<section class="mi-bloque">
  <?php if ( $imagen_url ) : ?>
    <img src="<?php echo esc_url( $imagen_url ); ?>" alt="" class="mi-bloque__img" />
  <?php endif; ?>

  <?php if ( $titulo ) : ?>
    <h2 class="mi-bloque__titulo"><?php echo esc_html( $titulo ); ?></h2>
  <?php endif; ?>

  <?php foreach ( $items as $i => $item ) : ?>
    <div class="mi-bloque__item">
      <p><?php echo esc_html( $item['texto'] ?? '' ); ?></p>
    </div>
  <?php endforeach; ?>
</section>
```

**Reglas importantes:**
- Siempre usar `esc_html()`, `esc_url()`, `esc_attr()` para sanitizar salidas.
- Preferir `wp_get_attachment_image_url( $id, 'full' )` sobre guardar URLs crudas — así WordPress puede regenerar tamaños.
- Guardar tanto `imgId` como `imgUrl` en los atributos: el ID para el render PHP, la URL para el preview en el editor.

---

## Paso 5 — Registrar el bloque en `functions.php`

```php
/* Registrar el bloque */
add_action( 'init', function () {
    register_block_type(
        get_stylesheet_directory() . '/blocks/nombre-del-bloque',
        [ 'render_callback' => 'mides_render_nombre_bloque' ]
    );
} );

/* Función render — encola JS del frontend aquí, no en block.json */
function mides_render_nombre_bloque( array $attributes ): string {
    // Encolar JS del frontend solo cuando el bloque se usa
    wp_enqueue_script(
        'mides-nombre-bloque',
        get_stylesheet_directory_uri() . '/js/nombre-bloque.js',
        [],
        wp_get_theme()->get( 'Version' ),
        true  // cargar en footer
    );

    ob_start();
    include get_stylesheet_directory() . '/blocks/nombre-del-bloque/render.php';
    return ob_get_clean();
}
```

---

## Paso 6 — Insertar el bloque en una página vía REST API

Para poner el bloque en el contenido de una página programáticamente:

```bash
# Con wp-cli dentro del contenedor Docker:
docker exec wordpress_app wp post update 25 \
  --post_content='<!-- wp:mides/nombre-bloque {"titulo":"Hola","items":[]} /-->' \
  --allow-root
```

O vía REST API (curl):

```bash
curl -s -u "admin:TU_APP_PASSWORD" \
  -X POST "http://localhost:8080/wp-json/wp/v2/pages/25" \
  -H "Content-Type: application/json" \
  -d '{"content":"<!-- wp:mides/nombre-bloque {\"titulo\":\"Hola\"} /-->"}'
```

---

## Checklist para un bloque nuevo

```
[ ] Crear carpeta blocks/nombre-del-bloque/
[ ] block.json          — nombre, atributos, editorScript, editorStyle
[ ] block.asset.php     — dependencias (wp-blocks, wp-element, wp-block-editor, wp-components)
[ ] block.js            — edit() con useBlockProps + InspectorControls, save() → null
[ ] editor.css          — estilos del preview en el editor
[ ] render.php          — HTML del frontend con esc_html/esc_url
[ ] js/nombre.js        — JS del frontend (si hay interactividad)
[ ] functions.php       — register_block_type() + render callback que encola el JS
[ ] docker cp           — copiar el tema al contenedor
```

---

## Copiar cambios al Docker

```bash
# Todo el tema de una vez
docker cp wp-theme/mides-child/. wordpress_app:/var/www/html/wp-content/themes/mides-child/

# Solo el CSS
docker cp styles.css wordpress_app:/var/www/html/wp-content/themes/mides-child/mides.css

# Limpiar caché de bloques (si el editor no ve los cambios)
docker exec wordpress_app wp cache flush --allow-root
```

---

## Referencia rápida — Tipos de atributos en `block.json`

```json
"attributes": {
  "texto":    { "type": "string",  "default": "" },
  "numero":   { "type": "number",  "default": 0 },
  "activo":   { "type": "boolean", "default": false },
  "opciones": {
    "type": "array",
    "default": [],
    "items": { "type": "object", "properties": { "label": { "type": "string" } } }
  },
  "align":    { "type": "string",  "default": "full" }
}
```
