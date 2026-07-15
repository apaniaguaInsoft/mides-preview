# WordPress MCP — Guía de uso

Conector entre Claude Code y el WordPress en Docker via `@automattic/mcp-wordpress-remote`.

---

## Configuración

El archivo `.mcp.json` en la raíz del proyecto ya tiene todo configurado:

```json
{
  "mcpServers": {
    "wordpress": {
      "command": "npx",
      "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
      "env": {
        "WP_API_URL": "http://localhost:8080/wp-json/mcp/mcp-adapter-default-server",
        "WP_API_USERNAME": "admin",
        "WP_API_PASSWORD": "JSbzSR8f4FYfbpKUlNUWqSBJ"
      }
    }
  }
}
```

> **Importante:** La config siempre va en `.mcp.json`, nunca en `settings.json` — ese archivo no acepta `mcpServers`.

### Requisitos en el WordPress

- Plugin **MCP Adapter** activo (genera el endpoint `/wp-json/mcp/mcp-adapter-default-server`)
- `WP_API_PASSWORD` es una **Application Password** (WP Admin → Usuarios → Perfil → Application Passwords)

---

## Herramientas MCP disponibles

Las herramientas están diferidas — hay que buscar su schema antes de usarlas:

```
ToolSearch("select:mcp__wordpress__mcp-adapter-discover-abilities")
ToolSearch("select:mcp__wordpress__mcp-adapter-execute-ability")
ToolSearch("select:mcp__wordpress__mcp-adapter-get-ability-info")
```

| Herramienta | Para qué sirve |
|---|---|
| `discover-abilities` | Lista todas las acciones disponibles en el WordPress |
| `get-ability-info` | Detalle de parámetros de una acción específica |
| `execute-ability` | Ejecuta la acción (crear, leer, actualizar, eliminar) |

### Flujo de uso típico

```
1. ToolSearch para cargar el schema de discover-abilities
2. discover-abilities → ver qué acciones existen
3. ToolSearch + get-ability-info → ver parámetros de la acción que necesitas
4. ToolSearch + execute-ability → ejecutar la acción
```

---

## Alternativa — REST API con curl (sin MCP)

Útil cuando el MCP no está disponible o para operaciones puntuales.

```bash
# Credenciales base
CREDS="admin:JSbzSR8f4FYfbpKUlNUWqSBJ"
BASE="http://localhost:8080/wp-json/wp/v2"

# Listar páginas
curl -s -u "$CREDS" "$BASE/pages?per_page=20" | python3 -m json.tool

# Listar posts
curl -s -u "$CREDS" "$BASE/posts?per_page=20" | python3 -m json.tool

# Actualizar contenido de página (ID 25 = página de inicio)
curl -s -u "$CREDS" \
  -X POST "$BASE/pages/25" \
  -H "Content-Type: application/json" \
  -d '{"content":"<!-- wp:paragraph --><p>Hola</p><!-- /wp:paragraph -->"}'

# Insertar bloque Gutenberg en página
curl -s -u "$CREDS" \
  -X POST "$BASE/pages/25" \
  -H "Content-Type: application/json" \
  -d '{"content":"<!-- wp:mides/hero-carousel {\"slides\":[{\"pre\":\"ACCIONES\",\"title\":\"QUE CAMBIAN VIDAS\"}]} /-->"}'

# Listar menús
curl -s -u "$CREDS" "http://localhost:8080/wp-json/wp/v2/menus" | python3 -m json.tool

# Crear ítem de menú
curl -s -u "$CREDS" \
  -X POST "http://localhost:8080/wp-json/wp/v2/menu-items" \
  -H "Content-Type: application/json" \
  -d '{"title":"Inicio","url":"/","menus":3,"menu_order":1}'
```

---

## Docker — Comandos de mantenimiento

```bash
# Copiar todo el tema al contenedor
docker cp wp-theme/mides-child/. wordpress_app:/var/www/html/wp-content/themes/mides-child/

# Copiar solo el CSS
docker cp styles.css wordpress_app:/var/www/html/wp-content/themes/mides-child/mides.css

# Ejecutar PHP directamente en el contenedor (para operaciones sin API)
docker exec wordpress_app php -r "
  define('ABSPATH', '/var/www/html/');
  require '/var/www/html/wp-load.php';
  // código WordPress aquí
"

# Cambiar contraseña de admin
docker exec wordpress_app php -r "
  define('ABSPATH', '/var/www/html/');
  require '/var/www/html/wp-load.php';
  wp_set_password('nueva-contraseña', 1);
  echo 'OK';
"

# Ver logs de errores PHP/Apache
docker exec wordpress_app tail -100 /var/log/apache2/error.log

# Limpiar caché de opciones de WordPress
docker exec wordpress_app php -r "
  define('ABSPATH', '/var/www/html/');
  require '/var/www/html/wp-load.php';
  wp_cache_flush();
  echo 'Cache limpiado';
"
```

---

## Datos del entorno

| Dato | Valor |
|---|---|
| URL WordPress | `http://localhost:8080` |
| Contenedor app | `wordpress_app` |
| Contenedor DB | `wordpress_db` |
| Usuario admin | `admin` |
| Application Password | `JSbzSR8f4FYfbpKUlNUWqSBJ` |
| Página de inicio | ID `25` |
| Ruta del tema | `/var/www/html/wp-content/themes/mides-child/` |
