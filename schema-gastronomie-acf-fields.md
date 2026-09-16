# Schema.org — campos ACF a criar para as páginas de Gastronomie

Lista de campos ACF a criar manualmente no wp-admin, para viabilizar o schema
`LocalBusiness`/`Restaurant`/`Bakery`/`Hotel` (via Yoast Schema API) nas 5
páginas de Gastronomie. Ver `CLAUDE.md`, secção "SEO / Schema", para o
contexto — isto substitui a nota "Candidato futuro" assim que os campos
abaixo estiverem preenchidos em pelo menos uma página.

**Não substitui nada do que já existe.** Os campos `address_text`/
`schedule_text` (módulo `our-locations`, só a página da Bäckerei) e
`location_address`/`location_address_2`/`location_schedule_text` (módulo
`location`, as 4 restantes) continuam exactamente iguais no display — texto
livre, sem alterações. Os campos novos abaixo servem só o JSON-LD do schema,
lido directamente pelo Google/schema.org.

## Grupo de campos novo

- **Nome do grupo (título no admin):** `Schema — Local Business` (livre — só
  o título, não afecta os nomes técnicos abaixo)
- **Localização (Location Rules):** aplicar aos 5 page templates (juntar as
  5 regras no mesmo grupo com "or"):
  - `page-gastronomie-rhyvage.php`
  - `page-gastronomie-dasbreitehotel.php`
  - `page-gastronomie-cantina-e9.php`
  - `page-gastronomie-our-bakery.php`
  - `page-gastronomie-events-seminare.php`

## Campos ao nível da página (fora do repeater)

| Nome do campo (Field Name)     | Tipo     | Label sugerido            | Choices / notas |
|---------------------------------|----------|----------------------------|------------------|
| `gastro_schema_business_type`  | Select   | Tipo de estabelecimento    | Choices: `Bakery : Bäckerei`, `Restaurant : Restaurant`, `CafeOrCoffeeShop : Café`, `Hotel : Hotel`, `LocalBusiness : Outro`. Return format: Value. |
| `gastro_schema_locations`      | Repeater | Localizações               | Ver sub-campos abaixo. Min rows: 0 — a secção não gera nada sem dados, tal como o resto do site. |

## Sub-campos dentro de `gastro_schema_locations` (uma linha por localização)

| Nome do campo (Field Name) | Tipo     | Label sugerido                  | Notas |
|------------------------------|----------|----------------------------------|-------|
| `name`                       | Text     | Nome                              | Ex.: "Bäckerei Weizenkorn Augst", "Restaurant Rhyvage" |
| `street_address`             | Text     | Rua e número                      | Ex.: "Hauptstrasse 4" |
| `postal_code`                | Text     | Código postal                     | Ex.: "4302" |
| `locality`                   | Text     | Localidade                        | Ex.: "Augst" |
| `phone`                      | Text     | Telefone                          | Formato internacional se possível, ex.: `+41 61 560 95 95` |
| `opening_hours`              | Textarea | Horário (uma regra por linha)     | **Formato Schema.org**, não o texto livre normal — uma regra por linha, ex.: `Mo-Fr 06:30-18:30` / `Sa 07:00-16:00` (domingo fechado = não escrever linha nenhuma) |

## Porquê um repeater e não campos fixos

A Bäckerei tem 3 localizações (Augst, Dreispitz, Erasmusplatz) numa só
página; as outras 4 páginas têm só 1 (ou, no caso de Events & Seminare,
possivelmente 2 — ver `location_address_2` no módulo `location.php`). Um
repeater cobre os dois casos com a mesma estrutura, sem precisar de campos
diferentes consoante a página.

## Próximo passo

Depois de preenchido em pelo menos uma página (idealmente a Bäckerei, já que
é a mais completa nos screenshots), escrever
`inc/schema/class-weizenkorn-schema-restaurant.php`, seguindo o mesmo padrão
de `class-weizenkorn-schema-jobposting.php`/`class-weizenkorn-schema-faq.php`
— uma peça de schema por página, registada via `wpseo_schema_graph_pieces`,
condicionada a `gastro_schema_locations` ter pelo menos uma linha.
