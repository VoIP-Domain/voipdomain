# VoIP Domain — automated testing toolkit

Scripts for lab/staging smoke tests and hook coverage tracking. See
[../en/TESTING-METHODOLOGY.md](../en/TESTING-METHODOLOGY.md) for the full methodology.

## Quick start

```bash
cd Documentations/testing

cp env.example env.local
# edit env.local — set VD_BASE_URL, VD_USERNAME, VD_PASSWORD

chmod +x *.sh ../scripts/hook-inventory.sh

# Full smoke run (static + API + Gearman inventory)
./run-all.sh

# Optional: sample R0 admin hooks from inventory
VD_RUN_R0_SAMPLE=1 ./run-all.sh
```

## Files

| File | Purpose |
|------|---------|
| `env.example` | Template environment variables |
| `env.local` | Your lab credentials (git-ignored) |
| `hook-inventory.csv` | Generated hook/filter list with risk class |
| `run-all.sh` | Orchestrates all smoke stages |
| `smoke-static.sh` | `php -l`, spool check |
| `smoke-api.sh` | Login, ping, API map, sample hooks |
| `smoke-gearman.sh` | `gearadmin`, optional router job |
| `hook-call.sh` | Call one hook via `/debug/callhook/:Hook` |
| `hook-smoke-r0.sh` | Batch sample of R0 admin hooks |
| `curl-examples.md` | Copy-paste curl commands |
| `postman/voipdomain-lab-smoke.postman_collection.json` | Import into Postman/Insomnia |

## Hook inventory

Generate or refresh the CSV (preserves `tested`, `test_date`, `notes` columns):

```bash
../scripts/hook-inventory.sh
```

CSV columns:

| Column | Description |
|--------|-------------|
| `name` | Hook or filter name |
| `kind` | `hook` or `filter` |
| `layer` | `admin`, `client`, `router`, `monitor`, … |
| `risk` | `R0`–`R4` (auto-classified, review manually) |
| `tested` | `yes` / `no` / `skip` — fill manually |
| `test_date` | ISO date |
| `notes` | Tester notes |
| `source_file` | First registration in codebase |

Mark coverage in a spreadsheet or edit the CSV directly. Re-run the inventory
script after merges — manual columns are merged by `name` + `kind`.

## Call a single hook

```bash
./hook-call.sh debug_ping '{}'
./hook-call.sh ranges_add_validate '{"Number":""}'

# Destructive hooks require explicit opt-in
VD_I_KNOW_THIS_IS_DESTRUCTIVE=1 ./hook-call.sh firmware_cisco_wipe '{}'
```

## Environment variables

| Variable | Default | Description |
|----------|---------|-------------|
| `VD_BASE_URL` | `http://localhost` | Admin interface URL |
| `VD_USERNAME` / `VD_PASSWORD` | — | Session login |
| `VD_TOKEN` | — | Bearer token (skips login) |
| `VD_COOKIE_JAR` | `/tmp/voipdomain-test-cookies.txt` | Session cookies |
| `GEARMAN_HOST` / `GEARMAN_PORT` | `127.0.0.1` / `4730` | Gearman server |
| `VD_SERVER_ID` | — | Scope for `router_reload_cache` |
| `VD_RUN_INFRA_HOOKS` | — | Set to `1` to run R4 infra hooks |
| `VD_RUN_R0_SAMPLE` | — | Set to `1` in `run-all.sh` for R0 batch |
| `VD_R0_SAMPLE_LIMIT` | `5` | Max R0 hooks in batch |

## Safety

- Never point `env.local` at production.
- Do not enable the **debug WebUI module** on production (`/debug/exec_hook` is unauthenticated).
- R3 hooks (`*_wipe`, `*_rebuild`) require `VD_I_KNOW_THIS_IS_DESTRUCTIVE=1` in `hook-call.sh`.
- Auto risk classification is heuristic — review before calling unknown hooks.

## CI integration (example)

```yaml
# .gitlab-ci.yml snippet (lab runner with PHP + checkout only)
test:static:
  script:
    - bash Documentations/testing/smoke-static.sh
```

API smoke requires a running lab stack and secrets injected as CI variables.
