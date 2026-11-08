# Portal APK / TWA packages (not linked from the website)

Prepared packaging for **Admin** and **Agent** as Android Trusted Web Activity wrappers around the existing PWAs.

| Portal | Host | Manifest | Package id |
|--------|------|----------|------------|
| Admin | `https://tonkow.com` | `/manifest-admin.webmanifest` | `com.tonkow.toapp.admin` |
| Agent | `https://agto-app.com` | `/manifest-agent.webmanifest` | `com.tonkow.toapp.agent` |

## Build

```bash
chmod +x apk/build-apk.sh
./apk/build-apk.sh admin
./apk/build-apk.sh agent
```

Needs Node.js (`npx`), Java 17+, and Android SDK. The signed APK is written under `apk/admin/` or `apk/agent/` — distribute privately; do **not** add a download button on the web UI.

## Digital Asset Links

After the first release keystore exists:

1. `keytool -list -v -keystore apk/<portal>/android.keystore`
2. Copy the SHA-256 fingerprint into `assetlinks.json.example`
3. Publish as `https://<host>/.well-known/assetlinks.json`
