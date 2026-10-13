---
description: Translating Figma frames to code — the project grid and how to find a column.
---

# Figma → código

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
