# Craft Redirects

A redirect management plugin for Craft CMS 5. Manage URL redirects — all from the control panel.

## Features

- **Exact & regex redirects** with support for captured groups (`$1`, `$2`, etc.)
- **301, 302, 307, 308** status codes
- **Chain detection** — warns when a redirect points to another redirect's source URL
- **Search & sort** — filter and sort redirects by any column
- **Bulk actions** — enable, disable, or delete multiple redirects at once
- **CSV import/export** — import redirects from CSV or export your full list
- **Labels & notes** — organize redirects with optional metadata

## Requirements

- Craft CMS 5.0 or later
- PHP 8.2 or later

## Installation

```bash
composer require custom/craft-redirects
php craft plugin/install redirects
```

## Usage

After installation, a **Redirects** item appears in the control panel sidebar with three sections:

### Redirects

Create and manage redirects. Each redirect has:

| Field      | Description                                     |
| ---------- | ----------------------------------------------- |
| From URL   | The path to redirect from (must start with `/`) |
| To URL     | The destination URL                             |
| Type       | HTTP status code (301, 302, 307, 308)           |
| Match Type | `exact` or `regex`                              |
| Label      | Optional label for organization                 |
| Notes      | Optional notes                                  |

**Exact match** redirects are case-insensitive and normalize trailing slashes — `/old-page` and `/old-page/` are treated the same.

**Regex match** redirects use PCRE patterns. Captured groups can be referenced in the destination URL:

| From URL             | To URL            | Example                                          |
| -------------------- | ----------------- | ------------------------------------------------ |
| `/blog/(\d{4})/(.*)` | `/articles/$1/$2` | `/blog/2024/my-post` -> `/articles/2024/my-post` |

### Import

Import redirects from a CSV file. The import flow provides column mapping and a preview before importing. An example CSV is available for download.
