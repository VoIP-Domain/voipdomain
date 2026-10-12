# VoIP Domain — lab curl examples

Replace `https://lab.example` and credentials with your `env.local` values.

## Session login

```bash
curl -sS -c /tmp/vd-cookies.txt -X POST 'https://lab.example/api/session' \
  -H 'Content-Type: application/json' \
  -d '{"Username":"admin","Password":"admin"}'
```

## Bearer token (alternative)

```bash
export VD_TOKEN="your-access-token"
curl -sS -H "Authorization: Bearer $VD_TOKEN" 'https://lab.example/api/debug/ping'
```

## Health and discovery

```bash
# Ping
curl -sS -b /tmp/vd-cookies.txt 'https://lab.example/api/debug/ping'

# API + hook map
curl -sS -b /tmp/vd-cookies.txt 'https://lab.example/api/debug/api/map' | head -c 2000

# Number explain (filter chain)
curl -sS -b /tmp/vd-cookies.txt 'https://lab.example/api/debug/explain/%2B5511999999999'
```

## Hook calls (POST /debug/callhook/:Hook)

```bash
# R0 — read-only
curl -sS -b /tmp/vd-cookies.txt -X POST 'https://lab.example/api/debug/callhook/debug_ping' \
  -H 'Content-Type: application/json' -d '{}'

# R1 — validate (expect 422)
curl -sS -b /tmp/vd-cookies.txt -X POST 'https://lab.example/api/debug/callhook/ranges_add_validate' \
  -H 'Content-Type: application/json' -d '{"Number":""}'

# R4 — router cache (router daemon must be running)
curl -sS -b /tmp/vd-cookies.txt -X POST 'https://lab.example/api/debug/callhook/router_reload_cache' \
  -H 'Content-Type: application/json' -d '{"ServerID":"1"}'
```

## CRUD smoke (tenant tree)

```bash
# List servers
curl -sS -b /tmp/vd-cookies.txt 'https://lab.example/api/servers'

# Create server (adjust payload — see Swagger /api/docs)
curl -sS -b /tmp/vd-cookies.txt -X POST 'https://lab.example/api/servers' \
  -H 'Content-Type: application/json' \
  -d '{
    "Description": "Lab server",
    "Address": "192.168.1.10",
    "Port": 5060
  }'
```

## Gearman (router daemon)

```bash
gearadmin --server=127.0.0.1:4730 --status

echo '{}' | gearman -f router_reload_cache -h 127.0.0.1 -p 4730
```

## OpenAPI / Swagger

Browse interactive docs at: `https://lab.example/api/docs`
