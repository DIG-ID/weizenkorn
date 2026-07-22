---
name: setup-project
description: Executa o SETUP.md interactivamente para arrancar um projecto novo a partir do starter - pergunta nome/slug/prefixo/URL, faz o find & replace, actualiza style.css e webpack.mix.js, faz reset ao versionamento e prepara o primeiro build. Usar uma vez, logo depois de extrair o zip do tema.
---

# Setup de projecto novo (SETUP.md automatizado)

Automatiza os passos 1–9 do `SETUP.md`. Assume que o zip do starter já foi
extraído para `wp-content/themes/` e que estamos dentro dessa pasta.

## Inputs (perguntar ao utilizador de uma vez, no início)

1. **Nome do projecto** (human-readable, ex.: "Weizenkorn")
2. **Slug da pasta/tema** (ex.: `weizenkorn`) — a pasta deve já ter este nome;
   se ainda se chamar `theme-starter-laravel`, avisar para renomear primeiro
   (renomear a pasta com o Claude Code lá dentro pode partir a sessão).
3. **Prefixo/text domain** (curto, lowercase, underscores, ex.: `weizenkorn`)
4. **URL de dev local** (ex.: `https://weizenkorn.digid/`)
5. **Cores primária/secundária e fontes** (opcional — pode ficar para depois)

## Processo (por ordem, validando cada passo)

1. **Find & replace no projecto inteiro** (case-sensitive, ordem importa):
   - `theme-starter-laravel` → slug
   - `Theme Starter Laravel` → nome do projecto
   - `digid` → prefixo (ATENÇÃO: não substituir `dig.id` nem URLs `digid.` de
     dev; rever os matches antes de aplicar — Author/Author URI mantêm dig.id)
   - Renomear também o output do script `zip` no `package.json`.
2. **style.css**: Theme Name, Description, `Version: 1.0.0`, Text Domain.
3. **webpack.mix.js**: `target` do BrowserSync → URL de dev.
4. **_vars.sass + tailwind.config.js**: cores e fontes, se fornecidas.
5. **functions.php**: rever constantes (font provider, Google Maps key vazia).
6. **inc/theme-setup.php**: rever nav menus com o utilizador.
7. **Reset de versionamento** (passo 9 do SETUP.md): `CHANGELOG.md` limpo com
   entrada única `[1.0.0]` referindo a versão do starter de origem (ler a
   última versão do CHANGELOG antes de o limpar); apagar `TODO.md`;
   `package.json` name/version.
8. **CLAUDE.md do projecto**: limpar exemplos do starter que não se aplicam
   (CPTs/field groups de exemplo) e deixar nota para preencher com os reais.
9. **Instalar e buildar**: `npm install` e `npm run dev`. Reportar erros.
10. Sugerir próximos passos: criar o repo git do projecto, correr
    `/figma-analysis` com o design (passo 12 do SETUP.md).

## Regras

- Mostrar um resumo do find & replace (nº de ocorrências por ficheiro) ANTES
  de aplicar — substituições erradas de prefixo são difíceis de reverter.
- Nunca tocar em `dist/` manualmente; é regenerado pelo build.
- No fim, confirmar que `npm run php:lint` continua a passar.
