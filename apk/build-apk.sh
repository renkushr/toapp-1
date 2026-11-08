#!/usr/bin/env bash
# Build Trusted Web Activity APKs for Admin / Agent portals.
# Requires: Node.js, Java 17+, Android SDK cmdline-tools.
# Output lands in apk/<portal>/app-release-signed.apk (never linked from the website).
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
PORTAL="${1:-}"

usage() {
  echo "Usage: $0 <admin|agent>"
  echo "Example: $0 admin"
  exit 1
}

[[ "$PORTAL" == "admin" || "$PORTAL" == "agent" ]] || usage

DIR="$ROOT/apk/$PORTAL"
MANIFEST="$DIR/twa-manifest.json"
[[ -f "$MANIFEST" ]] || { echo "Missing $MANIFEST"; exit 1; }

if ! command -v npx >/dev/null 2>&1; then
  echo "npx is required (Node.js)."
  exit 1
fi

cd "$DIR"

if [[ ! -d android ]]; then
  echo "==> Initializing Bubblewrap project for $PORTAL"
  npx --yes @bubblewrap/cli init --manifest "$MANIFEST" --directory android
fi

echo "==> Building APK for $PORTAL"
npx --yes @bubblewrap/cli build --directory android

SIGNED="$(find android -name 'app-release-signed.apk' -o -name 'app-release-unsigned.apk' 2>/dev/null | head -1 || true)"
if [[ -n "$SIGNED" ]]; then
  cp -f "$SIGNED" "$DIR/$(basename "$SIGNED")"
  echo "==> APK ready: $DIR/$(basename "$SIGNED")"
  echo "Deploy assetlinks after signing:"
  echo "  1) keytool -list -v -keystore android.keystore"
  echo "  2) fill apk/$PORTAL/assetlinks.json.example → public .well-known/assetlinks.json"
else
  echo "Build finished. Look under apk/$PORTAL/android for the APK artifact."
fi
