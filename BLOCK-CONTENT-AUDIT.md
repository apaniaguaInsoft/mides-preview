# Block Content Audit Guide

A step-by-step process for finding hardcoded text and images inside custom Gutenberg block render callbacks, and a plan for making them editable from the WordPress admin.

---

## Tools Available

### Option A — WordPress REST API (curl)

The quickest way to inspect live page content without any plugin.

```bash
# Get homepage block markup (raw)
curl -s -u "admin:JSbzSR8f4FYfbpKUlNUWqSBJ" \
  "http://localhost:8080/wp-json/wp/v2/pages/25?context=edit" | \
  python3 -c "import json,sys; print(json.load(sys.stdin)['content']['raw'])"

# List all pages (to find IDs)
curl -s -u "admin:JSbzSR8f4FYfbpKUlNUWqSBJ" \
  "http://localhost:8080/wp-json/wp/v2/pages?per_page=20&_fields=id,slug,title" | \
  python3 -m json.tool

# Get any page by ID
curl -s -u "admin:JSbzSR8f4FYfbpKUlNUWqSBJ" \
  "http://localhost:8080/wp-json/wp/v2/pages/<ID>?context=edit" | \
  python3 -c "import json,sys; print(json.load(sys.stdin)['content']['raw'])"
```

Credentials (Docker local environment):
- URL: `http://localhost:8080`
- Username: `admin`
- Password: `JSbzSR8f4FYfbpKUlNUWqSBJ` (Application Password)

### Option B — MCP WordPress (via Claude Code)

The MCP server currently only exposes **WPForms** abilities (`wpforms/list-forms`, etc.). It does **not** expose page content, themes, or the filesystem. Use the REST API above for content inspection.

To verify current MCP capabilities at any time:

```
1. In Claude Code, run ToolSearch: select:mcp__wordpress__mcp-adapter-discover-abilities
2. Call discover-abilities with no arguments
3. Review the list of available ability IDs
```

If the MCP adapter plugin is updated in the future and gains content/post abilities, the flow would be:
```
1. ToolSearch "select:mcp__wordpress__mcp-adapter-get-ability-info"
2. get-ability-info for the target ability to see its parameters
3. ToolSearch "select:mcp__wordpress__mcp-adapter-execute-ability"
4. execute-ability with the correct parameters
```

### Option C — Direct file inspection

All block files live at:
```
wp-theme/mides-child/blocks/<block-name>/
  block.json    ← registered attributes (what CAN be editable)
  block.js      ← editor UI controls (what IS editable in Gutenberg)
  render.php    ← frontend output (look for hardcoded strings here)
  editor.css    ← editor-only styles
```

---

## Review Process

### Step 1 — Get the page's block structure

Use the REST API curl command above to print the raw block markup of the page you are auditing. Each block appears as an HTML comment:

```
<!-- wp:mides/hero-carousel {"slides":[...], "interval":10} /-->
<!-- wp:mides/programas-sociales {"programs":[...]} /-->
```

The JSON inside the comment is the **serialized attribute data** saved in the database. Any text or image URL that does NOT appear here is hardcoded in `render.php`.

### Step 2 — For each block, compare attributes vs. render output

Open `block.json` and list all registered attributes. Then open `render.php` and check every piece of visible content:

- If it uses `$attributes['someKey']` → **editable** ✅
- If it uses `get_theme_mod(...)` → **editable via Customizer** ⚠️
- If it is a literal PHP string → **hardcoded, not editable** ❌

Example of a hardcoded string in render.php:
```php
<p class="gratuitos-banner__top">TODOS NUESTROS</p>  <!-- ❌ hardcoded -->
<p class="gratuitos-banner__free"><?php echo esc_html( $text_free ); ?></p>  <!-- ✅ editable -->
```

### Step 3 — Check that block.js has a UI control for each attribute

An attribute in `block.json` is only editable if `block.js` registers a control for it (TextControl, MediaUpload, RangeControl, etc.) inside the `InspectorControls` panel. If an attribute exists in block.json but has no control in block.js, the editor cannot change it — it will always use its default value.

### Step 4 — Document findings per block (use the table below)

---

## Making Content Editable — Implementation Steps

For each hardcoded value you want to expose:

**1. Add the attribute to `block.json`**
```json
"myText": { "type": "string", "default": "Hardcoded value here" }
```

**2. Add a UI control to `block.js` inside `InspectorControls`**
```js
el(TextControl, {
  label: 'My label',
  value: props.attributes.myText,
  onChange: function (v) { props.setAttributes({ myText: v }); }
})
```
For images, use `MediaUpload` + `MediaUploadCheck` (see `hero-carousel/block.js` for reference).

**3. Replace the hardcoded value in `render.php`**
```php
// Before
<p>HARDCODED TEXT</p>

// After
$my_text = $attributes['myText'] ?? 'Hardcoded value here';
// ...
<p><?php echo esc_html( $my_text ); ?></p>
```

Always use `?? 'default'` so existing blocks that don't have the attribute saved yet still render correctly (backward-compatible).

**4. Deploy to Docker**
```bash
docker cp wp-theme/mides-child/blocks/<block-name>/. \
  wordpress_app:/var/www/html/wp-content/themes/mides-child/blocks/<block-name>/
```

**5. Verify in the editor**

Open the page in WordPress Admin → edit the page → select the block → check that the new panel appears in the right sidebar with the correct default value → change it and confirm the frontend updates.

---

## Blocks Audit Status

### Homepage (`/`) — Page ID: 25

| Block | Hardcoded Texts | Hardcoded Images | Status |
|---|---|---|---|
| `mides/hero-carousel` | None | None | ✅ Fully editable |
| `mides/programas-sociales` | None | Icons imported to media library (IDs 258–266) — all 9 programs have `iconId` set | ✅ Fully editable |
| `mides/banner-gratuitos` | "TODOS NUESTROS", "PROGRAMAS SOCIALES", "SON", "GRATUITOS", "No debes pagar por NADA", "DENUNCIA AQUÍ", "CUALQUIER IRREGULARIDAD" | None | ✅ Fixed — all texts now editable |
| `mides/noticias-recientes` | None | None | ✅ Fully editable |
| `mides/institucional` | None | Icons imported to media library (IDs 254–257) — all 4 cards have `iconId` set | ✅ Fully editable |

### Other blocks (not yet audited)

| Block | Audited | Notes |
|---|---|---|
| `mides/hero-interior` | ❌ | Used on interior pages — review render.php |
| `mides/about-block` | ❌ | Possibly static text |
| `mides/autoridades` | ❌ | May contain hardcoded authority names |
| `mides/mesas-tecnicas` | ❌ | Review render.php for hardcoded labels |
| `mides/documentos-lista` | ❌ | CPT-driven but may have static UI labels |
| `mides/contacto-info` | ❌ | High risk — contact details are often hardcoded |
| `mides/contacto-mapa-form` | ❌ | Check map embed and form labels |
| `mides/infopub-tabs` | ❌ | Tab labels may be hardcoded |
| `mides/infopub-tab-panel` | ❌ | Panel content |
| `mides/infopub-lista-categoria` | ❌ | Category filter labels |
| `mides/infopub-card-link` | ❌ | Card text |

---

## Quick Audit Command

Run this to print render.php files for all unaudited blocks and grep for suspicious literal strings (not using `$attributes`):

```bash
for block in hero-interior about-block autoridades mesas-tecnicas documentos-lista \
             contacto-info contacto-mapa-form infopub-tabs infopub-tab-panel \
             infopub-lista-categoria infopub-card-link; do
  echo ""
  echo "===== $block ====="
  grep -n "'>.*<\|\">[A-Z]" \
    wp-theme/mides-child/blocks/$block/render.php 2>/dev/null | \
    grep -v "\$attributes\|\$theme\|\$url\|\$image\|\$icon\|\$arrow\|esc_"
done
```

Lines that appear in the output are candidates for hardcoded content.
