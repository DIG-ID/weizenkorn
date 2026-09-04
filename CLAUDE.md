# CLAUDE.md — Weizenkorn

Projecto para a Stiftung Weizenkorn (Basel) — weizenkorn.ch. Arrancado a partir do starter theme dig.id (v1.13.0, ver `CHANGELOG.md`). Ver `figma-architecture-analysis.txt` para o mapeamento completo do design (sections, components, modules, templates WP, CPTs, ACF) — usar como roadmap de build e manter esta secção do CLAUDE.md alinhada à medida que os campos reais forem criados.

## Language
- Responder sempre em **português de Portugal** no chat.
- Comentários no código devem ser em **inglês (en-EN)**.

---

## Stack

- **WordPress** custom theme (`text-domain: weizenkorn`)
- **WooCommerce** — decisão pendente: catálogo de produtos sem checkout (ver `figma-architecture-analysis.txt`, secção 9.1) pode acabar por usar CPT `product` + CF7 em vez de WooCommerce; actualizar esta linha quando decidido com o cliente.
- **Tailwind CSS** + **Laravel Mix** (webpack)
- **PHP 8.0+**
- **SASS** (estrutura modular em `assets/sass/`)
- **JS** em `assets/js/`
- **Dist** compilado em `dist/`

### Dependências JS notáveis
- GSAP, Lenis (smooth scroll), Swiper, Isotope, Fancyapps UI, imagesloaded

---

## Estrutura de Ficheiros

```
weizenkorn/
├── assets/
│   ├── js/
│   └── sass/
│       ├── _vars.sass
│       ├── _base-styles.sass
│       ├── _components/
│       ├── _modules/
│       ├── _pages/
│       ├── _posts/
│       └── main.sass
├── dist/                   # compiled assets (não editar directamente)
├── inc/                    # PHP includes do tema
├── page-templates/         # Page templates WordPress
├── template-parts/
│   ├── pages/
│   │   └── home/
│   ├── footer-main.php
│   ├── header-main.php
│   └── design-system.php
├── functions.php
├── webpack.mix.js
├── tailwind.config.js
├── phpcs.xml.dist
└── style.css
```

---

## Comandos de Desenvolvimento

```bash
# Compilar assets (desenvolvimento)
npm run dev

# Compilar assets (produção)
npm run prod

# Lint PHP (PHPCS)
npm run php:lint

# Auto-fix PHP (PHPCBF)
npm run php:fix
```

---

## Coding Standards

Todo o código PHP **deve** seguir:
- [PHP Coding Standards (PHPCS)](https://github.com/PHPCSStandards/PHP_CodeSniffer/)
- [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards)

Regras activas (ver `phpcs.xml.dist`): `WordPress-Core`, `WordPress-Docs`, `WordPress-Extra`.

Antes de entregar código PHP, verificar com `npm run php:lint`. Usar `npm run php:fix` para correcções automáticas.

---

## Custom Post Types

| Slug    | Descrição                                                                          |
|---------|-------------------------------------------------------------------------------------|
| `news`  | Notícias — ainda por criar. Pode reaproveitar o CPT `blog` do starter (renomear labels/slug) ou criar de novo; afecta slugs/traduções WPML (ver `figma-architecture-analysis.txt`, secção 9.3). |
| `offene-stellen` | Vagas / Open Positions ("Aktuell offene Stellen"). Criado via ACF (post type key `offene-stellen`, público, não hierárquico). **Tem archive próprio** (ao contrário do previsto inicialmente) — permalink e archive slug ambos `ausbildung-arbeiten/offene-stellen-mit-iv`, simulando ser filho da página `ausbildung-arbeiten`. Template do archive: `archive-offene-stellen.php` (raiz do tema, secções em `template-parts/archives/offene-stellen/`). Sem contexto de post na archive, por isso os campos da página (hero, offer-links, categorias, contacto) vivem nas Theme Options com o prefixo `offene_stellen_archive_` (mesmo padrão do `products_archive_`). Template do single post: `single-offene-stellen.php`, secções em `template-parts/single/offene-stellen/`. Duas taxonomias próprias (registadas em código, `inc/theme-setup.php`, não via ACF): `offene_stellen_anstellungsart` (Arbeitsstellen / Ausbildungsplätze — o banner vermelho do card) e `offene_stellen_standort` (as próprias oficinas do cliente — Schreinerei, DasBreiteHotel, …); ambas `public => false` (sem archive próprio de taxonomia) e alimentam o filtro do archive (`template-parts/archives/offene-stellen/job-filters.php`, endpoint REST `weizenkorn/v1/jobs` em `inc/rest-job-filters.php`) via `tax_query`. |

> **Nota:** tabela ainda por preencher com os slugs finais e o CPT `event` do starter (só manter se o projecto vier a precisar de eventos distintos de `news`). Actualizar esta secção à medida que os CPTs forem criados.

---

## ACF Field Groups

> Ainda por criar — nenhum grupo ACF definido no projecto até à data. Ver `figma-architecture-analysis.txt` (secção 7) para a sugestão de estrutura por secção (`hero_home`, `intro_panel`, `usp_band`, `cards_section`, `quote_slider`, `cta_form`, `locations`, `map`, `contact_person`, `skills_lists`, CPT `job`). Documentar aqui cada grupo real (CPT/página → nome do grupo → campos) à medida que for criado no ACF.

---

## SEO / Schema

- Plugin: **Yoast SEO** (`wordpress-seo`)
- Schema customizado via **Yoast Schema API** — usar filtros `wpseo_schema_graph_pieces` e `wpseo_schema_*`
- Não criar schema manual com `wp_head` se o Yoast já o suportar.

| Contexto     | Schema                  | Campos principais                              |
|--------------|-------------------------|------------------------------------------------|
| CPT `news`   | `schema.org/Article`    | Coberto pelo Yoast por omissão                 |
| CPT `offene-stellen` (single) | `schema.org/JobPosting` | Custom (`inc/schema/class-weizenkorn-schema-jobposting.php`, desde 1.13.0) — title, description, datePosted, hiringOrganization, jobLocation (morada fixa da Stiftung), employmentType só quando inferível com confiança (Ausbildungsplätze → INTERN) |
| Páginas com o módulo `template-parts/modules/faq.php` | `schema.org/FAQPage` | Custom (`inc/schema/class-weizenkorn-schema-faq.php`, desde 1.13.0) — lista de prefixos ACF conhecidos no próprio ficheiro; actualizar ao adicionar uma página nova que use o módulo com um prefixo diferente |

> Candidato futuro: `LocalBusiness`/`Restaurant`/`Bakery` para as páginas Gastronomie (Rhyvage, DasBreiteHotel, Cantina E9, padaria, events-seminare) — bloqueado por falta de campos reais de morada/telefone/horário por local, não por falta de código.
>
> Actualizar esta tabela quando os CPTs/templates reais forem criados (ver secção "Custom Post Types" acima).

---

## Plugins

> Atenção: alguns plugins são **apenas locais** (debug, migração, deploy) e não estão em produção.

### Stack standard (presente em ~100% dos sites live — assumir ao desenvolver)
- `advanced-custom-fields-pro` + `acfml` — campos ACF (multilingual)
- WPML: `sitepress-multilingual-cms` + `wpml-string-translation` + `wp-seo-multilingual` + `wpml-mailchimp-for-wp` — multilíngue
- `wordpress-seo` (Yoast) + `acf-content-analysis-for-yoast-seo` — SEO/Schema
- `woocommerce` + `woocommerce-multilingual` — loja
- `contact-form-7` + `contact-form-7-multilingual` — formulários
- `mailchimp-for-wp` — newsletter
- `wp-rocket` — cache, minify, lazyload (**normalmente só activo em live**)
- `sg-security` — hardening (o `inc/security.php` do tema cobre apenas o que este não cobre)
- `safe-svg` — sanitização de uploads SVG
- `simple-maintenance-mode` — plugin interno dig.id ([repo](https://github.com/DIG-ID/simple-maintenance-mode)): modo de manutenção com toggle no admin bar, HTTP 503 + `Retry-After`, bypass para admins. O template é personalizável por tema via override em `themes/<tema>/simple-maintenance-mode/` — a lógica fica sempre no plugin, nunca no tema

Ferramentas dev/deploy (sem impacto no código do tema): `wp-migrate-db`, `wppusher`, `regenerate-thumbnails`.

### Regras
- Não usar plugins adicionais — preferir código no tema ou plugins já instalados.
- **Não duplicar funcionalidade do stack**: lazyload/minify/cache é do WP Rocket; hardening base é do SG Security; schema é via Yoast API; sanitização de SVG é do safe-svg.
- Código sempre compatível com WPML: strings traduzíveis, `wpml_object_id` para IDs hardcoded, campos ACF traduzíveis via acfml.
- Plugins fora do stack standard (ex.: `age-gate`, `woocommerce-gateway-stripe`, `flexible-coupons-pro`, `woo-variation-swatches`) são **específicos de cada projecto** — documentar no CLAUDE.md do projecto.

---

## Documentação de Referência

Ao desenvolver, seguir sempre a documentação oficial como fonte de autoridade para boas práticas, exemplos e dúvidas. Em caso de conflito entre abordagens, a documentação oficial prevalece. Para APIs recentes ou casos duvidosos, **consultar a documentação online em vez de assumir**.

- **Theme Handbook** — https://developer.wordpress.org/themes/ (estrutura, template hierarchy, boas práticas de temas)
- **WordPress Developer Resources** — https://developer.wordpress.org/ (code reference de funções/hooks/classes, APIs)
- **Coding Standards** — https://developer.wordpress.org/coding-standards/ (PHP, HTML, CSS, JS, acessibilidade)
- **ACF** — https://www.advancedcustomfields.com/resources/
- **WooCommerce** — https://developer.woocommerce.com/docs/
- **WPML** — https://wpml.org/documentation/ (hooks e compatibilidade multilíngue)
- **Yoast Schema API** — https://developer.yoast.com/features/schema/

### Front-end (atenção às versões instaladas — ver `package.json`)
- **Tailwind CSS v3** — https://v3.tailwindcss.com/docs (o starter usa `^3.4` com Laravel Mix; **não** usar docs/sintaxe da v4)
- **GSAP v3** — https://gsap.com/docs/v3/
- **Lenis** — https://github.com/darkroomengineering/lenis/blob/main/README.md
- **Swiper** — https://swiperjs.com/swiper-api (o starter usa `^12`; não usar docs de versões antigas)
- **Fancyapps UI (Fancybox v5)** — https://fancyapps.com/fancybox/
- **Laravel Mix** — https://laravel-mix.com/docs

> Regra: antes de usar exemplos da documentação front-end, confirmar a versão instalada no `package.json` — APIs de Tailwind/Swiper mudam entre major versions.

---

## PHPDoc — Documentação de Código (obrigatório)

Todos os ficheiros PHP **devem** começar com um file docblock com `@package`, `@subpackage` e `@since`:

```php
<?php
/**
 * Short description of what this file does.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.0.0
 */
```

### Taxonomia de `@subpackage`
| `@subpackage` | Usar em |
|---|---|
| `Core` | `functions.php`, `header.php`, `footer.php`, `index.php` |
| `Template` | Root templates (`single.php`, `page.php`, `archive.php`, `404.php`, `search.php`) e `page-templates/*` |
| `Section` | `template-parts/` — secções de página (hero, header-main, footer-main, etc.) |
| `Component` | `template-parts/` — partes pequenas e reutilizáveis (cards, botões, breadcrumbs) |
| `Module` | Funcionalidades front-end compostas (sliders, accordions, galerias) |
| `Functionality` | `inc/` — features do tema (security, performance, enqueue, admin, setup) |
| `Helpers` | `inc/helpers.php`, `inc/theme-template-tags.php` |

### Regras
- `@since` = versão do tema em que o ficheiro/função foi introduzido (sincronizado com o `CHANGELOG.md`).
- Todas as funções têm docblock com descrição, `@param` e `@return` (standard `WordPress-Docs` do PHPCS).
- Docblocks e comentários sempre em **inglês (en-EN)**.
- Ao criar ficheiros novos, nunca usar comentários de linha (`// ...`) como cabeçalho — sempre docblock completo.

---

## Semântica HTML5 & Boas Práticas (obrigatório)

- Usar sempre os elementos semânticos correctos: `<header>`, `<nav>`, `<main>` (um único por página), `<section>`, `<article>`, `<aside>`, `<footer>`, `<figure>`/`<figcaption>`, `<time datetime="">`.
- **Um único `<h1>` por página**; hierarquia de headings sem saltos (`h1` → `h2` → `h3`).
- `<section>` só quando tem heading próprio; caso contrário usar `<div>`.
- Acessibilidade: skip-link para `#main-content`, `aria-label` em `<nav>` e botões icon-only, `alt` sempre definido nas imagens (vazio se decorativa), estados de foco visíveis.
- Imagens: usar `wp_get_attachment_image()` (gera `srcset`/`sizes`); `loading="lazy"` por omissão, `loading="eager"` + `fetchpriority="high"` apenas no hero/LCP.
- Schema/structured data: **sempre via Yoast Schema API (JSON-LD)** — nunca microdata (`itemscope`/`itemtype`) inline no HTML.
- Escaping sempre no output: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`. Inputs com sanitização e nonces quando aplicável.
- Strings sempre traduzíveis (`__()`, `esc_html__()`, etc.) com text domain `weizenkorn`, e em **inglês** por omissão no starter.
- Estas regras aplicam-se a todo o código novo e a qualquer ficheiro tocado (boy-scout rule: ao editar, corrigir a semântica/documentação desse ficheiro).

---

## i18n / Traduções

- Todas as strings do tema são traduzíveis (`__()`, `esc_html__()`, etc.) com text domain `weizenkorn`, em **inglês** por omissão.
- **Gerar o `.pot` com WP-CLI** (disponível no shell do Local by WPEngine), idealmente antes do lançamento:
  ```bash
  wp i18n make-pot . languages/weizenkorn.pot --domain=weizenkorn
  ```
- Com WPML no stack, o `.pot` não é obrigatório (o String Translation faz scan do tema), mas é boa prática: o WPML importa-o e o tema funciona com `.po`/`.mo` nativos em sites sem WPML.
- Não usar Loco Translate ou plugins equivalentes para isto (regra: sem plugins extra).
- `wpml-config.xml` na raiz do tema (quando existir): define defaults de tradução para CPTs, taxonomias e custom fields (traduzir vs. copiar) — ajustar por projecto conforme os campos ACF.

---

## Versionamento & Changelog

O tema segue **Semantic Versioning** (`MAJOR.MINOR.PATCH`) e mantém um `CHANGELOG.md` no formato [Keep a Changelog](https://keepachangelog.com/).

> **Starter vs. projecto:** o versioning e o changelog deste repositório pertencem ao **starter theme**. Quando o starter é usado para arrancar um projecto novo (via `SETUP.md`), o projecto **recomeça em `1.0.0`** com changelog limpo — a primeira entrada regista a versão do starter de origem (ver passo 9 do `SETUP.md`). A partir daí, o versioning do projecto evolui de forma independente com estas mesmas regras.

### Quando incrementar
| Tipo de alteração | Exemplo | Incremento |
|---|---|---|
| Correcção de bug / ajuste pequeno | fix num template, correcção CSS | **PATCH** — `1.0.0` → `1.0.1` |
| Nova funcionalidade / implementação | novo template, novo CPT, nova secção | **MINOR** — `1.0.1` → `1.1.0` |
| Mudança estrutural / incompatível | redesign, remoção de funcionalidade | **MAJOR** — `1.1.0` → `2.0.0` |

### Regras (obrigatório em cada alteração de código)
1. **No fim de cada tarefa concluída**, sugerir sempre a actualização do `CHANGELOG.md` e do `Version:` no `style.css` para reflectir a alteração (propor o novo número segundo a tabela acima).
2. Adicionar a entrada no `CHANGELOG.md` numa secção `## [X.Y.Z] — YYYY-MM-DD`, sob a categoria adequada: `Added`, `Changed`, `Fixed`, `Removed`, `Deprecated` ou `Security`.
3. O número de versão em `style.css` e a última versão do `CHANGELOG.md` devem estar sempre sincronizados.
4. Entradas do changelog **sempre em inglês (en-EN)**, curtas e orientadas ao que mudou para quem usa o tema (não detalhes internos de implementação).
5. Sempre que a versão muda (changelog + `style.css` actualizados), sugerir também uma **mensagem de commit para o push no GitHub** — em inglês, curta, no formato `vX.Y.Z: summary of changes` — para que changelog, versão do tema e histórico git fiquem em sintonia.
6. Para publicar uma **release no GitHub** (com o zip do tema anexado automaticamente via `.github/workflows/release.yml`), criar e enviar a tag da versão: `git tag vX.Y.Z && git push origin vX.Y.Z`. Fazer isto pelo menos nas versões que a equipa vai usar para arrancar projectos novos.

---

## Convenções de Escrita de Código (idiomas)

> Padrões concretos de "como escrevemos componentes" — para o código novo sair já no estilo do tema. Complementa (não repete) as secções de PHPDoc, Semântica HTML5 e i18n acima.

### PHP — output & escaping
- Sempre `<?php echo ?>` — **nunca** `<?= ?>`.
- Escaping por tipo: `esc_url()` (URLs/`href`/`src`), `esc_attr()` (atributos), `esc_html()` (texto), `wp_kses_post()` (HTML rico de ACF/editor).
- Imagens de media library: `wp_get_attachment_image( $id, 'full', false, array( 'class' => '...' ) )` — nunca montar `<img>` à mão quando há attachment ID.

### PHP — acesso a campos ACF
```php
// Single com fallback
$image_id = get_field( 'hero_image' ) ?: get_post_thumbnail_id( $post_id );

// Link (devolve array) — validar antes de usar
$button = get_field( 'section_hero_button' );
if ( $button ) :
	$link_url    = $button['url'];
	$link_title  = $button['title'];
	$link_target = $button['target'] ?: '_self';
endif;

// Repeater
if ( have_rows( 'items' ) ) :
	while ( have_rows( 'items' ) ) : the_row();
		$sub = get_sub_field( 'sub_field_name' );
	endwhile;
endif;

// Group (devolve array) — usar ! empty() para evitar notices
$group  = get_field( 'section_group_name' );
$title  = ( $group && ! empty( $group['title'] ) ) ? $group['title'] : 'Fallback';
$img_id = ( $group && ! empty( $group['image'] ) ) ? $group['image'] : null;
```

**Naming ACF:** snake_case com prefixo de contexto — `section_hero_title`, `section_hero_button`, `events_cpt_place`, `socials_instagram`.

### PHP — template parts & hooks
- Toda a secção via `get_template_part()`:
  ```php
  get_template_part( 'template-parts/modules/usp', 'band' );   // secção reutilizável
  get_template_part( 'template-parts/pages/home/hero' );       // secção só da home
  get_template_part( 'template-parts/posts/post', 'content' );
  ```
- **Onde colocar o ficheiro da secção** (decidir por reutilização):
  - Secção **reutilizada em ≥2 páginas/templates** → `template-parts/modules/` (PHP) + estilo em `_modules/` (SCSS). O Weizenkorn **tem** várias destas (`usp-band`, `offer-grid`, `capabilities-grid`, `cta-form`, `quote-slider` — ver `figma-architecture-analysis.txt`), por isso `modules/` é usada activamente aqui.
  - Secção **presa a uma só página** → `template-parts/pages/{página}/` (PHP) + estilo em `_pages/` (SCSS).
- Hooks: `function weizenkorn_nome() { ... } add_action( 'hook', 'weizenkorn_nome' );` — prefixo `weizenkorn_` sempre.

### PHP — dois erros a evitar (verificados em projetos anteriores)
1. **Body text de ACF textarea (`new_lines: wpautop`) já vem em `<p>`** — nunca voltar a envolver em `<p>` (cria `<p><p>` inválido e perde as classes Tailwind). Usar `<div>`:
   ```php
   // ERRADO: <p class="text-... pb-10"><?php echo wp_kses_post( $body ); ?></p>
   <div class="pb-10"><?php echo wp_kses_post( $body ); ?></div>
   ```
2. **Cor de texto em fundos escuros** — as classes de tipografia (`.title-main`, `.title-secondary`) não trazem cor. Definir a cor **no container**, para os filhos (incl. `<p>` gerados por wpautop) herdarem:
   ```php
   <div class="bg-brand-dark text-brand-cream section-foo__text"> ... </div>
   ```
   Tokens de cor reais (ver `tailwind.config.js` / `_vars.sass`): `brand-red` `#E30613`, `brand-dark` `#252525`, `brand-cream` `#F8F3E9`. Fonte única: **DM Sans** (`font-primary`/`font-secondary`).

### CSS / SCSS — naming
- Tailwind para layout/spacing/responsive; SCSS para componentes custom — não duplicar.
- Classes custom em kebab-case, semânticas. BEM-inspired em componentes complexos: `menu-toggle`, `menu-toggle__bars`, `bar bar--top`.
- Secções: `.section-{nome}` · Botões: `.btn`, `.btn-primary`… · Cards: `.card-{tipo}` · Wrappers: `.theme-container`, `.theme-grid`.
- Manter a ordem de imports do `main.sass` já existente; adicionar módulos/secções na pasta correspondente (`_modules/`, `_components/`, `_pages/`…).

### CSS / SCSS — @apply (estilo da casa)
As classes custom nos ficheiros SASS são construídas com utilitários Tailwind via **`@apply`** (ver `_components/_buttons.sass` e `_components/_typography.sass`), não com propriedades CSS soltas. Regras:
- Uma classe = uma linha `@apply` com os utilitários; usar **valores arbitrários** quando não há token (`text-[82px]`, `leading-[100px]`, `tracking-[0.5px]`, `gap-16`).
- **Responsive** com os breakpoints do Tailwind, alinhados com a grelha do design: base (mobile) · `md:` (768, tablet) · `xl:` (1280, desktop). Ex.: `text-[35px] md:text-[56px] xl:text-[82px]`.
- **Tipografia sem cor** — as classes de título/texto (`.title-hero`, `.title-main`, `.overline`, `.body-text`…) não trazem cor; define-se no container para os filhos (incl. `<p>` do wpautop) herdarem. Excepção: links inline (`.link-inline` → `text-brand-red`).
- Fonte via `font-primary`/`font-secondary` (tokens do `tailwind.config.js`); pesos `font-normal/medium/semibold/bold/black` = DM Sans 400/500/600/700/900.
- CSS puro só quando não há utilitário equivalente (ex.: `grid-template-columns: repeat(12,...)` em `.theme-grid`).

### CSS / SCSS — padrões recorrentes
```sass
// Colunas de grid com altura igual (imagem enche a célula esticada)
.theme-grid.items-stretch    // no HTML
.section-foo__img
  @media (min-width: 1280px)
    height: 100%
  img
    width: 100%
    height: 100%
    object-fit: cover

// Imagem que "sangra" até à direita do viewport (além do container 1820px)
.section-foo__wide-img
  @media (min-width: 1821px)
    margin-right: calc(-1 * (100vw - 1820px) / 2)
    width: calc(100% + (100vw - 1820px) / 2)
.section-foo
  overflow: hidden   // evitar scrollbar horizontal
```

### JavaScript
```javascript
// Entry (main.js): libs opcionais comentadas até serem precisas
import './gsap.js';
// import './swiper.js';
import { debounce, isTouchDevice } from './utils/helpers.js';
document.addEventListener('DOMContentLoaded', () => { /* init */ });

// jQuery sempre em IIFE
/* globals jQuery */
(function ($) {
  document.addEventListener('DOMContentLoaded', function () { /* ... */ });
})(jQuery);
```
- Alpine.js (quando usado): `<style>[x-cloak]{display:none !important;}</style>` + `x-data`, `x-show`, `x-cloak`, `:aria-expanded`.
- Enqueue de scripts sempre no footer, com dependência `array( 'jquery' )` e versão do tema.

### Figma → código
O MCP do Figma devolve posições absolutas num canvas (não é grid-aware). Calcular a coluna manualmente com a **grelha real deste projeto** (gutter 20px = `gap-5` constante em todos os breakpoints):

| Breakpoint | Canvas | Container | Colunas | Coluna | Margens | Tailwind |
|---|---|---|---|---|---|---|
| Desktop | ~1920 | **1820px** | 12 | ~133px | ~50px | `grid-cols-12 gap-5`, inset = `col-start-2 col-span-10` |
| Tablet | 834 | 700px | 6 | 100px | ~67px | `grid-cols-6 gap-5` |
| Mobile | 393 | 321px | 2 | 150px | 36px | `grid-cols-2 gap-5 px-9` |

```
Desktop: unidade (coluna+gutter) ≈ 154px · coluna N começa em x ≈ 50 + (N−1)×154
col = floor((element_left_px - 50) / 154) + 1
```
Workflow: um breakpoint de cada vez (desktop → tablet → mobile), uma secção de cada vez, e confirmar com screenshot + grid overlay. Ver `figma-architecture-analysis.txt` (grelha confirmada a 2026-07-22) para o mapeamento completo deste projeto.

---

## Convenções Gerais

- Breakpoints (confirmado 2026-07-22): usar sempre os breakpoints por omissão do Tailwind, sem os redefinir em `tailwind.config.js`. Vocabulário do projecto: **desktop = `xl` (≥1280px)**, **tablet = entre `md` e `xl` (768–1280px)**, **mobile = abaixo de `md` (<768px)**. Mobile-first: escrever o valor base para mobile/tablet e sobrepor com `xl:` para desktop (ex.: `text-[12px] xl:text-[15px]`), a não ser que o design precise de um valor próprio só para tablet (nesse caso usar `md:` também).
- Não usar plugins desnecessários — preferir código no tema.
- Não instalar dependências npm ou composer sem confirmação explícita.
- Ao adicionar template parts, seguir a estrutura existente em `template-parts/`.
- Assets CSS/JS devem ser compilados via `npm run dev` / `npm run prod` — nunca editar `dist/` directamente.
- Para campos ACF legados (`time`, `date`), usar sempre `event_date.start` / `event_date.end` em código novo.
- Text domain do tema: `weizenkorn`.
