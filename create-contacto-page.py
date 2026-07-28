#!/usr/bin/env python3
"""Migra la página de Contacto a bloques Gutenberg y quita el template PHP."""

import json
import urllib.request
import base64

WP_URL = "http://localhost:8080/wp-json/wp/v2"
USER   = "admin"
PASS   = "EUFHewrZKLNElFvP1ZXvjPKO"

AUTH    = base64.b64encode(f"{USER}:{PASS}".encode()).decode()
HEADERS = {
    "Content-Type": "application/json",
    "Authorization": f"Basic {AUTH}",
}

PAGE_ID = 193

def block(name, attrs=None, inner=""):
    a = (" " + json.dumps(attrs, ensure_ascii=False)) if attrs else ""
    if inner:
        return f"<!-- wp:{name}{a} -->\n{inner}\n<!-- /wp:{name} -->"
    return f"<!-- wp:{name}{a} /-->"


HERO = block(
    "mides/hero-interior",
    {
        "align": "full",
        "titulo": "CONTÁCTANOS",
        "subtitulo": "Estamos aquí para servirte. Tu bienestar es nuestra misión.",
    }
)

INFO = block(
    "mides/contacto-info",
    {
        "align": "full",
        "direccion1": "5a. Avenida 8-78 Zona 9",
        "direccion2": "Guatemala, Guatemala",
        "edificio":   "Edificio Plaza Lauderdale",
        "horario":    "Lunes a Viernes, 8:00 — 16:00 hrs",
        "telefono1":  "+502 2300-5400",
        "telefono2":  "+502 2302-6900",
        "telefono3":  "+502 2302-6800",
        "correo":     "info@mides.gob.gt",
    }
)

MAPA_FORM = block(
    "mides/contacto-mapa-form",
    {
        "align":           "full",
        "mapaUrl":         "https://maps.google.com/maps?q=Ministerio+de+Desarrollo+Social+MIDES+5a+Avenida+8-78+Zona+9+Guatemala&output=embed&z=16",
        "mapaPie":         "5a. Av. 8-78 Zona 9, Guatemala — Edificio Plaza Lauderdale",
        "formId":          60,
        "formTitulo":      "Envíanos un Mensaje",
        "formDescripcion": "Tu dirección de correo electrónico no será publicada. Los campos requeridos están marcados con *",
    }
)

PAGE_CONTENT = "\n\n".join([HERO, INFO, MAPA_FORM])

payload = json.dumps({
    "title":    "Contacto",
    "slug":     "contacto",
    "status":   "publish",
    "template": "",          # quitar el template PHP
    "content":  PAGE_CONTENT,
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
        print(f"  Template: {repr(data.get('template',''))}")
        print(f"  Content length: {len(data['content']['rendered'])}")
except urllib.error.HTTPError as e:
    body = e.read().decode()
    print(f"Error {e.code}: {body[:800]}")
