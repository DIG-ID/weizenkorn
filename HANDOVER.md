# Handover — sessão de 2026-08-24/25

Notas para retomar o trabalho noutra máquina. O `CLAUDE.md` continua a ser a
referência de convenções; isto é só o estado e as decisões desta sessão.

## Estado do repositório

Versão **1.8.1** (`style.css` e `CHANGELOG.md` sincronizados). Branch `main`,
alinhada com o remote no último commit `172ae9c`.

Por commitar — o trabalho todo desta sessão:

```
?? template-parts/modules/offer-grid.php
?? template-parts/modules/process-steps.php
?? template-parts/modules/faq.php
?? assets/sass/_modules/_offer-grid.sass
?? assets/sass/_modules/_process-steps.sass
?? assets/sass/_modules/_faq.sass
 M assets/sass/_modules/_modules.sass          três @import novos
 M template-parts/components/card-overview.php  card sem link
 M page-templates/page-services-kreativatelier.php
 M dist/                                        build de DEV, não de prod
```

O `dist/` está compilado em **dev**. O `/go-live` corre `npm run prod` antes do
commit, por isso não commitar o `dist/` à mão.

Próxima versão sugerida: **1.9.0** (funcionalidade nova). Os módulos novos já
têm `@since 1.9.0` nos docblocks.

## O que foi feito

Três módulos novos para a página **Kreativatelier**, todos verificados contra os
frames de desktop, tablet e mobile do Figma.

### `offer-grid` — "Gestalten mit Kopf, Herz und Hand"

Grelha de cards de oferta. Reutiliza o `components/card-overview.php` dos
Services sem o alterar; o que é próprio é a grelha.

|                     | mobile | tablet | desktop |
|---------------------|--------|--------|---------|
| Cards por linha     | 1      | 2      | 2       |
| Altura da imagem    | 192    | 192    | 400     |
| Card ímpar final    | 192, largura toda | **278**, 6 cols | **400**, 10 cols |
| Row-gap             | 32     | 24     | 54      |
| Régua → grelha      | 32     | 32     | 96      |

O último card ficar largo é **CSS, não PHP**: `:last-child:nth-child(odd)` só
casa quando o último é também ímpar, por isso acrescentar ou tirar uma oferta
rearranja a grelha sozinho. Não há campo a dizer qual é o grande.

### `process-steps` — "So entsteht Mehrwert"

Fila de fotografias com barra de legenda e uma frase que aparece no hover.

|                      | mobile   | tablet   | desktop |
|----------------------|----------|----------|---------|
| Tiles por linha      | 1        | 2        | 5       |
| Rácio do tile        | 320/297  | 340/320  | 350/430 |
| Passo ímpar final    | —        | 2 cols, 700/625 | — |
| Column / row gap     | 20 / 10  | 20 / 32  | 20 / —  |
| Barra                | 42h      | 42h      | 50h     |

Grelha própria (`repeat(5, 1fr)`), não a `.theme-grid` — cinco tiles iguais não
têm expressão em doze colunas — e corre a **largura toda do container**, ao
contrário de todas as outras secções, que ficam no inset.

A regra do tile largo está limitada em cima **e** em baixo (`768–1279px`): no
desktop a grelha tem 5 colunas e os 5 passos preenchem-na exactamente, por isso
um `min-width` solto partiria a linha.

O hover está atrás de `@media (hover: hover)`; onde não há pointer a barra
mostra a frase de uma vez. A animação é `grid-template-rows: 0fr → 1fr`, a única
forma de transicionar para uma altura que o conteúdo decide.

### `faq`

Lista estática (confirmado com o cliente: **não é acordeão**). É um `<dl>` —
`<dt>` pergunta, `<dd>` resposta — com um `<div>` por par que é a grelha e
carrega a régua.

|                    | mobile    | tablet         | desktop            |
|--------------------|-----------|----------------|--------------------|
| Colunas            | 1 (todas) | 1 (cols 1-5)   | 2 (col 4+3 · col 7+5) |
| pt / pb            | 16 / 16   | 24 / 34        | 32 / 44            |
| Pergunta ↔ resposta| 24        | 24             | —                  |

O separador (`#252525`) corre a largura toda do container enquanto o texto fica
nas colunas 4-11 — por isso a régua está no item e não nas colunas.

A pergunta é **Regular uppercase** (`.label-overline`), não bold. Parece um
título de card mas o Figma dá 20/30 Regular.

### `card-overview` — alterado

Sem URL passou a desenhar-se como `<div>` em vez de `<a>`, sem seta e sem a
classe `group`. Antes fazia `return` e o card **desaparecia em silêncio**. O
`overview-cards` dos Services não é afectado: tira sempre o URL do
`get_permalink()` da página-filha.

### `page-services-kreativatelier.php`

Preenchido. Ordem: hero-section · offer-grid · usp-band · process-steps ·
quote-slider · location · cta-form · faq.

**Atenção:** o ficheiro foi criado vazio pelo Daniel no commit `0aff491`, o
mesmo que criou a `page-services.php`. Ele está a tratar da página Services —
combinar com ele para não mexerem os dois nos mesmos ficheiros.

## Por decidir

**A escala tipográfica base.** Cinco secções confirmam que `.label-overline`
devia ter `leading-[15px]` na base (tem 30) e três que `.body-text` devia ter
22 (tem 20). Um texto de 14px com 30px de entrelinha é o dobro do espaçamento —
o 30 foi quase de certeza escrito a pensar no passo `xl:`, onde a fonte é 20px.

Há **três overrides scoped** à espera desta decisão, em `_spaces.sass`,
`_offer-grid.sass` e `_faq.sass`. Corrigir na origem são duas linhas em
`_typography.sass` mais apagar os três overrides, seguido de uma passagem de
olhos pelas páginas já montadas. Afecta 18 secções, por isso é decisão do Bruno.

**Título do FAQ.** O Figma dá 120/76 no desktop; o `.title-main` dá 112/112.
Mobile (38) e tablet (74) batem ao pixel com a escala do tema, o que aponta para
um valor solto do designer naquele frame. Ficou com a escala partilhada.

## Trabalho de admin pendente

- Tirar o *Required* do campo **Link** em `offer_grid → items` (o código já
  trata a ausência).
- Criar o form CF7 `CTA – Kreativatelier` (duplicar o *CTA – Our Bakery*), pôr
  o `To` e o Subject certos, e o shortcode no campo `cta_shortcode` da página.
- **Não há form por omissão**: `general_cta_form_shortcode` nas Theme Options
  está vazio e o módulo faz `return` sem shortcode. Vale a pena preencher.
- O **CTA – Events & Seminare** tem o Subject errado — diz "Bäckerei", copiado
  do form da padaria.
- O `From` dos sete forms é `wordpress@weizenkorn.digid`, domínio local. Item de
  go-live: mudar nos sete de uma vez.

## Coisas aprendidas nesta sessão (custaram tempo)

- **Tabs do ACF não criam namespace.** O prefixo vem de um campo de tipo
  **Group**. Uma tab chamada `offer_grid` e um group chamado `offer_grid`
  coexistem sem colidir, porque tabs não guardam valores.
- **Clonar o GRUPO "Section Title"** (`group_6a4fb434d42e9`), nunca um repeater
  lá dentro, e nunca o clone de outra página — uma cadeia de clones renderiza
  vazio enquanto o admin mostra os valores.
- **Apagar ou renomear um campo ACF deixa postmeta órfã** que o `get_field()`
  continua a devolver.
- **Margens verticais de irmãos adjacentes colapsam** — o intervalo é o máximo,
  não a soma. Foi por isso que os `mt` das grelhas são o valor inteiro do frame.
- **`@apply title-card` funciona.** O Tailwind emite-o em regras separadas por
  media query, por isso um `grep` da primeira regra parece mostrar que faltam
  propriedades.
- **O Tailwind nunca lê templates guardados na base de dados** (CF7, ACF). As
  classes usadas lá têm de estar declaradas no SASS.
- **Acesso à base de dados local**, útil para confirmar campos ACF em vez de
  adivinhar:

  ```bash
  SOCK="$HOME/Library/Application Support/Local/run/zAR3Jqql-/mysql/mysqld.sock"
  /opt/homebrew/opt/mysql-client/bin/mysql -u root -proot -S "$SOCK" \
    -e "SELECT post_name, post_title FROM local.wp_posts WHERE post_type='acf-field-group';"
  ```

  O id da pasta em `run/` muda por máquina — listar `run/*/mysql/mysqld.sock` e
  testar qual responde.

## Ainda por construir

O `figma-architecture-analysis.txt` é o roteiro. Por fazer, entre outros:
`capabilities-grid`, `service-info-downloads`, `cross-links`, `testimonial`,
`contact-person`, `sales-points`, e os CPTs `news` e `job`.
