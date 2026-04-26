# AI Grow Bot — convenience targets
#
# Run on local dev box (PowerShell users: install GNU Make via choco/scoop, or
# just copy the underlying commands).

.PHONY: help install dev fresh test serve push deploy logs cache-clear

help:                       ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN{FS=":.*?## "}{printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

install:                    ## Local: composer + npm install
	composer install
	@if [ -f package.json ]; then npm install; fi

dev:                        ## Local: vite dev server (front-end)
	npm run dev

fresh:                      ## Local: drop & re-seed DB
	php artisan migrate:fresh --seed
	rm -f storage/app/installed.lock

test:                       ## Local: phpunit
	php artisan test

serve:                      ## Local: built-in server on :8000
	php artisan serve

push:                       ## Stage, commit (msg=...), push to origin
	git add -A
	@if [ -z "$(msg)" ]; then echo "Usage: make push msg=\"your message\""; exit 1; fi
	git commit -m "$(msg)"
	git push

deploy:                     ## Trigger production deploy via SSH
	@if [ -z "$(SSH_HOST)" ]; then echo "Set SSH_HOST=user@host (e.g. SSH_HOST=root@72.61.232.244)"; exit 1; fi
	ssh $(SSH_HOST) "cd /home/aigrowbot_admin/htdocs/aigrowbot.com && bash deploy.sh"

logs:                       ## Tail Laravel log
	tail -f storage/logs/laravel.log

cache-clear:                ## Clear all Laravel caches
	php artisan optimize:clear
