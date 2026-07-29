---
name: go-live
description: Publica o site em produção. Compila os assets em modo prod, corre o lint, faz versionamento SemVer (arranca em 1.0.0 na primeira vez), sincroniza CHANGELOG.md e style.css, prepara um commit curto e — só com confirmação explícita — faz push a main (que é o go-live). Usar sempre que o site vai para produção.
---

# Go-live (deploy para produção)

> **Nota:** este skill é para **repos de projecto**. No repo do starter não há
> produção — usar o `/release` (tag → Action → zip).

Este repo faz deploy por **push à branch `main`** (plugin/CI tipo WP Pusher).
**Não** há Action nem zip como no starter — por isso `git push origin main` **é**
o momento do go-live: assim que o push acontece, o site actualiza.

Ao contrário do `/release`, este skill **compila os assets de produção** antes de
commitar. Nunca deixar `dist/` de desenvolvimento ir para produção.

## Processo

1. **Pré-check**
   - Confirmar que a branch actual é `main` (`git rev-parse --abbrev-ref HEAD`).
   - Correr `git status` e perceber a working tree. Se houver alterações **não
     relacionadas** misturadas, alertar e sugerir separá-las antes de continuar.
   - Lembrar o utilizador: **push a `main` = site live imediato**.

2. **Build de produção** *(passo-chave, não saltar)*
   - Correr `npm run prod`.
   - Confirmar que `dist/mix-manifest.json` e os ficheiros em `dist/` foram
     regenerados (hashes/minificação). Se o build falhar, parar e reportar.

3. **Lint**
   - Correr `npm run php:lint`. Se falhar, tentar `npm run php:fix` e voltar a
     correr; reportar o que não for auto-corrigível e parar até estar limpo.

4. **Versionamento** (SemVer)
   - Se **não existem tags nem `CHANGELOG.md`** → é a primeira ida a produção:
     versão **`1.0.0`**, criar `CHANGELOG.md`.
   - Caso contrário, propor bump com base no diff desde a última tag/versão:
     - só correcções/ajustes → **PATCH**
     - funcionalidade nova → **MINOR**
     - breaking change → **MAJOR**
   - **Por omissão** (projecto ainda em fase local/staging, sem lançamento
     público): a opção recomendada é **manter o número de versão actual**,
     só acrescentando a entrada nova à secção do `CHANGELOG.md` já existente
     para essa versão (não criar secção nova nem tocar no `style.css`). Ainda
     assim, apresentar sempre as duas opções (manter vs. bump) e **confirmar
     com o utilizador** qual aplicar antes de avançar — isto muda quando o
     projecto passar a ter lançamentos públicos reais.

5. **CHANGELOG.md + style.css** (só quando a versão sobe)
   - Adicionar/completar a secção `## [X.Y.Z] — YYYY-MM-DD` com as categorias
     correctas (Added/Changed/Fixed/Removed/Deprecated/Security), em inglês,
     orientada ao que mudou.
   - Sincronizar o `Version:` do `style.css` com o mesmo número.
   - Changelog e `style.css` têm de ficar **sempre com o mesmo número**.
   - Se a decisão do passo 4 foi manter a versão actual, saltar a mudança do
     `style.css` e só adicionar as categorias novas (Added/Changed/Fixed/…)
     à secção já existente do `CHANGELOG.md` para essa versão.

6. **Commit** (mensagem curta e simples)
   - Formato: `vX.Y.Z: <resumo curto>` (inglês, uma linha, orientado ao utilizador).
   - Incluir os `dist/` recompilados no commit.
   - **Nunca fazer push neste passo.**

7. **Push = go-live** (só com confirmação explícita)
   - Antes de pedir luz verde, confirmar com o utilizador:
     - conteúdo/loja/WPML/age-gate prontos para o público;
     - o build de produção do passo 2 está incluído no commit.
   - Com confirmação: `git push origin main`.
   - Indicar como **verificar o deploy**:
     - abrir o site em produção e fazer **hard-refresh** (Ctrl/Cmd+Shift+R);
     - confirmar que o CSS/JS novos carregam (cache-busting via o helper
       `<prefixo>_asset_version()` do tema, hashes do `mix-manifest.json`);
     - verificar o log/estado do plugin de deploy (WP Pusher → Deploy log).

8. **Tag (opcional)**
   - Como o deploy é por branch, a tag é só um **marcador histórico** em git e
     **não** dispara deploy. Se o utilizador quiser: `git tag vX.Y.Z` e,
     opcionalmente, `git push origin vX.Y.Z`.

## Regras

- **Nunca** fazer `git push` (nem tag com push) sem confirmação explícita do
  utilizador — na `main`, push publica o site.
- **Sempre** correr `npm run prod` antes do commit de go-live; não deixar `dist/`
  de dev ir para produção.
- Changelog e `style.css` sempre com o mesmo número.
- Commit de go-live: mensagem curta e simples (uma linha), ao contrário de
  changelogs internos detalhados.
- Se a working tree tiver alterações não relacionadas, alertar e sugerir separá-las.

## Diferenças face ao `/release`

| | `/release` | `/go-live` |
|---|---|---|
| Compila assets | não | **sim (`npm run prod`)** |
| Deploy | tag → Action → zip | **push a `main`** |
| Commit | resumo detalhado | resumo curto |
| Pós-passo | Action verde + zip | site live + deploy log |
