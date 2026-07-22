---
name: figma-analysis
description: Analisa um projecto Figma e gera o figma-architecture-analysis.txt com o mapeamento para a arquitectura do tema (sections, components, modules, templates WP, campos ACF). Usar no arranque de um projecto novo, depois do SETUP.md, ou quando o design é actualizado.
---

# Figma → Arquitectura do Tema

Gera um `figma-architecture-analysis.txt` na raiz do tema a partir de um ficheiro Figma, mapeando o design para a arquitectura deste starter (ver taxonomia no `CLAUDE.md`: Section / Component / Module / Template / Helpers).

## Inputs (perguntar ao utilizador se não fornecidos)

1. **Link do ficheiro Figma** (URL `figma.com/design/...`)
2. **Nome da página do Figma** a analisar (ex.: "Hifi Wireframe_in process") — os ficheiros de cliente têm normalmente páginas Lofi/Hifi/Design System; analisar a mais recente/final.

## Processo

1. **Estrutura**: `get_metadata` sem nodeId para listar as páginas; identificar a página-alvo; `get_metadata` do node da página para obter os frames de topo (os ecrãs). Se o XML for grande demais, extrair apenas os frames de indent 1–2 (id, name, width×height).
2. **Análise de repetição**: contar quantas páginas partilham os mesmos grupos/frames filhos (por nome). Os blocos presentes em 3+ páginas são candidatos a **sections/components reutilizáveis**; os presentes em quase todas são globais (header, footer, page-header).
3. **Análise visual**: `get_screenshot` (maxDimension 4096 para páginas altas) de 5–8 páginas representativas: Home, uma listagem (archive), um single, uma página de conteúdo composto, contacto, e qualquer página com padrões únicos (formulários, mapas, sliders). Descarregar via curl para o scratchpad e ler.
4. **Tokens**: se disponível, `get_variable_defs` para cores/tipografia exactas (para `_vars.sass` e `tailwind.config.js`).

## Output — `figma-architecture-analysis.txt` na raiz do tema

Seguir esta estrutura (ver exemplo de um projecto anterior se existir):

1. **Cabeçalho** — projecto, ficheiro/página Figma, data, páginas analisadas em detalhe.
2. **Visão geral** — tipo de site, línguas (WPML?), nº de templates, áreas de conteúdo, tokens de design visíveis.
3. **Globais** — header, footer, menu overlay → `template-parts/` (Section).
4. **Secções** — blocos de página reutilizáveis → `template-parts/pages/` (Section), com nota de variantes.
5. **Componentes** — peças pequenas repetidas → `template-parts/components/` (Component), **com os `$args` sugeridos** para cada um. Regra: componentes recebem dados via `get_template_part( ..., null, $args )`, nunca chamam `get_field()`.
6. **Modules** — tudo o que tem comportamento JS (sliders → Swiper, overlays → GSAP, mapas, filtros → Isotope, lightbox → Fancybox). Confirmar que as libs do bundle são mesmo usadas; sinalizar as que não são.
7. **Mapeamento WordPress** — front-page, page-templates, CPTs (+archive/single), 404, legais. Sinalizar quando muitas páginas partilham secções (considerar ACF flexible content vs. templates fixos).
8. **Sugestão de custom fields** — padrão por secção (overline/title/description/link/variant) + grupos por template/CPT.
9. **Template tags** — helpers funcionais (datas `<time>`, ícones inline, normalização de campos link ACF).
10. **Notas / decisões em aberto** — tudo o que precisa de decisão do cliente/equipa (ex.: WooCommerce vs. CPT+inquiry, estrutura de CPTs, comportamentos não especificados no design).

## Regras

- Não inventar conteúdo: registar apenas o que se vê no design; dúvidas vão para a secção "decisões em aberto".
- Respeitar o stack standard (`CLAUDE.md`): schema via Yoast, formulários CF7, newsletter Mailchimp, multilingue WPML.
- O `.txt` é um documento de trabalho por projecto — actualizá-lo quando o design muda (secções novas → acrescentar, não reescrever).
