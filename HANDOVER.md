# Handover — sessão de 2026-10-13

**Isto é um instantâneo, não um documento vivo.** Descreve trabalho decidido mas
não executado *neste* repositório. Apagar assim que estiver feito — o
`HANDOVER.md` anterior foi removido (`afbc4c5`) precisamente por ter ficado
parado na 1.8.1 a descrever trabalho já entregue há semanas.

A referência permanente de convenções continua a ser o `CLAUDE.md`.

> **O trabalho do starter não está aqui.** Foi para o `TODO.md` do
> `theme-starter-laravel` (commit `67af680`), na secção *"P2 — Convenções &
> automação do arranque de projectos"*: as convenções para `.claude/rules/`, as
> três lições por escrever, o `WORKFLOW.md` congelado e os três skills de
> automação. Fica lá porque é lá que o trabalho se faz, e porque o `TODO.md` do
> starter já está em `export-ignore` — nunca vai nos zips dos clientes.

---

## Estado do repositório

Versão **1.19.4**, `style.css` e `CHANGELOG.md` sincronizados. Branch `main`,
working tree limpa.

Commits locais **por enviar**: `afbc4c5` (remoção do handover antigo) e o deste
ficheiro.

> Nota prática: nas sessões recentes o `git push` falhou sempre a partir do
> Claude Code — `could not read Username for 'https://github.com'`. O `fetch`
> funciona; o push tem de ser feito no teu terminal. Não há `~/.ssh/` nem `gh`
> instalado nesta máquina.

---

## 1. Derivas no `CLAUDE.md`

Três secções afirmam hoje o contrário da realidade. Confirmadas a 2026-10-13
contra o HEAD.

| Secção | Diz | Realidade |
|---|---|---|
| **ACF Field Groups** | "Ainda por criar — nenhum grupo ACF definido no projecto até à data" | **401** chamadas a `get_field()`/`have_rows()` e 6 exports `acf-*.json` na raiz |
| **Custom Post Types** → `news` | "Notícias — ainda por criar" | `archive-news.php` e `single-news.php` existem |
| **Stack** → WooCommerce | "decisão pendente: pode acabar por usar CPT `product` + CF7" | **0** ficheiros com código WooCommerce — o CPT próprio já decidiu |

A pior é a primeira: os grupos ACF existem só na base de dados e nos exports
JSON, sem mapa nenhum. A secção pede para os documentar à medida que são
criados, e isso nunca aconteceu.

**Sugestão:** gerar o mapa a partir dos `acf-*.json` da raiz em vez de o
escrever à mão.

### Porque é que isto derivou

Explica o padrão, e por isso vale a pena ficar registado. O `CLAUDE.md` foi
escrito quase todo **no primeiro dia** (261 → 392 linhas, tudo a 2026-07-22).
Em 230 commits desde então só lhe tocaram 9 vezes, e as 4 edições posteriores a
Julho somam **+8 / −6 linhas** — todas a acrescentar uma linha a tabelas de
schema que já existiam. Nenhuma secção nova foi criada depois do primeiro dia.

O ficheiro cresce bem nas **convenções** e fica para trás no **inventário**.
O `/release` e o `/go-live` obrigam a tocar no changelog a cada entrega; o
`CLAUDE.md` não tem gatilho equivalente.

---

## 2. Pendentes menores

- **`template-parts/modules/offer-grid.php`** — 4 warnings de alinhamento de
  array, auto-corrigíveis com `npm run php:fix`. Deixados de fora dos últimos
  go-lives para não misturar alterações cosméticas com as entregas. Tratar à
  parte.
- **`order-form`** — a correcção do aviso de validação do CF7 (v1.18.4) foi
  aplicada só ao `cta-form`. O `order-form` usa gaps de 8 a 20px, onde uma
  mensagem absoluta de 20px aterra em cima do campo seguinte. Para o mesmo
  comportamento é preciso primeiro abrir espaço entre os campos.
- **Logo separator da Bäckerei** — desde a v1.18.3 todos têm `xl:h-[101px]`. A
  marca da Bäckerei é empilhada (ícone + duas linhas), por isso à mesma altura
  sai com 186px de largura contra os 320–394px das outras. Se incomodar, a
  saída é um ficheiro em versão horizontal; mexer no número desequilibra os
  outros três.
- **Locale alemão** — o site tem `de_DE`, que é a variante **informal** (du). O
  CF7 tem lá 46 strings em forma du e 13 com `ß`. O site trata o visitante por
  **Sie** e usa ortografia suíça (`ss`, nunca `ß`) em todo o tema. O correcto
  para um site de Basileia é **`de_CH`** — resolve as duas coisas de uma vez.
  Verificar se o `de_CH` do CF7 está completo; o que faltar traduz-se no WPML
  String Translation.

---

## 3. Por verificar (bloqueado)

O **socket do MySQL do Local não respondeu** em nenhuma das sessões recentes,
por isso ficou por confirmar:

- as versões dos plugins de produção (Yoast, WP Rocket, SG Security, Safe SVG,
  Mailchimp) — aparecem como "a confirmar" no documento de stack entregue ao
  cliente
- qual o ficheiro de logo que a página da Bäckerei usa de facto
- o locale realmente activo na base de dados e como o WPML o tem mapeado

---

## 4. Entregue nesta sessão

Documento de **stack tecnológica para o cliente**, publicado como artifact
privado: <https://claude.ai/artifact/J9E22YdJJLFo7bYCJjPLL8>

Cinco secções (plataforma, tema, interface, compilação, plugins) com versões
lidas dos ficheiros reais. Os cinco plugins de produção estão marcados como "a
confirmar" — actualizar quando os números estiverem disponíveis.
