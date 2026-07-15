---
name: nuevo-bloque-gutenberg
description: Crear un nuevo bloque Gutenberg para el tema MIDES WordPress. Usar cuando el usuario pida "crear un bloque", "nuevo bloque gutenberg", "agregar bloque al tema" o similar.
user-invocable: true
---

# Crear nuevo bloque Gutenberg — MIDES

Lee primero `/Users/apaniagua/Proyectos/mides-site/BLOQUES-GUTENBERG.md` para la guía completa. Resumen operativo:

## Estructura de archivos

Cada bloque vive en `wp-theme/mides-child/blocks/{nombre-bloque}/`:

```
blocks/
└── nombre-del-bloque/
    ├── block.json        ← Metadatos, atributos, editorScript, editorStyle
    ├── block.js          ← UI del editor (vanilla JS, sin JSX/webpack)
    ├── block.asset.php   ← Dependencias JS (CRÍTICO — sin esto falla en silencio)
    ├── editor.css        ← Estilos solo en el editor Gutenberg
    └── render.php        ← HTML del frontend (PHP)
```

El JS del frontend va en `wp-theme/mides-child/js/` y se encola desde `functions.php`.

## Checklist de creación

1. Preguntar al usuario: nombre del bloque, atributos necesarios, si tiene interactividad JS en frontend
2. Crear la carpeta `blocks/{nombre}/`
3. Crear `block.json` — nombre siempre con prefijo `mides/`, incluir `editorScript` y `editorStyle`
4. Crear `block.asset.php` — dependencias: `wp-blocks`, `wp-element`, `wp-block-editor`, `wp-components`
5. Crear `block.js` — con `useBlockProps` en `edit()` y `save: () => null`
6. Crear `editor.css` — preview en editor. Añadir `margin-bottom: 0 !important` a elementos `<p>` para contrarrestar el estilo de Astra (`.entry-content p { margin-bottom: 1.6em }`)
7. Crear `render.php` — con `esc_html()`, `esc_url()`, `esc_attr()` siempre
8. Si hay JS frontend: crear `wp-theme/mides-child/js/{nombre}.js`
9. Registrar en `functions.php`: `register_block_type()` + render callback que encola el JS frontend
10. Desplegar al Docker:

```bash
docker cp wp-theme/mides-child/. wordpress_app:/var/www/html/wp-content/themes/mides-child/
docker exec wordpress_app wp cache flush --allow-root
```

## Reglas importantes

- **NO** usar `viewScript` con rutas relativas `../../` en `block.json` — falla silenciosamente
- `useBlockProps` es **obligatorio** en `edit()` — sin él el bloque no es clickeable en el editor
- `save()` siempre retorna `null` (render dinámico via PHP)
- Para arrays de items, usar `.concat([nuevoItem])` — nunca mutar el array directamente
- El CSS del frontend viene de `styles.css` raíz (copiado como `mides.css`), no del `editor.css`
- Siempre sanitizar: `esc_html()` para texto, `esc_url()` para URLs, `esc_attr()` para atributos HTML
- Para imágenes: guardar tanto `imgId` como `imgUrl` — el ID para PHP, la URL para preview en editor

## Insertar bloque en una página

```bash
# Con wp-cli
docker exec wordpress_app wp post update PAGE_ID \
  --post_content='<!-- wp:mides/nombre-bloque {"attr":"valor"} /-->' \
  --allow-root
```
