---
name: responsive-audit
description: >
  Audita bloques y componentes Gutenberg del tema MIDES WordPress para verificar
  que sean responsive en desktop, tablet y móvil. Usa esta skill cuando el usuario
  diga "revisa responsividad", "no se ve bien en móvil", "es responsive?",
  "audita los bloques", "revisar breakpoints", "se rompe en móvil", o cualquier
  variante. También activar proactivamente después de crear un bloque nuevo o de
  editar CSS de un bloque existente, para evitar regresiones de responsividad.
  Si el usuario menciona que algo se ve mal en tablet o celular, esta skill es la
  primera que debes usar.
---

# Responsive Audit — Bloques MIDES

## Contexto del proyecto

- **Tema hijo**: `wp-theme/mides-child/`
- **CSS global**: `wp-theme/mides-child/mides.css`
- **Bloques**: `wp-theme/mides-child/blocks/<nombre>/`
  - `render.php` — HTML del bloque en frontend
  - `block.js` — Editor Gutenberg
  - `block.json` — Atributos y configuración
- **Docker**: Los cambios locales NO se reflejan automáticamente. Siempre ejecutar
  `docker cp` al terminar cualquier corrección.

## Breakpoints del proyecto

| Nombre   | Valor     | Uso principal                        |
|----------|-----------|--------------------------------------|
| Desktop  | > 900px   | Layout base (flujo normal del CSS)   |
| Tablet   | ≤ 900px   | Grid → 2 columnas, ajustes de espacio|
| Móvil    | ≤ 560px   | Grid → 1 columna, texto más pequeño  |
| Móvil XS | ≤ 480px   | Ajustes finos para pantallas muy pequeñas |

Siempre verificar contra estos breakpoints. Si un bloque usa otros valores (700px, 640px, etc.) está bien, pero los principales son 900px y 560px.

---

## Proceso de auditoría

### Paso 1 — Identificar qué auditar

Si el usuario menciona un bloque específico, auditar solo ese. Si pide una auditoría general, listar todos los directorios en `blocks/` y auditarlos uno por uno.

```
ls wp-theme/mides-child/blocks/
```

### Paso 2 — Por cada bloque, leer estos archivos eficientemente

Para evitar leer archivos innecesarios, usar este orden:

1. `grep` las clases CSS del bloque en `mides.css` — esto da contexto de qué estilos existen y sus breakpoints sin leer el archivo completo
2. Leer solo las líneas relevantes de `mides.css` (usar `offset` y `limit`)
3. Leer `render.php` para confirmar la estructura HTML
4. Solo leer `block.js` si el bloque tiene lógica de layout en el editor

**Para auditorías generales (todos los bloques)**: auditar de a 3-4 bloques por vez, no todos a la vez. Empezar por los que tienen más probabilidad de problemas (grids multi-columna, bloques con tipografía grande).

### Paso 3 — Aplicar el checklist de responsividad

Para cada bloque, verificar los siguientes puntos:

#### A. Layouts fijos que no colapsan y zona muerta entre breakpoints
- Grids con columnas fijas (`repeat(N, 1fr)` con N > 1) sin `@media` que los reduzcan a 1 columna
- Flex rows sin `flex-wrap: wrap` ni breakpoint que cambie a columna
- Anchos fijos (`width: 400px`) sin `max-width: 100%` ni breakpoint que los ajusten
- **Zona muerta entre 560px y 900px**: breakpoints que saltan de 900px directamente a 480px dejan pantallas de 500–560px (Android comunes como Moto G4, Galaxy A) con 2 columnas muy angostas (~160px). Siempre verificar qué pasa en ese rango intermedio. El estándar del proyecto es colapsar a 1 columna en **560px**, no en 480px.

#### B. Touch targets demasiado pequeños
- Botones, links y elementos interactivos menores de 44×44px en móvil
- Verificar `width`, `height`, `padding` en los estilos del elemento

#### C. Texto que no escala, desborda o no hace word-break
- `font-size` fijo en `px` en pantallas grandes que no se reduce en móvil
- Títulos (`h1`, `h2`, `h3`) sin ajuste de tamaño en breakpoints de móvil
- **`clamp()` con mínimo demasiado alto**: `clamp(2.6rem, 6vw, 4rem)` en móvil usa siempre `2.6rem` porque `6vw` es menor que el mínimo — el texto sigue siendo grande aunque el viewport sea pequeño. El mínimo del clamp debe ser razonable para pantallas de 360px.
- **`letter-spacing` fijo en títulos `uppercase`**: valores como `letter-spacing: 4px` en texto todo mayúsculas multiplican el espacio por cada carácter. En palabras largas como "INSTITUCIONAL" (13 chars × 4px = 52px extra) esto puede causar overflow en móvil. Reducir a `1px`–`2px` en breakpoints de ≤560px.
- Ejemplo real — hero interior (`.contacto-hero__title`): tenía `font-size: clamp(2.6rem, 6vw, 4rem)` + `letter-spacing: 4px` + `text-transform: uppercase`. El título se cortaba en pantallas de 375px. Fix: agregar en `@media (max-width: 600px) { font-size: clamp(1.6rem, 8vw, 2.4rem); letter-spacing: 1.5px; }`
- **`text-transform: uppercase` sin `overflow-wrap`**: texto en mayúsculas tiende a no hacer word-break automáticamente. Siempre verificar que los selectores con `text-transform: uppercase` tengan `overflow-wrap: break-word` para evitar desbordamiento en nombres largos (especialmente en grids angostos de móvil). Ejemplo: `.equipo-card__nombre { text-transform: uppercase; }` sin `overflow-wrap` hace que nombres como "ROSA MARINA GUTIÉRREZ PÉREZ" desborden el card en pantallas de 360px.

#### D. Imágenes y decoraciones desbordadas
- `<img>` sin `max-width: 100%` o `width: 100%`
- Fondos con tamaño fijo que no se adaptan
- **Imágenes decorativas con ancho fijo**: elementos como líneas SVG decorativas con `width: 260px` pueden desbordar en pantallas de 320px. Verificar que tengan `max-width: 100%` o reducir su ancho en breakpoints móvil.

#### E. Overflow horizontal
- Contenedores sin `overflow-x: hidden` o con contenido que puede salirse
- Tablas sin `overflow-x: auto` en un wrapper

#### F. Padding/margin que rompen el layout en móvil
- Padding laterales excesivos que reducen demasiado el espacio útil en pantallas pequeñas
- Márgenes negativos sin control en móvil

---

## Formato del reporte

Siempre usar este formato para el reporte. Adaptar el contenido pero no el esquema:

```
## Responsive Audit — [Nombre del bloque o "Todos los bloques"]

### [nombre-bloque]
**Estado general**: 🔴 Crítico / 🟡 Advertencias / 🟢 OK

| # | Problema | Severidad | Archivo | Referencia |
|---|----------|-----------|---------|-----------|
| 1 | Grid de 3 columnas sin breakpoint móvil | 🔴 Crítico | mides.css | `.clase__grid { grid-template-columns: repeat(3,1fr) }` |
| 2 | Botón de 32px de alto en móvil | 🟡 Advertencia | render.php | `.bloque__btn { height: 32px }` |

**Sugerencia de fix:**
```css
@media (max-width: 900px) {
  .clase__grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 560px) {
  .clase__grid { grid-template-columns: 1fr; }
}
```

---
### Resumen general
| Bloque | Estado | Críticos | Advertencias |
|--------|--------|----------|-------------|
| hero-carousel | 🟢 OK | 0 | 0 |
| autoridades   | 🔴 Crítico | 2 | 1 |
```

---

## Si el usuario pide que corrijas los problemas

1. Aplica los cambios en `mides.css` (o en el archivo del bloque si corresponde)
2. Ejecuta inmediatamente:
   ```bash
   docker cp wp-theme/mides-child/mides.css wordpress_app:/var/www/html/wp-content/themes/mides-child/mides.css
   ```
   O si editaste archivos de un bloque específico:
   ```bash
   docker cp wp-theme/mides-child/blocks/<bloque>/. wordpress_app:/var/www/html/wp-content/themes/mides-child/blocks/<bloque>/
   ```
3. Reporta qué cambios hiciste y en qué líneas

## Severidad

- **🔴 Crítico**: El bloque claramente se rompe o desborda en esa pantalla (layout overflow, contenido ilegible, elementos solapados)
- **🟡 Advertencia**: Visualmente degradado pero funcional (texto grande, espacios ajustados, touch targets pequeños)
- **🟢 OK**: El bloque es completamente responsive en todos los breakpoints

No inventar problemas. Si un bloque usa `max-width: 100%` y `flex-wrap: wrap`, está bien aunque no tenga breakpoints explícitos — el layout ya es fluido.
