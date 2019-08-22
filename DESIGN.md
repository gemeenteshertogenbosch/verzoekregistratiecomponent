#Design Considerations







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