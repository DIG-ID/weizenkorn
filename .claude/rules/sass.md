---
description: SASS and Tailwind house style — @apply, naming, breakpoints, recurring layout patterns.
paths:
  - "assets/sass/**/*.sass"
  - "tailwind.config.js"
---

# SASS / Tailwind — convenções de escrita

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

