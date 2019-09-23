#Design Considerations

This component was designd inline with the [NL API Strategie](https://docs.geostandaarden.nl/api/API-Strategie) and [https://www.noraonline.nl/wiki/Standaarden](NORA).


__solution__
geldigOp is suported as a backup for validOn, but only validOn us documented.


It is precievable that in future iterations we would like to use indexd array in situations where the index of the array can't be assumed on basis of url notation, when indexes arn't numirical, when we dont want an index to start at 0 or when indexes are purpusly missing (comma notation of id,name,description would always refert to te equvalant of fields: [
  0 => id,
  1 => name,
  2 => description
]


### Duration
For

| Period Designator | Description                                                          |
|-------------------|----------------------------------------------------------------------|
| Y                 | years                                                                |
| M                 | months                                                               |
| D                 | days                                                                 |
| W                 | weeks. These get converted into days, so can not be combined with D. |
| H                 | hours                                                                |
| M                 | minutes                                                              |
| S                 | seconds                                                              |

### Types versus formats

| Type    | Format    | Example  | Source | Description | Documantation                                                        |
|---------|-----------|----------|--------|-------------|----------------------------------------------------------------------|
| integer | int32     |          |        |             |                                                                      |
| integer | int64     |          |        |             |                                                                      |
| string  | float     | 0.15625  |        |             | https://en.wikipedia.org/wiki/Single-precision_floating-point_format |
| string  | double    | 0.15625  |        |             | https://en.wikipedia.org/wiki/Double-precision_floating-point_format |
| integer | byte      |          |        |             |                                                                      |
| integer | binary    |          |        |             |                                                                      |
| string  | date      |          |        |             |                                                                      |
| string  | date-time |          |        |             |                                                                      |
| string  | duration  | P23DT23H |        |             | https://en.wikipedia.org/wiki/ISO_8601#Durations                     |
| string  | password  |          |        |             |                                                                      |
| string  | boolean   |          |        |             |                                                                      |
| string  | string    |          |        |             |                                                                      |
| string  | uuid      |          |        |             |                                                                      |
| string  | uri       |          |        |             |                                                                      |
| string  | email     |          |        |             |                                                                      |
| string  | rsin      |          |        |             |                                                                      |
| string  | bag       |          |        | A BAG uuid  |                                                                      |
| sring   | bsn       |          |        |             |                                                                      |
| string  | iban      |          |        |             |                                                                      |
|         |           |          |        |             |                                                                      | 