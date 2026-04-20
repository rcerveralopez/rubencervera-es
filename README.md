# rubencervera.es — Guía de implementación

Esta guía traslada los HTML maquetados al stack real: WordPress + SEO técnico + workflow de blog automatizado con n8n.

---

## 1 · Archivos entregados

```
rubencervera-web/
├── styles.css                   Estilos globales compartidos
├── index.html                   Home
├── soporte.html                 Categoría 01 · Soporte y reparación
├── desarrollo-web.html          Categoría 02 · Desarrollo web
├── infraestructura-ia.html      Categoría 03 · Infraestructura + IA
├── contacto.html                Formulario cualificado
├── blog.html                    Hub del blog
├── blog-post-template.html      Plantilla para posts auto-generados
└── README.md                    Este documento
```

---

## 2 · Mapeo a WordPress

### 2.1 · URLs (permalinks)

En `Ajustes > Enlaces permanentes`:

- Estructura: **Nombre de la entrada** → `/%postname%/`
- Base de categoría: **blog/categoria** (en lugar de `category`)

URLs finales:

| Página | Slug | URL final |
|---|---|---|
| Home | — | `/` |
| Soporte | `soporte` | `/soporte/` |
| Desarrollo web | `desarrollo-web` | `/desarrollo-web/` |
| Infraestructura + IA | `infraestructura-ia` | `/infraestructura-ia/` |
| Contacto | `contacto` | `/contacto/` |
| Blog | `blog` | `/blog/` |
| Posts | dinámico | `/blog/nombre-post/` ⚠ |

⚠ Para que los posts vivan bajo `/blog/`, instalar **WP Permastructure** o usar Custom Post Type para blog. Alternativa más simple: mover el slug del blog a `/notas/` o mantener posts en raíz (`/slug/`) — pero `/blog/slug/` es mejor para la estructura semántica.

### 2.2 · Categorías de blog

Crear 4 categorías con estos slugs exactos (los HTML hacen referencia a ellos):

- `soporte-tecnico` → "Soporte"
- `desarrollo-web` → "Web"
- `automatizacion-ia` → "Automatización + IA"
- `domotica` → "Domótica"

### 2.3 · Estrategia de tema

**Opción A — Tema base custom (recomendado):**
Crear un tema hijo minimalista que cargue `styles.css` y tenga los templates:

- `front-page.php` → home
- `page-soporte.php` → página Soporte
- `page-desarrollo-web.php` → página Web
- `page-infraestructura-ia.php` → página Infra
- `page-contacto.php` → contacto
- `home.php` o `page-blog.php` → hub blog
- `single.php` → posts individuales (basado en `blog-post-template.html`)

**Opción B — Builder (Elementor/Bricks) + CSS adicional:**
Pegar `styles.css` en `Apariencia > Personalizar > CSS adicional`. Recrear las páginas en el builder respetando clases. **No recomendado** — el builder añade divs y scripts innecesarios que tiran los Core Web Vitals.

**Opción C — Páginas con HTML puro (rápido):**
Plugin `Raw HTML` o bloque "HTML personalizado" de Gutenberg. Pegar cada HTML en su página. Funciona pero complica el blog dinámico.

**→ Mi recomendación:** Opción A. El trabajo inicial se amortiza en velocidad, SEO y mantenimiento.

### 2.4 · Plugins SEO mínimos

- **Rank Math** (gratis, mejor que Yoast gratis) → meta tags, sitemap, schema.
- **WP Rocket** o **LiteSpeed Cache** → cache, minificación, lazy-load.
- **Imagify** o **ShortPixel** → compresión automática de imágenes a WebP.
- **Contact Form 7** o **Fluent Forms** → formulario `/contacto/` con integración al email y a n8n.
- **Wordfence** o **Solid Security** → seguridad.

⚠ Desactivar schema nativo del plugin SEO si mantienes el JSON-LD de las plantillas — evitar duplicado.

### 2.5 · Optimización técnica crítica

- **Hosting con PHP 8.2+** (Raiola, SiteGround, Cloudways).
- **Cloudflare delante** → CDN + DNS + protección DDoS gratis.
- **Fuentes locales** → descargar JetBrains Mono e IBM Plex Sans y servirlas desde `/fonts/` con `preload`. Elimina dependencia de Google Fonts (mejor Core Web Vitals + GDPR).
- **WebP + AVIF** para todas las imágenes.
- **HTTP/3** si el hosting lo soporta.

---

## 3 · Estrategia SEO

### 3.1 · Arquitectura de keywords

Cada página ataca su cluster:

**Home**
- `soluciones técnicas Maresme`, `técnico informático Barcelona`

**Soporte**
- `reparación ordenadores Maresme`, `técnico informático Arenys de Mar`, `soporte remoto España`, `instalación Windows Barcelona`, `mantenimiento informático pymes`

**Web**
- `desarrollo web WordPress Barcelona`, `diseño web pymes Maresme`, `tienda online WooCommerce`, `auditoría web`, `mantenimiento WordPress`

**Infraestructura + IA**
- `automatización n8n`, `self-hosting Docker`, `HomeAssistant Zigbee`, `consultoría IA pymes`, `VPS gestionado España`

### 3.2 · Schema.org implementado

Cada página tiene su JSON-LD listo:

- `ProfessionalService` + `LocalBusiness` en home y contacto
- `Service` + `OfferCatalog` en cada página de servicio
- `FAQPage` donde hay bloque FAQ → genera **rich snippets** en Google
- `BreadcrumbList` en todas las subpáginas
- `BlogPosting` en posts individuales
- `Blog` en el hub

### 3.3 · Internal linking

Reglas del senior:

- Home → enlaza a las 3 categorías (hecho en las tarjetas).
- Cada página de servicio → enlaza al blog relacionado en la sección de artículos (añadir cuando haya posts).
- Cada post → enlaza al servicio relacionado vía CTA (hecho en template).
- Blog hub → filtros por categoría (hecho).
- Footer global → enlaza a todas las páginas principales (hecho).

### 3.4 · Sitemap + robots.txt

**sitemap.xml** lo genera Rank Math automáticamente. Comprobar que incluye:
- Páginas (`/`, `/soporte/`, `/desarrollo-web/`, `/infraestructura-ia/`, `/contacto/`, `/blog/`)
- Posts del blog
- Categorías del blog

**robots.txt:**

```txt
User-agent: *
Allow: /
Disallow: /wp-admin/
Disallow: /wp-includes/
Disallow: /?s=
Allow: /wp-admin/admin-ajax.php

Sitemap: https://rubencervera.es/sitemap_index.xml
```

### 3.5 · Google Search Console + Analytics

- Verificar dominio con Search Console → subir sitemap.
- Instalar Analytics (o **Plausible** si quieres privacy-first, es de pago pero europeo).
- Activar Google Tag Manager si vas a hacer tracking de conversiones del formulario.

---

## 4 · Workflow n8n del blog semanal

### 4.1 · Pipeline completo

```
[Cron semanal viernes 09:00]
        │
        ▼
[Selector de tema y categoría]
   · Rota entre categorías (4 categorías × 1 post/semana = 4 semanas/ciclo)
   · Lee keywords objetivo de una Google Sheet o DB
   · Prioriza keywords con volumen medio/bajo y baja competencia
        │
        ▼
[Investigación (opcional)]
   · SerpAPI o Dataforseo → analiza top 10 resultados para la keyword
   · Extrae H2/H3 comunes, detecta gaps de contenido
        │
        ▼
[Generación con LLM]
   · Prompt estructurado (ver 4.2)
   · Genera JSON con: title, slug, meta_description, lede, content_html, faq[]
        │
        ▼
[Imagen destacada]
   · DALL-E / Flux / Stable Diffusion con prompt derivado del título
   · O Unsplash API si prefieres fotos reales
        │
        ▼
[Rellenar template]
   · Reemplaza {{PLACEHOLDERS}} en blog-post-template.html
   · Inyecta FAQ_JSON_LD si aplica
        │
        ▼
[Publicar en WordPress]
   · REST API /wp-json/wp/v2/posts (Application Password)
   · Categoría correcta, tags, imagen destacada adjunta
        │
        ▼
[Notificación + log]
   · Email/Telegram con enlace al post
   · Registro en Sheet para no repetir keywords
```

### 4.2 · Prompt de generación (crítico para SEO)

```
Rol: Eres un redactor técnico especializado en {CATEGORÍA}.
Escribes en español de España, tono profesional y directo, sin jerga excesiva.
El artículo forma parte del blog de Rubén Cervera (rubencervera.es),
un técnico independiente que ofrece servicios de soporte, web y automatización.

Keyword principal: {KEYWORD}
Keywords secundarias: {KEYWORDS_SECONDARY}
Intención de búsqueda: {INTENT}  // informational | transactional | navigational

Genera un JSON con esta estructura EXACTA:

{
  "title": "Título SEO-optimizado (55-60 chars) que incluya la keyword",
  "slug": "slug-con-guiones-y-keyword",
  "meta_description": "Descripción 140-160 chars, incluye keyword, invita a clicar",
  "reading_time": "X min",
  "lede": "Párrafo entradilla 2-3 frases, engancha, menciona keyword",
  "content_html": "HTML con h2, h3, p, ul, code. 1200-1800 palabras.
                   Estructura: problema → contexto → solución paso a paso → conclusión.
                   Keyword principal en primer H2 y primer párrafo.
                   Keywords secundarias distribuidas naturalmente.",
  "faq": [
    {"q": "Pregunta 1", "a": "Respuesta 80-120 palabras"},
    {"q": "Pregunta 2", "a": "Respuesta 80-120 palabras"},
    {"q": "Pregunta 3", "a": "Respuesta 80-120 palabras"}
  ],
  "related_service": "soporte" | "web" | "infraestructura",
  "image_prompt": "Prompt en inglés para generar imagen destacada"
}

REGLAS SEO OBLIGATORIAS:
1. H1 = title (ya está en el template, no incluirlo en content_html).
2. Un solo H1 por página (el del template).
3. Jerarquía correcta: H2 > H3, sin saltos.
4. Keyword principal en: title, meta_description, primer H2, primer párrafo, URL slug.
5. Densidad de keyword 1-2% (ni más ni menos).
6. Longitud mínima 1200 palabras.
7. No inventes datos, estadísticas o URLs.
8. Incluye al menos un bloque de código si es tutorial técnico.
9. FAQ responde preguntas que la gente realmente busca (People Also Ask).
10. Cierra el artículo con un párrafo que enlace mental al servicio profesional.
```

### 4.3 · Mapeo de placeholders del template

El workflow debe sustituir estos valores en `blog-post-template.html`:

| Placeholder | Fuente |
|---|---|
| `{{POST_TITLE}}` | `title` del JSON |
| `{{POST_SLUG}}` | `slug` del JSON |
| `{{META_DESCRIPTION}}` | `meta_description` |
| `{{META_KEYWORDS}}` | keywords concatenadas |
| `{{POST_CATEGORY}}` | nombre legible ("Automatización + IA") |
| `{{POST_CATEGORY_SLUG}}` | slug categoría ("automatizacion-ia") |
| `{{PUBLISH_DATE_ISO}}` | `new Date().toISOString().split('T')[0]` |
| `{{PUBLISH_DATE_HUMAN}}` | formateado "18 abr 2026" |
| `{{READING_TIME}}` | `reading_time` |
| `{{LEDE}}` | `lede` |
| `{{CONTENT_HTML}}` | `content_html` |
| `{{FEATURED_IMAGE_URL}}` | URL de la imagen generada/subida |
| `{{RELATED_SERVICE_URL}}` | `/soporte/` según `related_service` |
| `{{RELATED_SERVICE_NAME}}` | "Soporte" / "Desarrollo Web" / "Infraestructura" |
| `{{FAQ_JSON_LD}}` | JSON-LD generado desde array `faq` |
| `{{RELATED_POSTS_HTML}}` | 3 últimos posts de la misma categoría (WP_Query en single.php) |

### 4.4 · Integración WP REST API

Endpoint: `POST https://rubencervera.es/wp-json/wp/v2/posts`

Headers:
```
Authorization: Basic {base64(usuario:application_password)}
Content-Type: application/json
```

Body:
```json
{
  "title": "{{POST_TITLE}}",
  "slug": "{{POST_SLUG}}",
  "content": "{{CONTENT_HTML}}",
  "excerpt": "{{LEDE}}",
  "status": "publish",
  "categories": [ID_DE_CATEGORIA],
  "tags": [IDs_DE_TAGS],
  "featured_media": ID_DE_IMAGEN,
  "meta": {
    "rank_math_title": "{{POST_TITLE}} | Rubén Cervera",
    "rank_math_description": "{{META_DESCRIPTION}}",
    "rank_math_focus_keyword": "{{KEYWORD}}"
  }
}
```

⚠ **Application Password**, no la del login. Crearla en `Usuarios > tu perfil > Application Passwords`.

### 4.5 · Calendario editorial sugerido

Rotación semanal de categorías (1 post cada viernes):

```
Semana 1 → Soporte
Semana 2 → Web
Semana 3 → Automatización + IA
Semana 4 → Domótica
Semana 5 → Soporte  (vuelve al inicio)
...
```

Con 52 semanas = 13 posts/categoría/año = 52 posts/año. Suficiente para hacer crecer el SEO a 12 meses vista.

---

## 5 · Checklist pre-lanzamiento

### Técnico
- [ ] SSL activo (Let's Encrypt / Cloudflare)
- [ ] WWW redirige a no-WWW (o viceversa, pero solo una versión indexable)
- [ ] HTTP redirige a HTTPS (301)
- [ ] Lighthouse Performance > 90 mobile
- [ ] Lighthouse Accessibility > 95
- [ ] robots.txt subido
- [ ] sitemap.xml accesible y enviado a GSC
- [ ] 404 personalizado
- [ ] Política de privacidad + Aviso legal + Cookies (obligatorio en España)

### SEO
- [ ] Meta titles y descriptions únicas en cada página
- [ ] Una sola H1 por página
- [ ] Imágenes con `alt` descriptivo
- [ ] Schema LocalBusiness validado en [Rich Results Test](https://search.google.com/test/rich-results)
- [ ] Google Search Console verificado + sitemap enviado
- [ ] Ficha Google Business Profile creada y vinculada
- [ ] Canonical correctos en todas las páginas

### Funcional
- [ ] Formulario `/contacto/` envía al email correcto
- [ ] Prueba del parámetro `?s=soporte/web/infraestructura` en contacto
- [ ] Todos los enlaces internos funcionan
- [ ] Responsive probado en móvil real (no solo DevTools)
- [ ] Workflow n8n del blog en pruebas 2-3 semanas antes de publicar real

---

## 6 · Estimación de tiempos

| Tarea | Tiempo |
|---|---|
| Setup hosting + WordPress + tema | 2-3h |
| Implementar páginas (opción A, tema hijo) | 6-8h |
| Configurar Rank Math + schema + sitemap | 2h |
| Formulario contacto + integración | 2h |
| Cloudflare + optimización Core Web Vitals | 2h |
| Workflow n8n del blog | 6-8h |
| Pruebas + 1 post de test manual | 2h |
| **Total** | **22-27h** |

Si vas a 3-4h/día, 1 semana de trabajo para dejarlo listo.

---

## 7 · Siguiente paso

Cuando tengas el dominio y hosting listos, el orden es:

1. Montar WordPress base + tema hijo vacío
2. Subir `styles.css` y probar que cargan las fuentes
3. Crear las 5 páginas estáticas (home, 3 servicios, contacto)
4. Configurar Rank Math + sitemap + Search Console
5. Crear blog hub + 1 post manual de prueba
6. Montar workflow n8n + probar 2-3 semanas en modo borrador
7. Activar publicación automática

Lo haces en este orden y no tienes sorpresas.
