#!/usr/bin/env bash
set -euo pipefail

SERVER="${TOAPP_SERVER:-root@152.42.166.107}"
REMOTE_DIR="${TOAPP_REMOTE_DIR:-/opt/toapp}"
LOCAL_DIR="$(cd "$(dirname "$0")/.." && pwd)"
SSH_KEY="${TOAPP_SSH_KEY:-$HOME/.ssh/id_ed25519}"

SSH_OPTS=(-i "${SSH_KEY}" -o StrictHostKeyChecking=accept-new)

echo "==> Syncing code to ${SERVER}:${REMOTE_DIR}"
rsync -avz --delete \
  -e "ssh ${SSH_OPTS[*]}" \
  --exclude '.git' \
  --exclude '.env' \
  --exclude '.env.production' \
  --exclude 'core/vendor' \
  --exclude 'core/node_modules' \
  --exclude 'core/storage/logs/*' \
  --exclude 'core/storage/framework/cache/*' \
  --exclude 'core/storage/framework/sessions/*' \
  --exclude 'core/storage/framework/views/*' \
  --exclude 'core/bootstrap/cache/*' \
  "${LOCAL_DIR}/" "${SERVER}:${REMOTE_DIR}/"

echo "==> Building and restarting containers"
ssh "${SSH_OPTS[@]}" "${SERVER}" "REMOTE_DIR=${REMOTE_DIR} bash -s" <<'EOF'
set -euo pipefail
cd "${REMOTE_DIR}"

if [[ ! -f .env.production ]]; then
  echo "ERROR: .env.production not found on server."
  exit 1
fi

docker compose --env-file .env.production -f docker-compose.prod.yml up -d --build

echo "==> Running migrations (Toapp only)"
docker compose --env-file .env.production -f docker-compose.prod.yml exec -T app bash -lc '
cd /var/www/html/core
for f in 2026_07_05_000001_create_agents_system 2026_07_06_000001_create_third_party_insurance_system 2026_07_06_120000_add_agent_chat_attachments 2026_07_07_000001_add_payout_settings_to_users_table 2026_07_17_000001_add_fee_percent_to_agents 2026_07_20_000001_add_support_contact_to_general_settings 2026_07_21_000001_add_payment_and_coverage_to_insurance_applications 2026_07_22_000001_add_coverage_length_to_insurance_applications 2026_07_25_000001_add_living_benefits_to_insurance_applications 2026_08_20_000001_add_user_approval_setting_to_general_settings 2026_09_06_000001_add_paypal_phone_to_users_table 2026_09_07_000001_seed_portal_languages 2026_09_08_000001_create_cms_pages_table 2026_09_08_120000_update_toapp_terms_of_service 2026_09_08_180000_update_support_contact_and_terms_email 2026_09_21_000001_normalize_toapp_brand_casing 2026_09_21_120000_force_toapp_brand_casing; do
  php artisan migrate --force --path=database/migrations/${f}.php 2>/dev/null || true
done
mv -f /var/www/html/check-insure/index.html /var/www/html/check-insure/index.html.bak 2>/dev/null || true
chmod -R a+rX /var/www/html/check-insure
'

echo "==> Clearing and warming caches"
docker compose --env-file .env.production -f docker-compose.prod.yml exec -T app \
  php /var/www/html/core/artisan optimize:clear
docker compose --env-file .env.production -f docker-compose.prod.yml exec -T app \
  php /var/www/html/core/artisan config:cache
docker compose --env-file .env.production -f docker-compose.prod.yml exec -T app \
  php /var/www/html/core/artisan view:cache

docker compose --env-file .env.production -f docker-compose.prod.yml ps
EOF

echo "==> Deploy complete: http://152.42.166.107"
