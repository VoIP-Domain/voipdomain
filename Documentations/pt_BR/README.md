# VoIP Domain — Documentação (Português Brasileiro)

Índice central da documentação. English: [../README.md](../README.md).

---

## Por onde começar

| Público | Documento |
|---------|-----------|
| **Primeiro contato / lab** | [QUICK-START.md](QUICK-START.md) ← **Comece aqui!** |
| **Desenvolvedores (referência completa)** | [MANUAL-DO-DESENVOLVEDOR.md](MANUAL-DO-DESENVOLVEDOR.md) |
| **Operadores / instaladores** | [INSTALL.md](INSTALL.md) · [PLANEJAMENTO-DE-IMPLANTACAO.md](PLANEJAMENTO-DE-IMPLANTACAO.md) |
| **Visão geral da arquitetura** | [ARQUITETURA.md](ARQUITETURA.md) |
| **Utilizadores / admins (FAQ)** | [FAQ.md](FAQ.md) |

---

## Documentação para desenvolvedores

| Tópico | Português |
|--------|-----------|
| Manual completo do desenvolvedor | [MANUAL-DO-DESENVOLVEDOR.md](MANUAL-DO-DESENVOLVEDOR.md) |
| Arquitetura e fluxos de dados | [ARQUITETURA.md](ARQUITETURA.md) |
| Metodologia de testes | [METODOLOGIA-DE-TESTES.md](METODOLOGIA-DE-TESTES.md) |
| Recursos implementados recentemente | [RECURSOS-IMPLEMENTADOS.md](RECURSOS-IMPLEMENTADOS.md) |
| Guia do desenvolvedor de plugins | [PLUGINS.md](PLUGINS.md) |
| Plugin de exemplo | [../../Plugins/sampleitems/README.md](../../Plugins/sampleitems/README.md) |

---

## Instalação e implantação

| Tópico | Documento |
|-------|----------|
| **Início rápido (30 min)** | [QUICK-START.md](QUICK-START.md) |
| Guia de instalação completo | [INSTALL.md](INSTALL.md) |
| Planejamento de implantação | [PLANEJAMENTO-DE-IMPLANTACAO.md](PLANEJAMENTO-DE-IMPLANTACAO.md) |
| Dialplan Asterisk | [DIALPLAN.md](DIALPLAN.md) |
| Empacotamento RPM (RHEL) | [../rpm/README.md](../rpm/README.md) |
| Exemplo NGiNX | [../etc/nginx/README.md](../etc/nginx/README.md) |
| Pool PHP-FPM | [../etc/php-fpm.d/README.md](../etc/php-fpm.d/README.md) |
| systemd | [../systemd/](../systemd/) |
| Configs de exemplo | [../etc/voipdomain/](../etc/voipdomain/) |

---

## Segurança e operações

| Tópico | Documento |
|-------|----------|
| Guia de segurança | [SEGURANCA.md](SEGURANCA.md) |
| Scripts de teste | [../testing/README.md](../testing/README.md) |

---

## Mapa do repositório

```
Documentations/
├── README.md              ← índice em inglês
├── pt_BR/                 ← esta pasta (documentação PT-BR)
├── en/                    ← documentação em inglês
├── etc/                   ← configs de exemplo
├── rpm/                   ← spec RPM
├── systemd/               ← unit files
├── scripts/               ← scripts de manutenção
└── testing/               ← scripts de smoke-test
```
