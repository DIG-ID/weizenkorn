---
description: PHP house style — escaping, ACF access, template parts, hooks.
---

# PHP — convenções de escrita

> Padrões concretos de "como escrevemos componentes". Complementa (não repete) as
> secções de PHPDoc, Semântica HTML5 e i18n do `CLAUDE.md`.

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

