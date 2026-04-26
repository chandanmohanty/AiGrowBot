#!/usr/bin/env bash
#
# deploy.sh — Pull latest, install deps, migrate, optimize.
# Safe to re-run. Run from the project root on the server.
#
# Usage (as root):
#     bash deploy.sh
#
# Usage (as the site user already):
#     ./deploy.sh
#
# Optional flags:
#     --no-pull      Skip `git pull` (use whatever is checked out)
#     --skip-migrate Skip database migrations
#     --skip-build   Skip npm build (front-end assets)
#     --fresh        Re-build all Laravel caches from scratch
#     --maintenance  Wrap the deploy in `php artisan down/up`

set -euo pipefail

# ───────────── Config ─────────────
SITE_USER="${SITE_USER:-aigrowbot_admin}"
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ARTISAN="php artisan"

# ───────────── Flags ─────────────
DO_PULL=1
DO_MIGRATE=1
DO_BUILD=0
FRESH=0
MAINTENANCE=0

for arg in "$@"; do
    case "$arg" in
        --no-pull)      DO_PULL=0 ;;
        --skip-migrate) DO_MIGRATE=0 ;;
        --skip-build)   DO_BUILD=0 ;;
        --build)        DO_BUILD=1 ;;
        --fresh)        FRESH=1 ;;
        --maintenance)  MAINTENANCE=1 ;;
        -h|--help)
            sed -n '2,20p' "$0"
            exit 0
            ;;
        *)
            echo "Unknown flag: $arg" >&2
            exit 2
            ;;
    esac
done

# ───────────── Helpers ─────────────
say()   { printf "\n\033[1;36m▶ %s\033[0m\n" "$*"; }
ok()    { printf "\033[1;32m✓ %s\033[0m\n" "$*"; }
warn()  { printf "\033[1;33m! %s\033[0m\n" "$*"; }
die()   { printf "\033[1;31m✗ %s\033[0m\n" "$*" >&2; exit 1; }

# Re-exec ourselves as the site user if we were launched as root
if [ "$(id -u)" -eq 0 ]; then
    if id -u "$SITE_USER" >/dev/null 2>&1; then
        say "Re-running as $SITE_USER (script was started as root)"
        exec sudo -u "$SITE_USER" -H bash "$0" "$@"
    else
        die "SITE_USER='$SITE_USER' does not exist on this server. Set SITE_USER=<your-user> and re-run."
    fi
fi

cd "$PROJECT_DIR"

# ───────────── Sanity ─────────────
say "Sanity checks"
[ -f composer.json ] || die "composer.json not found in $PROJECT_DIR — wrong directory?"
[ -f artisan ]       || die "artisan not found in $PROJECT_DIR — wrong directory?"
command -v php       >/dev/null || die "php not found on PATH"
command -v composer  >/dev/null || die "composer not found on PATH"
ok "Project root looks valid ($(php -r 'echo PHP_VERSION;'))"

# ───────────── Maintenance mode (optional) ─────────────
if [ "$MAINTENANCE" -eq 1 ] && [ -f .env ]; then
    say "Enabling maintenance mode"
    $ARTISAN down --render="errors::503" || warn "Could not enable maintenance mode (continuing)"
fi
trap '[ "$MAINTENANCE" -eq 1 ] && $ARTISAN up || true' EXIT

# ───────────── Pull ─────────────
if [ "$DO_PULL" -eq 1 ] && [ -d .git ]; then
    say "Pulling latest from $(git rev-parse --abbrev-ref HEAD)"
    BEFORE=$(git rev-parse --short HEAD)
    git fetch --quiet --prune
    git pull --ff-only
    AFTER=$(git rev-parse --short HEAD)
    if [ "$BEFORE" = "$AFTER" ]; then
        ok "Already up to date ($AFTER)"
    else
        ok "Updated $BEFORE → $AFTER"
    fi
else
    warn "Skipping git pull"
fi

# ───────────── Composer ─────────────
say "Installing composer dependencies (production)"
composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist \
    --no-progress
ok "Composer install complete"

# ───────────── .env / APP_KEY ─────────────
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        say "No .env found — bootstrapping from .env.example"
        cp .env.example .env
        $ARTISAN key:generate --force
        ok ".env created and APP_KEY generated"
        warn "Visit /install to complete setup, or edit .env manually before running again."
    else
        die "No .env and no .env.example to bootstrap from."
    fi
fi

# ───────────── Permissions (writable dirs) ─────────────
say "Ensuring writable dirs"
chmod -R u+rwX,g+rwX storage bootstrap/cache 2>/dev/null || true
ok "storage/ and bootstrap/cache/ are writable"

# ───────────── Front-end build (optional) ─────────────
if [ "$DO_BUILD" -eq 1 ]; then
    if [ -f package.json ] && command -v npm >/dev/null; then
        say "Building front-end assets (npm ci && npm run build)"
        npm ci --silent
        npm run build
        ok "Assets built"
    else
        warn "Skipping npm build (no package.json or npm not on PATH)"
    fi
fi

# ───────────── Migrations ─────────────
if [ "$DO_MIGRATE" -eq 1 ]; then
    say "Running database migrations"
    $ARTISAN migrate --force --no-interaction
    ok "Migrations complete"
else
    warn "Skipping migrations"
fi

# ───────────── Storage symlink ─────────────
if [ ! -L public/storage ]; then
    say "Linking public/storage → storage/app/public"
    $ARTISAN storage:link || warn "storage:link failed (continuing)"
fi

# ───────────── Caches ─────────────
say "Rebuilding Laravel caches"
$ARTISAN config:clear
$ARTISAN route:clear
$ARTISAN view:clear
$ARTISAN cache:clear || true

if [ "$FRESH" -eq 1 ]; then
    $ARTISAN optimize:clear
fi

$ARTISAN config:cache
$ARTISAN route:cache
$ARTISAN view:cache
$ARTISAN event:cache 2>/dev/null || true
ok "Caches rebuilt"

# ───────────── Restart PHP-FPM (best-effort) ─────────────
if command -v sudo >/dev/null && sudo -n true 2>/dev/null; then
    PHP_VER=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
    if sudo -n systemctl is-active --quiet "php${PHP_VER}-fpm" 2>/dev/null; then
        say "Reloading php${PHP_VER}-fpm"
        sudo -n systemctl reload "php${PHP_VER}-fpm" && ok "PHP-FPM reloaded"
    fi
fi

# ───────────── Done ─────────────
say "Deploy finished at $(date '+%Y-%m-%d %H:%M:%S')"
ok  "Live commit: $(git rev-parse --short HEAD 2>/dev/null || echo 'n/a')"
ok  "App URL:     $(grep ^APP_URL .env | cut -d= -f2)"
