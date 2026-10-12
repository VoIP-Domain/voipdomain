# VoIP Domain — Segurança

Orientações de segurança operacional para administradores. English: [../en/SECURITY.md](../en/SECURITY.md).

---

## 1. Escopo

O VoIP Domain inclui um **servidor de administração** (interface web, banco central, router), **nós Asterisk** (telefonia, daemons client/monitor/fastagi, banco local `vdclient` para plugins) e o **Gearman** como barramento de jobs. FastAGI lê dados de gateways de arquivos JSON locais. Aplique as seções abaixo a todos os hosts da implantação.

Relacionado: [INSTALL.md](INSTALL.md) · [ARQUITETURA.md](ARQUITETURA.md)

---

## 2. Servidor de administração

| Área | Recomendação |
|------|----------------|
| **HTTPS** | Termine TLS no NGiNX. Não exponha a interface admin em HTTP simples em produção. |
| **Credenciais padrão** | O instalador web cria o super-admin `admin` / `admin`. Altere a senha imediatamente após o primeiro login. |
| **Arquivos de configuração** | `/etc/voipdomain/` contém credenciais do banco, Gearman e a chave do certificado master. Restrinja a root e aos usuários de daemon que precisam ler (`nginx`, usuário do router). |
| **Certificado master** | `/etc/voipdomain/master-certificate.key` criptografa payloads Gearman. Faça backup seguro; perda impede descriptografar jobs em trânsito. Modo `0600 root:root`; o router lê as chaves dos tenants a partir do banco de dados. |
| **Endpoints de debug** | Desative `general.debug = true` em produção. Rotas `/debug/*` expõem mapas internos de hooks e API. |
| **PHP-FPM** | Execute o pool VoIP Domain com usuário dedicado (`nginx` nos layouts RPM). Não compartilhe o pool com outros sites. |
| **Atualizações** | Mantenha SO, NGiNX, PHP e MariaDB atualizados. Prefira RPMs de releases tagueados em vez de copiar árvores ao vivo. |

---

## 3. Rede e firewall

Exponha apenas o necessário por função.

| Serviço | Porta típica | Expor para |
|---------|--------------|------------|
| Interface admin (HTTPS) | 443 | Administradores / VPN |
| Gearman (`gearmand`) | 4730 | Servidor admin ↔ nós Asterisk apenas |
| MariaDB | 3306 | Localhost ou rede privada entre hosts conhecidos |
| SIP Asterisk (PJSIP) | UDP/TCP 5060, TLS 5061 | Operadoras e telefones internos — não a Internet aberta |
| RTP Asterisk | UDP 10000–20000 (seu intervalo) | Mesmos peers do SIP |
| AMI (daemon monitor) | 5038 | Localhost no nó Asterisk |

- Coloque Gearman e MariaDB em redes privadas; nunca publique em interface pública.
- Prefira VPN site-a-site ou SBC para telefones remotos em vez de encaminhar SIP direto ao Asterisk.

---

## 4. MariaDB

- Use senhas fortes para as contas `vd` (admin) e `vdclient` (nó) criadas pelo instalador.
- Vincule o MariaDB a endereços internos quando houver acesso remoto; restrinja com firewall e listas de host em `GRANT`.
- Criptografe backups em repouso; contêm CDR, metadados de credenciais e configuração de tenants.

---

## 5. Asterisk e telefonia

Recomendações para cada nó Asterisk gerenciado pelo VoIP Domain.

### 5.1 Exposição

- **Evite** expor o Asterisk diretamente à Internet.
- **Nunca** registre telefones IP com IP público sem hardening, patches e credenciais únicas e fortes. Senhas padrão de fabricante são vetor comum de invasão.
- Se SIP voltado à Internet for inevitável:
  - Use senhas fortes (mínimo 8 caracteres; maiúsculas, minúsculas, dígitos, símbolos).
  - Rotacione credenciais a cada 2–3 meses.
  - Prefira **TLS + SRTP**; desabilite SIP UDP/TCP simples quando possível.
  - Bloqueie ou limite chamadas para destinos caros ou incomuns (LCR e dialplan).

### 5.2 Endurecimento SIP

Em PJSIP ou `sip.conf` legado (`[general]`):

```ini
alwaysauthreject = yes
```

O Asterisk responde com desafio de autenticação em vez de “404 Not Found” para peers desconhecidos, reduzindo enumeração de usuários.

### 5.3 Mitigação de força bruta

Use **Fail2Ban** (ou equivalente) nos logs Asterisk/PJSIP para banir origens com falhas repetidas de autenticação.

### 5.4 Banco local do nó

O banco `vdclient` em cada nó armazena billing e dados de plugins quando o link com o admin cai. Proteja-o como o banco central `vd`.

---

## 6. Daemons e jobs Gearman

- Daemons client registram workers `vd_client_{serverid}_task`. Nós standby com `backup = true` também registram `vd_backup_server_{serverid}_task`. Comprometimento de um nó permite executar jobs daquele server ID — isole redes e restrinja SSH.
- Payloads são criptografados com o certificado master; trate o Gearman mesmo assim como segmento confiável (VLAN privada).
- Após mudanças em plugins ou módulos, recarregue daemons (`voipdomain-daemon-reload@*.path` em instalações RPM).

---

## 7. Checklist (produção)

- [ ] HTTPS na interface admin; HSTS quando aplicável
- [ ] Senha padrão `admin` alterada
- [ ] Permissões de `/etc/voipdomain/` revisadas
- [ ] Gearman e MariaDB inacessíveis da Internet
- [ ] SIP/RTP Asterisk restritos a peers conhecidos
- [ ] TLS/SRTP forçados para endpoints remotos
- [ ] `alwaysauthreject = yes` na pilha SIP
- [ ] Fail2Ban ou equivalente ativo
- [ ] Modo debug desligado; `/debug/*` não usado em produção
- [ ] Atualizações de SO e pacotes agendadas

---

## Veja também

- [INSTALL.md](INSTALL.md) — implantação e endurecimento na instalação
- [../testing/README.md](../testing/README.md) — validação em laboratório (não substitui hardening de produção)
