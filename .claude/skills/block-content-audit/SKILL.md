---
name: block-content-audit
description: Audit custom Gutenberg blocks in the MIDES WordPress theme to verify that all texts, images, and icons are editable from the WordPress admin (not hardcoded in render.php). Invoke this skill whenever the user asks to "audit blocks", "revisar bloques", "check if block content is editable", "find hardcoded content", "revisar textos hardcodeados", "revisar iconos hardcodeados", "qué textos o iconos están fijos en los bloques", "validar editabilidad de bloques", "cuáles bloques tienen contenido hardcodeado", "se pueden cambiar los iconos", or wants to know whether any specific block or all blocks have content that cannot be changed from the WordPress editor. Also use when the user creates a new block and wants to verify it follows the editability standard.
---

# Block Content Audit

Inspect custom Gutenberg blocks in the MIDES WordPress theme and identify any texts, images, or icons that are hardcoded in `render.php` instead of being editable from the Gutenberg editor or WordPress Customizer.

## Project Context

- Block files: `wp-theme/mides-child/blocks/<block-name>/`
- Each block has three key files:
  - `block.json` — registered attributes (defines what *can* be stored)
  - `block.js` — editor UI controls (defines what *is actually editable* in Gutenberg)
  - `render.php` — frontend HTML output (where hardcoded strings hide)
- WordPress runs in Docker `wordpress_app` at `http://localhost:8080`
- Full methodology reference: `BLOCK-CONTENT-AUDIT.md` in the project root

## Audit Steps

### 1 — Determine scope

```bash
ls wp-theme/mides-child/blocks/
```

If the user named a specific block, audit only that one. Otherwise audit all blocks. Skip blocks the user hasn't asked about.

### 2 — For each block, read all three files in parallel

Read `block.json`, `block.js`, and `render.php` simultaneously to save time.

**From `block.json`:** Extract the full list of registered attribute names and their default values.

**From `block.js`:** Find every `props.setAttributes` call. Each attribute key referenced there has a UI control and IS editable. Look for these control types: `TextControl`, `TextareaControl`, `RangeControl`, `SelectControl`, `MediaUpload`, `CheckboxControl`, `ToggleControl`. An attribute in `block.json` that is NOT referenced in `block.js` can never be changed from the editor — it will always use its default.

**From `render.php`:** Every piece of visible text, image, or content icon that does NOT come from `$attributes[...]` or `get_theme_mod(...)` is hardcoded. Use this quick grep as a starting point, then read the full file to confirm:

```bash
grep -n "'>.\|\">[A-Z]\|get_stylesheet_directory_uri.*img/" \
  wp-theme/mides-child/blocks/<block>/render.php 2>/dev/null | \
  grep -v '\$attributes\|\$theme_mod\|\$url\|\$image\|\$icon\|esc_\|class=\|id=\|href=\|src=\|Linea-Resaltadora\|line[0-9]'
```

The extra pattern catches hardcoded theme image/icon paths (`get_stylesheet_directory_uri() . '/img/...'`). The exclusions filter out the decorative underline SVGs that are intentionally fixed.

### 3 — Optionally verify against live page data

The serialized JSON saved in the database only contains attributes that were explicitly set by the editor. Anything not in this JSON is coming from `render.php` logic (either an attribute default or a hardcoded value).

```bash
# Get page blocks (page ID 25 = homepage)
curl -s -u "admin:JSbzSR8f4FYfbpKUlNUWqSBJ" \
  "http://localhost:8080/wp-json/wp/v2/pages/25?context=edit" | \
  python3 -c "import json,sys; print(json.load(sys.stdin)['content']['raw'])"

# Find other page IDs
curl -s -u "admin:JSbzSR8f4FYfbpKUlNUWqSBJ" \
  "http://localhost:8080/wp-json/wp/v2/pages?per_page=20&_fields=id,slug,title" | \
  python3 -m json.tool
```

## Classification Rules

### Texts & Images

| What you find in render.php | Classification |
|---|---|
| `$attributes['key']` | ✅ Editable via Gutenberg |
| `get_theme_mod('key')` | ⚠️ Editable via Customizer only |
| Attribute in block.json but no control in block.js | ⚠️ Attribute exists but editor can't change it |
| Literal PHP string or static HTML text | ❌ Hardcoded — not editable |

### Icons — the key distinction

Icons come in two flavors and the rule is simple: **if a site editor would reasonably want to swap it out, it should be editable**.

| What you find in render.php | Classification |
|---|---|
| `wp_get_attachment_image_url($attributes['iconId'])` with `iconId > 0` in saved data | ✅ Editable — media library |
| `$attributes['iconUrl']` used as `<img src>` but `iconId` is 0 or missing | ⚠️ Partial — URL stored but not in media library |
| `get_stylesheet_directory_uri() . '/img/icon.svg'` for a **content icon** | ❌ Hardcoded — should be an attribute |
| `get_stylesheet_directory_uri() . '/img/icon.svg'` for a **decorative element** | — Skip (intentional) |
| Inline `<svg>` code (arrows, dividers, decorative shapes) | — Decorative, skip |

**Content icons** — icons that represent a specific program, card, person, or category — should always be editable via `MediaUpload` (which also accepts SVG files). Examples: program icons in `mides/programas-sociales`, card icons in `mides/institucional`, authority photos in `mides/autoridades`.

**Decorative icons** — arrows in buttons, underline lines (`Linea-Resaltadora.svg`), divider shapes — are structural and do not need to be editable. Do not flag these.

### Media library standard for content icons

**All content icons must be uploaded to the WordPress media library** (i.e., `iconId > 0` in the saved block attributes). A block that stores only a URL (`iconUrl` without a valid `iconId`) is ⚠️ partial — the URL may break if the domain or theme path changes, and the icon won't appear in the Gutenberg media picker.

When auditing saved page data via the REST API, check that `iconId` is a non-zero number for every content icon slot. If `iconId` is 0 or absent even though the block code supports `MediaUpload`, flag it as ⚠️ **icon not uploaded to media library**.

To fix: upload the SVG/image via the WordPress admin (Media → Add New) or programmatically with `wp_insert_attachment()`, then save the resulting attachment ID back to the block's `iconId` attribute.

## Output Format

Produce a per-block report section, then a summary table.

**Per-block section:**

```
### `mides/<block-name>`

**Texts**
| Content | Source | Status |
|---|---|---|
| "Example text" | $attributes['myText'] | ✅ Editable |
| "Hardcoded label" | Literal string (render.php:42) | ❌ Hardcoded |
| "Denuncia URL" | get_theme_mod('url_denuncia') | ⚠️ Customizer |

**Images & Icons**
| Element | Type | Source | Status |
|---|---|---|---|
| Hero background | Image | $attributes['bgUrl'] via MediaUpload | ✅ Editable |
| Program icon | Content icon | $attributes['iconId'] via MediaUpload | ✅ Editable |
| bono_social.svg | Content icon | theme path /img/bono_social.svg (render.php:18) | ❌ Hardcoded |
| Arrow in button | Decorative icon | Inline SVG | — Decorative, skip |
| Underline line | Decorative | Linea-Resaltadora.svg via theme path | — Decorative, skip |

**Attributes without editor controls**
- `someAttr` — in block.json (default: "value") but no TextControl/MediaUpload in block.js

**Overall**: ✅ Fully editable / ⚠️ Partially editable / ❌ Has hardcoded content
```

**Summary table (always at the end):**

```
## Audit Summary

| Block | Hardcoded Texts | Hardcoded Icons | Missing Controls | Status |
|---|---|---|---|---|
| mides/hero-carousel | 0 | 0 | 0 | ✅ |
| mides/programas-sociales | 0 | 0 | 0 | ✅ |
| mides/contacto-info | 3 | 1 | 0 | ❌ |
...

**X block(s) need attention.**
```

## Making Hardcoded Content Editable

If the user asks you to fix the issues found, apply this pattern for each hardcoded value:

**1. `block.json`** — add the attribute with the current hardcoded value as default:
```json
"myText": { "type": "string", "default": "Current hardcoded text" }
```

**2. `block.js`** — add a control inside `InspectorControls` (in an appropriate `PanelBody`):

For text:
```js
el(TextControl, {
  label: 'Descriptive label',
  value: props.attributes.myText,
  onChange: function (v) { props.setAttributes({ myText: v }); }
})
```

For content icons (SVG or image), always store both the media library ID and the URL — the ID lets WordPress regenerate the URL if the domain changes, and the URL works as a fallback:
```js
// In block.json: "iconId": { "type": "number", "default": 0 }, "iconUrl": { "type": "string", "default": "" }
el(MediaUploadCheck, null,
  el(MediaUpload, {
    onSelect: function (media) {
      props.setAttributes({ iconId: media.id, iconUrl: media.url });
    },
    allowedTypes: ['image'],  // SVG files are also accepted as 'image'
    value: props.attributes.iconId || undefined,
    render: function (ref) {
      return el(Button, { onClick: ref.open, variant: 'secondary' },
        props.attributes.iconId ? 'Cambiar ícono' : 'Seleccionar ícono'
      );
    }
  })
)
```

See `programas-sociales/block.js` for a complete real-world example of icon upload in a repeatable list.

**3. `render.php`** — replace the hardcoded value with the attribute, always with a fallback:

For text:
```php
$my_text = $attributes['myText'] ?? 'Current hardcoded text';
// ...
<p><?php echo esc_html( $my_text ); ?></p>
```

For a content icon that was a hardcoded theme path:
```php
$icon_id  = (int) ( $attributes['iconId']  ?? 0 );
$icon_url = $attributes['iconUrl'] ?? '';

// Prefer the media library URL (resolves domain changes); fall back to stored URL
if ( $icon_id ) {
    $icon_url = wp_get_attachment_image_url( $icon_id, 'full' ) ?: $icon_url;
}
// ...
<?php if ( $icon_url ) : ?>
  <img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $icon_alt ); ?>" />
<?php endif; ?>
```

The `?? 'default'` fallback ensures blocks already saved in the database (without the new attribute) continue to render correctly.

**4. Deploy to Docker:**
```bash
docker cp wp-theme/mides-child/blocks/<block-name>/. \
  wordpress_app:/var/www/html/wp-content/themes/mides-child/blocks/<block-name>/
```

**5. Update `BLOCK-CONTENT-AUDIT.md`** — change the block's row in the "Blocks Audit Status" table to ✅.
