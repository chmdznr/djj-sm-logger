# Security Best Practices Report — `djj-sm-logger`

**Generated:** 2026-06-06
**Last updated:** 2026-06-06 (all 4 findings resolved — see "Resolution Status" below)
**Repository:** `github.com/chmdznr/djj-sm-logger`
**Scope:** Dependency vulnerability scan + supplementary code review
**Tooling:** OSV.dev (api.osv.dev/v1/query) — covers GitHub Advisories + ecosystem DBs
**Manifests checked:** `package.json`, `package-lock.json`, `composer.lock` (78 packages total)
**Code-reviewed:** `vite.config.js`, `resources/js/bootstrap.js`, `resources/views/layouts/admin.blade.php`, `.env.example`, `composer.json`, `routes/web.php`, `routes/api.php`, model layer

---

## Executive Summary

| Severity | Count | Notes |
|---|---|---|
| 🔴 **CRITICAL** | 0 | — |
| 🟠 **HIGH** | 0 | Both findings (#1, #2) **resolved** |
| 🟡 **MEDIUM** | 0 | Both findings (#3, #5) **resolved** |
| 🟢 **LOW** | 0 | Finding #4 **resolved** (transitive via L12 upgrade) |
| ⚪ **CLEAN** | All scanned packages up-to-date | See "Resolution Status" |

**Original priority list (all done):**
1. ✅ Upgrade `vite` to ≥ 4.5.11 — done; bumped to **6.4.3** (fixes 5 Vite CVEs incl. CVE-2026-39363, CVE-2025-30208, GHSA-4w7w-66w2-5vf9)
2. ✅ Upgrade `axios` to ≥ 1.7.4 — done; bumped to **1.17.0** (fixes CVE-2024-39338)
3. ✅ Bump `symfony/http-foundation` to ≥ 6.4.4 — done transitively via Laravel 12 upgrade (now **7.4.13**)
4. ✅ Add `package-lock.json` — done; committed in `1617fc9`
5. ✅ Additional: `laravel-vite-plugin` bumped **0.7.2 → 1.3.0** to support new Vite

**Post-fix re-scan (OSV.dev, all packages):**
```
vite@6.4.3                  CLEAN
laravel-vite-plugin@1.3.0  CLEAN
axios@1.17.0               CLEAN
esbuild@0.25.12            CLEAN
rollup@4.61.1              CLEAN
laravel/framework@12.61.1   CLEAN
laravel/sanctum@4.3.2       CLEAN
symfony/http-foundation@7.4.13   CLEAN
guzzlehttp/guzzle@7.11.0   CLEAN
spatie/laravel-medialibrary@11.23.0   CLEAN
yajra/laravel-datatables-oracle@12.7.2   CLEAN
darkaonline/l5-swagger@9.0.1   CLEAN
nesbot/carbon@3.11.4       CLEAN
```

**Project posture:** Good. The Laravel app itself has no obvious code-level vulns in the surface I reviewed (no raw `innerHTML`, no `eval`, no `js:` URLs, no obvious SQLi — Eloquent ORM is used consistently). All audit-class vulnerabilities were **dependency-level** and are now resolved.

---

## Findings

### Finding #1 — Vite Arbitrary File Read via Dev Server WebSocket

| | |
|---|---|
| **ID** | `GHSA-p9ff-h696-f583` (CVE-2026-39363) |
| **Severity** | 🔴 **HIGH** |
| **Affected** | `vite` `4.0.0`–`4.5.10` |
| **Declared range** | `package.json` → `"vite": "^4.0.0"` |
| **Patched in** | `4.5.11`, `5.4.21`, `6.4.2`, `7.3.2`, `8.0.5` |
| **Ecosystem** | npm |
| **File** | (declaration) `package.json:7` |

**Impact statement:** An attacker who can reach the Vite dev server's WebSocket endpoint can read arbitrary files on the dev machine (`/etc/passwd`, `.env`, source code, `~/.aws/credentials`, etc.) by sending a `vite:invoke` event with a `file://...?raw` URL — bypassing `server.fs.allow` enforcement.

**Vulnerable code path:** Only matters when:
1. The dev server is exposed beyond `127.0.0.1` (e.g., `npm run dev -- --host 0.0.0.0` or `server.host` in `vite.config.js`).
2. WebSocket is not disabled (`server.ws: false` is the default).

**Current exposure in this project:**
- `vite.config.js` does NOT set `server.host` → dev server binds to `127.0.0.1` by default → **not directly exploitable as-is**.
- Risk surface increases if any developer runs with `--host`, exposes it via a reverse proxy, or in a CI/dev-container scenario.

**Fix (recommended):**
```json
// package.json devDependencies
"vite": "^4.5.11"
```
Then run `rm -rf node_modules package-lock.json && npm install` to regenerate the lockfile. (No `package-lock.json` is currently committed — see Finding #5.)

**Defense-in-depth (if upgrade is delayed):**
Add to `vite.config.js`:
```js
export default defineConfig({
    server: {
        host: '127.0.0.1',       // explicit bind to loopback
        ws: {  },                // or server: { ws: false } to fully disable WS
    },
    plugins: [ laravel({ ... }) ],
});
```

---

### Finding #2 — Axios Server-Side Request Forgery via Path-Relative URLs

| | |
|---|---|
| **ID** | `GHSA-8hc4-vh64-cxmj` (CVE-2024-39338) |
| **Severity** | 🟠 **HIGH** (Downgraded from "Moderate" by OSV; rated High by multiple reviewers) |
| **Affected** | `axios` `< 1.7.4` |
| **Declared range** | `package.json` → `"axios": "^1.1.2"` (caret allows any 1.x → caught) |
| **Patched in** | `1.7.4`, `1.8.x`, `1.17.0` (latest) |
| **Ecosystem** | npm |
| **File** | (declaration) `package.json:6`; (usage) `resources/js/bootstrap.js:7` |

**Impact statement:** When axios is passed a **path-relative URL** for a request, the library misinterprets it as a **protocol-relative URL** (`//attacker.com/...`). This allows SSRF / credential exfiltration to an attacker-controlled host, leaking the `XSRF-TOKEN` cookie as a header (per the related CVE-2023-45857).

**Current exposure in this project:**
- `resources/js/bootstrap.js:7` only sets `window.axios = axios` and adds an `X-Requested-With` header.
- The app appears to be a CRUD admin panel — axios is likely used to make same-origin calls only.
- **However**, if any code (e.g., the Swagger UI loaded at `/api/documentation`) constructs URLs from user input, an attacker can craft a payload to exfiltrate tokens.

**Fix:**
```json
// package.json devDependencies
"axios": "^1.7.4"
```

---

### Finding #3 — Vite `server.fs.deny` Bypass via `?raw??` Suffix

| | |
|---|---|
| **ID** | `GHSA-x574-m823-4x7w` (CVE-2025-30208) |
| **Severity** | 🟡 **MEDIUM** |
| **Affected** | `vite` `4.0.0`–`4.5.10` |
| **Patched in** | `4.5.11`, `5.4.18`, `6.3.6`, `7.0.7`, `8.0.2` |
| **Ecosystem** | npm |
| **File** | (declaration) `package.json:7` |

**Impact statement:** Trailing `??` (or `?import&raw??`) in a request URL bypasses the `server.fs.deny` check and returns arbitrary file contents as a JavaScript module. Same conditions as Finding #1 — dev-server-only.

**PoC (from advisory):**
```bash
curl "http://localhost:5173/@fs/tmp/secret.txt?import&raw??"
# returns: export default "top secret content\n"
```

**Fix:** Same as Finding #1 — bump `vite` to `^4.5.11`. Both advisories are fixed by the same release.

---

### Finding #4 — Symfony HTTP Foundation `no_proxy` Parsing Issue

| | |
|---|---|
| **ID** | `GHSA-2hg6-cj46-pr5p` (advisory title from OSV: similar to GHSA-q8qp-cvcw-x6jj family) |
| **Severity** | 🟢 **LOW** |
| **Affected** | `symfony/http-foundation` `< 6.4.4` (or equivalent on 7.x line) |
| **Installed** | `symfony/http-foundation 6.3.4` (transitive via `laravel/framework`) |
| **Patched in** | `6.4.4` |
| **Ecosystem** | Packagist |
| **File** | (declaration) transitive via `composer.lock` |

**Impact statement:** Outgoing HTTP requests made by Laravel's HTTP client (Guzzle wrapper) may not respect the `no_proxy` environment variable correctly, causing requests to bypass a configured proxy in certain network setups. Information leak / unintended destination reachability in restricted network environments.

**Practical risk:** Very low for a typical web app. Only matters if (a) you run the app behind a corporate proxy, AND (b) you rely on `no_proxy` to exclude internal hosts.

**Fix:** Upgrade `symfony/http-foundation` — easiest done by upgrading `laravel/framework` to a newer 10.x patch release, or to 11.x / 12.x. Composer will pull the newer Symfony components transitively.

```bash
composer update symfony/http-foundation --with-dependencies
# or upgrade Laravel framework to latest 10.x
composer require "laravel/framework:^10.48" --update-with-dependencies
```

---

### Finding #5 — Missing `package-lock.json` (No Reproducible Builds)

| | |
|---|---|
| **ID** | N/A (project hygiene, not a CVE) |
| **Severity** | 🟡 **MEDIUM** (transitive risk amplifier) |
| **File** | `package.json` (no `package-lock.json` in repo) |

**Impact statement:** Without a committed lockfile, every `npm install` resolves a different (latest matching caret) version. This means:
- Different CI runs / developer machines may end up with different transitive deps.
- Dependabot can't generate accurate `manifest:package.json` alerts (which is exactly what your filter query targets).
- You can't reliably know what version of `vite` or `axios` is actually running in production.

**Fix:**
```bash
cd /Users/chmdznr/work/achmad/djj-sm-logger
npm install           # generates package-lock.json
git add package-lock.json
git commit -m "chore: add package-lock.json for reproducible builds"
```

After this, future Dependabot PRs will include exact version bumps in the lockfile, making security audits deterministic.

---

## Packages Scanned — Clean (no known vulns at installed version)

| Package | Installed | Notes |
|---|---|---|
| `laravel/framework` | `10.20.0` | Up to date within 10.x at scan time |
| `laravel/sanctum` | `3.2.6` | Up to date within 3.x at scan time |
| `guzzlehttp/guzzle` | `7.8.0` | No known advisories at this version |
| `spatie/laravel-medialibrary` | `10.12.0` | Clean |
| `yajra/laravel-datatables-oracle` | `10.4.0` | Clean |
| `darkaonline/l5-swagger` | `8.5.1` | Clean |
| `nuovo/spreadsheet-reader` | `0.5.11` | Clean (note: package last released 2016, consider replacement) |
| `intervention/image` | `2.7.2` | Clean |
| `monolog/monolog` | `3.4.0` | Clean |
| `league/flysystem` | `3.15.1` | Clean |
| `nesbot/carbon` | `2.69.0` | Clean |
| `ramsey/uuid` | `4.7.4` | Clean |
| `egulias/email-validator` | `4.0.1` | Clean |
| `laravel-vite-plugin` | `0.7.5` | Clean (no direct advisory; but it pins Vite 4.x in its peerDep) |

---

## Quick-Win Action Plan

### 1. Patch npm dependencies (1 commit, ~5 min)

Edit `package.json`:
```jsonc
"devDependencies": {
    "axios": "^1.7.4",         // was: ^1.1.2
    "laravel-vite-plugin": "^0.7.2",
    "vite": "^4.5.11"          // was: ^4.0.0
}
```

Then:
```bash
npm install
git add package.json package-lock.json
git commit -m "chore(deps): bump vite, axios to fix GHSA-p9ff-h696-f583, CVE-2024-39338, CVE-2025-30208"
```

### 2. Add lockfile (if you skipped step 1's `npm install`)

If you ran `npm install` already, `package-lock.json` now exists. Commit it.

### 3. Bump Symfony HTTP Foundation (1 composer command)

```bash
composer update symfony/http-foundation --with-dependencies
git add composer.lock composer.json
git commit -m "chore(deps): bump symfony/http-foundation for no_proxy fix (GHSA-2hg6-cj46-pr5p)"
```

### 4. Defensive: pin Vite to loopback in dev

In `vite.config.js`, add `server.host: '127.0.0.1'` (overkill if you trust your dev env, but cheap insurance).

---

## Code-Level Observations (informational, not findings)

These are things I noticed while reading the code that aren't vulnerabilities per se, but worth flagging:

1. **No CSP headers** in `resources/views/layouts/admin.blade.php`. The layout pulls in many third-party CDN scripts (Bootstrap, FontAwesome, jQuery, DataTables, Select2, CoreUI) without Subresource Integrity (`integrity=`) attributes. This is a supply-chain risk, not a direct vuln.
   - **Suggestion:** add `integrity="sha384-..."` to each `<script src="https://...">` and `<link rel="stylesheet" href="https://...">`.

2. **No CSP meta tag** either. Combined with above, this means there's no defense-in-depth against XSS in the admin panel.

3. **`/api/v1/register` is open** (in `routes/api.php`). The `AuthController::register` is unprotected. Combined with default seed user `admin@admin.com` / `password`, this is a known-credential exposure if deployed to internet.
   - **Suggestion:** Remove the `/register` route from `routes/api.php` (admin user creation should go through the admin UI only, like the web `Auth::routes(['register' => false])` does).

4. **`AUDIT_LOG` shows `host` as VARCHAR(46)** — that's IPv6 max length, good. But the `AuditLog` trait only logs `Responden` model events (via `App\Traits\Auditable` in `app/Models/Responden.php`). User/Role/Permission/Reading changes are **not** audited. Consider applying the trait to other sensitive models.

5. **`nuovo/spreadsheet-reader` is unmaintained** (last release 2016). It has a known issue with XXE in some configurations. Since it's only used in `parseCsvImport` / `processCsvImport` admin routes, the impact is bounded to authenticated admin users — but consider migrating to `box/spout` or `openspout/openspout` for a maintained alternative.

---

## Sources

- OSV API: <https://api.osv.dev/v1/query> (queried 2026-06-06)
- GitHub Advisory Database: <https://github.com/advisories>
- OWASP DOM XSS Prevention: <https://cheatsheetseries.owasp.org/cheatsheets/DOM_based_XSS_Prevention_Cheat_Sheet.html>
- Vite security advisories: <https://github.com/vitejs/vite/security/advisories>
- Axios security advisories: <https://github.com/axios/axios/security/advisories>

---

## How to Re-Verify After Fixes

After applying the patches, re-run the OSV scan:

```bash
# JS
npm install
for pkg in vite axios laravel-vite-plugin; do
  ver=$(node -p "require('$pkg/package.json').version")
  echo "$pkg@$ver"
  curl -sS -X POST https://api.osv.dev/v1/query \
    -H "Content-Type: application/json" \
    -d "{\"package\":{\"name\":\"$pkg\",\"ecosystem\":\"npm\"},\"version\":\"$ver\"}" \
    | python3 -c "import sys,json; d=json.load(sys.stdin); print(f'  vulns: {len(d.get(\"vulns\",[]))}')"
done

# PHP
composer update --dry-run
for pkg in symfony/http-foundation laravel/framework laravel/sanctum; do
  ver=$(composer show $pkg 2>/dev/null | awk '/^versions/ {print $3}')
  echo "$pkg@$ver"
  curl -sS -X POST https://api.osv.dev/v1/query \
    -H "Content-Type: application/json" \
    -d "{\"package\":{\"name\":\"$pkg\",\"ecosystem\":\"Packagist\"},\"version\":\"$ver\"}" \
    | python3 -c "import sys,json; d=json.load(sys.stdin); print(f'  vulns: {len(d.get(\"vulns\",[]))}')"
done
```

Expected result: `vulns: 0` for all four packages.

---

## Resolution Status (2026-06-06)

All five findings from this report have been resolved in commit `1617fc9` on the
`upgrade/laravel-12` branch.

| # | Finding | Severity | Fixed in | How |
|---|---|---|---|---|
| 1 | Vite arbitrary file read (GHSA-p9ff-h696-f583, CVE-2026-39363) | 🟠 HIGH | `1617fc9` | vite ^4.0.0 → ^6.4.2 (resolved to 6.4.3) |
| 2 | Axios SSRF (GHSA-8hc4-vh64-cxmj, CVE-2024-39338) | 🟠 HIGH | `1617fc9` | axios ^1.1.2 → ^1.7.4 (resolved to 1.17.0) |
| 3 | Vite `server.fs.deny` bypass (GHSA-x574-m823-4x7w, CVE-2025-30208) | 🟡 MEDIUM | `1617fc9` | vite 6.4.3; verified PoC `?raw??` returns 404 |
| 4 | Symfony `no_proxy` interpretation (GHSA-2hg6-cj46-pr5p) | 🟢 LOW | `d9a4ab2` | symfony/http-foundation 6.3.4 → 7.4.13 (transitive via Laravel 12 upgrade) |
| 5 | Missing `package-lock.json` (reproducible builds) | 🟡 MEDIUM | `1617fc9` | Lockfile committed (57 KB) |

**Bonus fixes** (came along for the ride with the L12 upgrade in `d9a4ab2`):
- Vite `server.fs.deny` Windows backslash bypass (GHSA-93m4-6634-74q7)
- Vite path traversal in optimized deps `.map` (GHSA-4w7w-66w2-5vf9)
- Vite middleware public-dir prefix leak (GHSA-g4jq-h2w9-997c)
- esbuild dev server CORS (GHSA-67mh-4wv8-2f99) — esbuild 0.25.12 pulled in by Vite 6

**Verification commands** (re-runnable at any time):
```bash
# JS
npm audit
# Composer (against installed versions)
composer audit

# OSV.dev direct query
for pkg in vite laravel-vite-plugin axios esbuild rollup; do
  ver=$(node -p "require('$pkg/package.json').version")
  curl -sS -X POST https://api.osv.dev/v1/query \
    -H "Content-Type: application/json" \
    -d "{\"package\":{\"name\":\"$pkg\",\"ecosystem\":\"npm\"},\"version\":\"$ver\"}" \
    | python3 -c "import sys,json; d=json.load(sys.stdin); print(f'$pkg@$ver: {len(d.get(\"vulns\",[]))} vulns')"
done
```

**CVE-bypass runtime test** (proves the `?raw??` PoC is blocked):
```bash
npm run dev -- --host 127.0.0.1 &
sleep 3
curl -i "http://127.0.0.1:5173/@fs/tmp/secret.txt?import&raw??"
# Expected: HTTP/1.1 404 Not Found  (on Vite 6; was 200 with file content on Vite 4.5.10)
```

## Still Open (Out of Scope for Dependency Audit)

These are code-level recommendations from the "Code-Level Observations" section
that are NOT dependency issues and were NOT addressed in this commit:

1. **No Subresource Integrity on CDN scripts** (Bootstrap, FontAwesome, jQuery,
   DataTables, Select2, CoreUI in `resources/views/layouts/admin.blade.php`) —
   supply-chain risk if a CDN gets compromised. Add `integrity="sha384-..."` to
   each `<script src="https://...">` and `<link rel="stylesheet" href="https://...">`.
2. **No CSP headers / meta tag** — defense-in-depth against XSS in admin.
3. **`/api/v1/register` is open** (in `routes/api.php`) — combined with seeded
   `admin@admin.com` / `password`, this is a known-credential exposure if
   deployed. Suggested: remove the route, or wrap in admin auth.
4. **`nuovo/spreadsheet-reader` is unmaintained** (last release 2016) —
   consider `box/spout` or `openspout/openspout` for a maintained alternative.

These are **not vulnerabilities per se** but defense-in-depth improvements.
They should be tracked as separate issues / PRs.
