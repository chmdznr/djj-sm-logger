# Security Notes

Operational appendix to [`security_best_practices_report.md`](security_best_practices_report.md).

## Subresource Integrity (SRI) — regenerating hashes

All CDN-hosted CSS/JS in `resources/views/layouts/admin.blade.php` and
`resources/views/layouts/app.blade.php` are pinned with `integrity="sha384-..."`
attributes plus `crossorigin="anonymous"`. The browser refuses to execute the
resource if the bytes served by the CDN no longer match the hash.

**Whenever you bump a CDN library version, you must regenerate its hash:**

```bash
curl -s 'https://cdnjs.cloudflare.com/ajax/libs/<lib>/<version>/<file>' \
  | openssl dgst -sha384 -binary \
  | openssl base64 -A
```

Then update the corresponding tag:

```html
<script src="https://cdnjs.cloudflare.com/ajax/libs/<lib>/<version>/<file>"
        integrity="sha384-<output-of-the-command-above>"
        crossorigin="anonymous"></script>
```

Rules of thumb:

- Hash the **exact URL** referenced in the blade file (same protocol, same path).
  A different minified build or CDN mirror produces a different hash.
- Always keep `crossorigin="anonymous"` — SRI silently fails without it on
  cross-origin requests.
- Local assets served from this origin (`{{ asset('css/custom.css') }}`,
  `{{ asset('js/main.js') }}`) do **not** need SRI.
- After editing, load `/login` and `/admin` with the browser dev-tools network
  tab open: an SRI mismatch shows up as a blocked request + console error.

## Security headers

`app/Http/Middleware/SecurityHeaders.php` (registered globally in
`bootstrap/app.php`) sets on every response:

| Header | Value |
|---|---|
| `X-Content-Type-Options` | `nosniff` |
| `X-Frame-Options` | `SAMEORIGIN` |
| `Referrer-Policy` | `strict-origin-when-cross-origin` |
| `Permissions-Policy` | `geolocation=(), microphone=(), camera=()` |

**Content-Security-Policy is intentionally not set.** The admin layout contains a
large inline `<script>` block (DataTables initialization) that a strict CSP would
break. Follow-up: move the inline block into a compiled asset, then add a CSP.

## API registration endpoint

`POST /api/v1/register` was removed (it was unauthenticated and auto-granted the
admin role). Web registration is likewise disabled in `routes/web.php`. If
registration is ever needed again, it must **not** default new users to role 1
(admin) and must sit behind throttling + verification.

## CSV import

`app/Http/Controllers/Traits/CsvImportTrait.php` uses `openspout/openspout`
(replacing the unmaintained `nuovo/spreadsheet-reader`) and only accepts model
names from the `$allowedImportModels` whitelist (`Responden`, `IotReading`,
`SmReading`). When adding CSV import to a new admin module, add the model name
to that whitelist — arbitrary `App\Models\*` class resolution from request input
is deliberately blocked. Regression coverage: `tests/Feature/CsvImportTest.php`.

## Default credentials

`database/seeders/UsersTableSeeder.php` seeds `admin@admin.com` / `password`.
**Change this account's password immediately on any non-local deployment.**
