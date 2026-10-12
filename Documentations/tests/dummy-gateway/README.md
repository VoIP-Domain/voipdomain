# Dummy Gateway for Testing

A simple Asterisk gateway configuration for testing outbound calls without requiring external infrastructure.

## Purpose

This dummy gateway is useful for:

- **Development**: Test call routing and dialplan logic without real PSTN/SIP providers
- **CI/CD**: Automated testing of call flows
- **Debugging**: Isolate issues by eliminating external dependencies
- **Training**: Learn VoIP Domain without incurring call costs

## Behavior

When a call is placed to this gateway:

1. The gateway sends `Ringing` signal
2. Waits 5 seconds (simulating ring time)
3. Answers the call
4. Plays the echo test announcement
5. Runs echo test (caller hears their own voice with ~1s delay)
6. Plays goodbye message
7. Hangs up

## Installation

### 1. Copy configuration files

```bash
cp pjsip-dummygw.conf /etc/asterisk/voipdomain/configs/
cp dialplan-dummygw.conf /etc/asterisk/voipdomain/configs/
```

### 2. Verify bootstrap includes

Ensure your PJSIP bootstrap includes config files:

```ini
; In /etc/asterisk/voipdomain/bootstrap/pjsip.conf
#include voipdomain/configs/pjsip-*.conf
```

Ensure your dialplan bootstrap includes config files:

```ini
; In /etc/asterisk/voipdomain/bootstrap/extensions.conf
#include voipdomain/configs/dialplan-*.conf
```

### 3. Reload Asterisk

```bash
asterisk -rx "pjsip reload"
asterisk -rx "dialplan reload"
```

### 4. Verify configuration

```bash
# Check endpoint is loaded
asterisk -rx "pjsip show endpoint dummygw"

# Check dialplan context
asterisk -rx "dialplan show dummygw-incoming"
```

## Usage

### In VoIP Domain

Create a gateway in the administrative interface pointing to `dummygw` or configure a route to use this gateway for testing.

### Direct dialplan testing

Add a test extension to your dialplan:

```ini
[your-context]
exten => 9999,1,NoOp(Testing dummy gateway)
 same => n,Dial(PJSIP/${EXTEN}@dummygw,60)
 same => n,Hangup()
```

### From Asterisk CLI

```bash
asterisk -rx "channel originate PJSIP/100 extension 1234@dummygw-incoming"
```

## Files

| File | Description |
|------|-------------|
| `pjsip-dummygw.conf` | PJSIP endpoint, AOR and identify configuration |
| `dialplan-dummygw.conf` | Dialplan context that handles incoming calls |

## Customization

### Change ring delay

Edit `dialplan-dummygw.conf` and modify the `Wait(5)` line:

```ini
same => n,Wait(10)  ; Wait 10 seconds instead of 5
```

### Add custom audio

Replace `Playback(demo-echotest)` with your own audio file:

```ini
same => n,Playback(custom/your-audio)
```

### Simulate busy/congestion

Create alternative contexts for testing different scenarios:

```ini
[dummygw-busy]
exten => _.,1,Busy(5)

[dummygw-congestion]
exten => _.,1,Congestion(5)

[dummygw-noanswer]
exten => _.,1,Ringing()
 same => n,Wait(60)
 same => n,Hangup()
```

## Troubleshooting

### Gateway not found

```bash
# Check if endpoint exists
asterisk -rx "pjsip show endpoints" | grep dummygw

# If not, check config syntax
asterisk -rx "config reload res_pjsip.so"
```

### Calls not routing

```bash
# Enable verbose logging
asterisk -rx "core set verbose 5"

# Check dialplan
asterisk -rx "dialplan show dummygw-incoming"
```

### No audio

Ensure the demo sounds are installed:

```bash
# Fedora/RHEL
dnf install asterisk-sounds-core-en-ulaw asterisk-sounds-core-en-alaw
```
