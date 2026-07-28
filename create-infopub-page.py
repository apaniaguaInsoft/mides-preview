#!/usr/bin/env python3
"""Crea la página de Información Pública en WordPress con bloques Gutenberg."""

import json
import urllib.request
import base64

WP_URL  = "http://localhost:8080/wp-json/wp/v2"
USER    = "admin"
PASS    = "JSbzSR8f4FYfbpKUlNUWqSBJ"

AUTH = base64.b64encode(f"{USER}:{PASS}".encode()).decode()
HEADERS = {
    "Content-Type": "application/json",
    "Authorization": f"Basic {AUTH}",
}

# ── Helpers ──────────────────────────────────────────────────────────────────

def block(name, attrs=None, inner=""):
    a = (" " + json.dumps(attrs, ensure_ascii=False)) if attrs else ""
    if inner:
        return f"<!-- wp:{name}{a} -->\n{inner}\n<!-- /wp:{name} -->"
    return f"<!-- wp:{name}{a} /-->"

def para(text, classname=None):
    attrs = {"className": classname} if classname else None
    cls   = f' class="{classname}"' if classname else ""
    return block("paragraph", attrs, f'<p{cls}>{text}</p>')

def card(tag, titulo, descripcion="", url="", texto_btn="Ver documento",
         icono="document", color="default", tipo_btn="external",
         modo_doc=False, doc_id=0):
    a = {
        "tag": tag, "titulo": titulo,
        "textoBoton": texto_btn, "tipoBoton": tipo_btn,
        "icono": icono, "colorIcono": color,
    }
    if descripcion: a["descripcion"] = descripcion
    if modo_doc:
        a["modoDocumento"] = True
        a["documentoId"] = doc_id
    else:
        a["urlManual"] = url
    return block("mides/infopub-card-link", a)

def lista_cat(categoria_id, tag, titulo, descripcion="", icono="globe", columnas=5):
    a = {
        "categoriaId": categoria_id,
        "tag": tag, "titulo": titulo, "icono": icono, "columnas": columnas,
    }
    if descripcion: a["descripcion"] = descripcion
    return block("mides/infopub-lista-categoria", a)

def grid(*col_contents, cols=2):
    """Grid usando wp:group con clase infopub-grid — evita errores de validación de wp:columns."""
    class_name = f"infopub-grid infopub-grid--{cols}"
    inner = "\n".join(col_contents)
    attrs = {"className": class_name}
    return (
        f'<!-- wp:group {json.dumps(attrs, ensure_ascii=False)} -->\n'
        f'<div class="wp-block-group {class_name}">\n'
        f'{inner}\n'
        f'</div>\n'
        f'<!-- /wp:group -->'
    )

def vstack(*contents):
    """Grupo vertical sin clase especial (para apilar cards dentro de un grid cell)."""
    inner = "\n".join(contents)
    return (
        '<!-- wp:group -->\n'
        '<div class="wp-block-group">\n'
        f'{inner}\n'
        '</div>\n'
        '<!-- /wp:group -->'
    )

def panel(label, icono, *inner_blocks):
    inner = "\n".join(inner_blocks)
    return block("mides/infopub-tab-panel", {"label": label, "icono": icono}, inner)

# ── Contenido de cada tab ────────────────────────────────────────────────────

TAB_LEY = panel(
    "Ley de Acceso", "document",

    para(
        'La <strong>Ley de Acceso a la Información Pública (Decreto 57-2008)</strong> '
        'garantiza el derecho de toda persona a solicitar y tener acceso a la información '
        'pública, sin discriminación alguna. Disponible en español, 20 idiomas mayas y '
        'lenguaje de señas:',
        "infopub-panel__intro"
    ),

    grid(
        card(
            tag="Decreto 57-2008",
            titulo="Ley de Acceso a la Información Pública",
            descripcion="Versión en español — texto completo de la ley vigente.",
            url="https://www.mides.gob.gt/ley-de-acceso-a-la-informacion-publica/ley-acceso-informacion-idiomas-2/",
            texto_btn="Ver documento",
            icono="document",
        ),
        card(
            tag="Lengua de Señas",
            titulo="Ley de Acceso en Lengua de Señas",
            descripcion="Versión en Lengua de Señas Guatemalteca (LENSEGUA).",
            url="https://www.mides.gob.gt/ley-de-acceso-a-la-informacion-publica/ley-acceso-informacion-idiomas-3/",
            texto_btn="Ver documento",
            icono="coffee",
            color="teal",
        ),
    ),

    lista_cat(
        categoria_id=20,
        tag="20 Idiomas",
        titulo="Ley de Acceso en Idiomas Mayas",
        descripcion="Disponible en los principales idiomas mayas de Guatemala — Decreto 57-2008.",
        icono="globe",
        columnas=5,
    ),
)

TAB_OFICIO = panel(
    "Información de Oficio", "briefcase",

    para(
        'Los <strong>Sujetos Obligados</strong> deben mantener actualizada y disponible la '
        'información pública de oficio, conforme al <strong>Artículo 10 del Decreto 57-2008</strong>. '
        'Consulta la información por entidad:',
        "infopub-panel__intro"
    ),

    grid(
        card(
            tag="MIDES",
            titulo="UDAF",
            url="https://www.mides.gob.gt/transparencia-2/mides/",
            texto_btn="Consultar información",
            icono="home",
            tipo_btn="external",
        ),
        card(
            tag="FPS",
            titulo="Fondo de Protección Social",
            url="https://www.mides.gob.gt/transparencia-2/fondo-de-proteccion-social/",
            texto_btn="Consultar información",
            icono="users",
            color="amber",
            tipo_btn="external",
        ),
        card(
            tag="FODES",
            titulo="FODES",
            url="https://www.mides.gob.gt/fodes",
            texto_btn="Consultar información",
            icono="dollar",
            color="teal",
            tipo_btn="external",
        ),
        cols=3,
    ),
)

TAB_SOLICITUD = panel(
    "Solicitud", "chat",

    para(
        'Toda persona tiene derecho a solicitar información pública sin necesidad de justificar '
        'su solicitud. Puedes hacerlo <strong>en línea</strong> o contactando directamente a las '
        'Unidades de Acceso a la Información Pública (UIP):',
        "infopub-panel__intro"
    ),

    grid(
        card(
            tag="Formulario en Línea",
            titulo="Solicitud de Información Pública en Línea",
            descripcion=(
                "Ingresa tu solicitud directamente a través del portal oficial del MIDES. "
                "Recibirás respuesta en un plazo no mayor a 10 días hábiles, sin costo y "
                "sin necesidad de justificar la solicitud."
            ),
            url="https://informacionpublica.mides.gob.gt/Vista/InfoPublica/FrmSolicitudInformacionPublica.aspx",
            texto_btn="Ingresar solicitud en línea",
            icono="phone",
            tipo_btn="external",
        ),
        vstack(
            card(
                tag="UIP — MIDES",
                titulo="uipmides@mides.gob.gt",
                descripcion="Unidad de Acceso a la Información Pública",
                url="mailto:uipmides@mides.gob.gt",
                texto_btn="Enviar correo",
                icono="mail",
                tipo_btn="none",
            ),
            card(
                tag="UIP — FODES",
                titulo="uaipfodes@mides.gob.gt",
                descripcion="Unidad de Acceso a la Información Pública FODES",
                url="mailto:uaipfodes@mides.gob.gt",
                texto_btn="Enviar correo",
                icono="mail",
                color="amber",
                tipo_btn="none",
            ),
        )
    ),
)

TAB_TRANS = panel(
    "Transparencia", "search",

    para(
        'Documentos de <strong>transparencia presupuestaria</strong> del MIDES en cumplimiento '
        'de los Decretos 101-97 y 13-2013 de la Ley Orgánica del Presupuesto:',
        "infopub-panel__intro"
    ),

    lista_cat(
        categoria_id=25,
        tag="Decreto No. 13-2013",
        titulo="Reformas a la Ley Orgánica del Presupuesto — MIDES",
        descripcion="Decreto 101-97 · Ley Orgánica del Presupuesto",
        icono="document",
        columnas=2,
    ),

    lista_cat(
        categoria_id=26,
        tag="FOPROSO",
        titulo="Fondo de Protección Social",
        descripcion="Decreto 101-97 · Ley Orgánica del Presupuesto",
        icono="dollar",
        columnas=2,
    ),

    lista_cat(
        categoria_id=17,
        tag="Rendición de Cuentas",
        titulo="Memorias de Labores",
        descripcion="Informe anual de actividades, logros y resultados institucionales del MIDES.",
        icono="book",
        columnas=5,
    ),

    lista_cat(
        categoria_id=23,
        tag="Art. 17 Ter · Decreto No. 13-2013",
        titulo="Informes Cuatrimestrales",
        descripcion=(
            "Informes de seguimiento y ejecución presupuestaria presentados ante las "
            "Comisiones del Congreso, en cumplimiento del Art. 17 Ter de la Ley "
            "Orgánica del Presupuesto."
        ),
        icono="calendar",
        columnas=3,
    ),

    # Gobierno Abierto
    lista_cat(
        categoria_id=24,
        tag="Alianza para el Gobierno Abierto (AGA)",
        titulo="Gobierno Abierto",
        descripcion=(
            "Filosofía de transformación de la gestión pública que promueve la transparencia, "
            "la participación ciudadana y la colaboración. Guatemala forma parte de la AGA, "
            "organismo integrado por 70 países."
        ),
        icono="globe",
        columnas=5,
    ),

    grid(
        card(
            tag="2016 – 2018 · Plan III",
            titulo="Plan de Acción Nacional de Gobierno Abierto",
            descripcion=(
                "Primer plan en cumplir con la totalidad de los estándares de la AGA, "
                "elaborado bajo la metodología SMART. Incluye 22 compromisos en 5 ejes "
                "de trabajo."
            ),
            url="#",
            texto_btn="Ver Información",
            icono="document",
            tipo_btn="external",
        ),
        card(
            tag="2018 – 2020 · Plan IV",
            titulo="Plan de Acción Nacional de Gobierno Abierto",
            descripcion=(
                "Mecanismo propicio para la prevención de la corrupción, mediante un espacio "
                "de diálogo entre instituciones públicas y sociedad civil. Construido con "
                "24 compromisos basados en 12 ejes de trabajo."
            ),
            url="#",
            texto_btn="Ver Información",
            icono="document",
            color="amber",
            tipo_btn="external",
        ),
    ),
)

# ── Ensamblar la página completa ─────────────────────────────────────────────

TABS = block(
    "mides/infopub-tabs",
    {"align": "full"},
    "\n".join([TAB_LEY, TAB_OFICIO, TAB_SOLICITUD, TAB_TRANS])
)

HERO = block(
    "mides/hero-interior",
    {
        "align": "full",
        "titulo": "Información Pública",
        "subtitulo": "Consulta los documentos y recursos de acceso público del MIDES",
    }
)

PAGE_CONTENT = "\n\n".join([HERO, TABS])

# ── Crear la página en WordPress ─────────────────────────────────────────────

PAGE_ID = 195  # Página "Información Pública" existente

payload = json.dumps({
    "title":   "Información Pública",
    "slug":    "informacion-publica",
    "status":  "publish",
    "content": PAGE_CONTENT,
}, ensure_ascii=False).encode("utf-8")

req = urllib.request.Request(
    f"{WP_URL}/pages/{PAGE_ID}",
    data=payload,
    headers=HEADERS,
    method="POST",
)

try:
    with urllib.request.urlopen(req) as resp:
        data = json.loads(resp.read())
        print(f"✓ Página actualizada — ID: {data['id']}  URL: {data['link']}")
except urllib.error.HTTPError as e:
    body = e.read().decode()
    print(f"Error {e.code}: {body[:500]}")
