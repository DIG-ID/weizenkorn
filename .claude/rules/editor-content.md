---
description: HTML and media that come from the database — the Tailwind safelist, editor-uploaded images, and Contact Form 7 markup.
---

# Conteúdo vindo do editor

Regras para tudo o que **não está no código**: markup escrito num campo ACF, um
formulário do Contact Form 7, uma imagem carregada na media library. Estas três
coisas já custaram tempo neste projecto; todas falham em silêncio.

---

## 1. O Tailwind nunca lê a base de dados

O Tailwind gera CSS a partir dos ficheiros que o `content` do
`tailwind.config.js` manda ler — `.php`, `.js`, `.sass`. **Nunca lê a base de
dados.** Uma classe escrita num campo ACF, num formulário do CF7 ou no editor
do WordPress não existe no CSS compilado, e não há erro nenhum a avisar: o
elemento simplesmente não é estilizado.

Qualquer classe que viva na base de dados tem de estar no `safelist`:

```js
// tailwind.config.js
safelist: [
  'hidden',
  'md:hidden',
  'md:inline',
  'xl:hidden',
],
```

### Caso real — quebras de linha responsivas nos títulos

Um `<br>` com classe, escrito pelo editor num campo de título, permite quebrar
a linha só nalguns tamanhos:

| Onde quebra | Markup |
|---|---|
| Só mobile (< 768px) | `<br class="md:hidden">` |
| Só tablet (768–1279px) | `<br class="hidden md:inline xl:hidden">` |
| Tablet e mobile (< 1280px) | `<br class="xl:hidden">` |
| Todos os tamanhos | `<br>` |

**Pôr sempre um espaço de um dos lados do `<br>`.** Nos tamanhos em que fica
`display:none`, é esse espaço que separa as duas palavras:

```
Massgeschneidert<br class="md:hidden"> für Sie
```

Sem o espaço, no desktop sai `Massgeschneidertfür Sie`.

Isto só funciona onde o `wp_kses()` deixa passar `class` no `<br>` — hoje em
`template-parts/modules/hero-section.php` e
`template-parts/components/section-heading.php`. Noutro campo qualquer o `<br>`
sai impresso como texto.

**Precisas de outro breakpoint?** Acrescenta ao `safelist` e recompila. Escrever
`2xl:hidden` sem isso não faz nada.

---

## 2. Imagens carregadas pelo editor dimensionam-se pelo ficheiro

`h-auto` (ou `w-auto`) numa imagem vinda da media library entrega a decisão do
tamanho a quem fez o upload. Duas pessoas carregam dois ficheiros e a página
fica com dois tamanhos diferentes.

Aconteceu com os logos de separator das páginas de Gastronomie: com
`xl:h-auto`, cada um renderizava à sua altura intrínseca — Rhyvage a **31px**,
Cantina e9 a **53px**, DasBreiteHotel a **68px**, a Bäckerei acima de **150px**.

**Dar sempre uma altura fixa por breakpoint** a imagens que o editor carrega e
que têm de ficar consistentes entre páginas:

```php
'class' => 'w-auto h-[32px] md:h-[71px] xl:h-[101px] max-w-full',
```

Dimensionar por **altura** e não por largura: marcas altas e marcas largas
partilham o mesmo slot, e por largura a alta é ampliada até destoar.

Nota: uma marca empilhada (ícone + duas linhas de texto) fica mais estreita que
uma de uma linha à mesma altura. É geometricamente correcto; se destoar, a
saída é pedir o ficheiro em versão horizontal, não mexer no número.

---

## 3. O Contact Form 7 injecta markup dentro do fluxo

O CF7 insere o aviso de validação (`.wpcf7-not-valid-tip`) **dentro** do
`.wpcf7-form-control-wrap` de cada campo. Em fluxo normal, um campo que falha
empurra para baixo tudo o que vem a seguir — e como os campos falham todos ao
mesmo tempo, salta a coluna inteira.

Tirar do fluxo e deixar assentar no espaço que a coluna já deixa entre campos:

```sass
.wpcf7-form-control-wrap
  @apply relative

.wpcf7-not-valid-tip
  @apply absolute left-0 top-full
```

**Confirmar que há folga.** O aviso ocupa 20px; o `cta-form` tem gaps de 24px
(mobile) e 32px (tablet+), por isso cabe. O `order-form` tem gaps de 8 a 20px
em vários pontos — lá, o aviso absoluto aterra em cima do campo seguinte, e por
isso a regra **não** lhe foi aplicada.

O `.wpcf7-response-output` (a caixa de confirmação/erro por baixo do form)
**fica no fluxo** — essa deve mesmo ocupar espaço.

---

## 4. O CF7 traz as suas próprias strings traduzidas

As mensagens de validação vêm do ficheiro de tradução do plugin, não do tema.
O locale escolhido decide o tratamento e a ortografia:

- `de_DE` é a variante **informal** do WordPress (du) — *"Bitte überprüfe das"*
- `de_DE_formal` e `de_CH` são formais (Sie) — *"Bitte überprüfen Sie das"*

Num site suíço, `de_CH` resolve duas coisas de uma vez: é formal e usa
ortografia suíça (**`ss`, nunca `ß`**). O `de_DE` do CF7 traz 13 strings com
`ß` (`Dateigröße`, `Schließen`), que destoam do resto do site.

Corrigir o locale, não as strings uma a uma.
