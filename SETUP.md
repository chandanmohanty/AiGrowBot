# AI Grow Bot — Laravel Setup (XAMPP + MySQL)

## 0. Production deployment — Web Setup Wizard (recommended)

A one-time web-based installer handles everything: environment checks, database configuration, site settings, admin account, migrations and seeding.

```bash
# 1. Upload / clone the project onto the server
cd /var/www/aigrowbot
composer install --no-dev --optimize-autoloader

# 2. Ensure write permissions
chmod -R ug+rw storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
# Also ensure the directory holding .env is writable (project root) for first run

# 3. Point your web server's document root at ./public
#    (Apache / Nginx / Caddy — standard Laravel setup)

# 4. Visit https://your-domain.com/install in a browser
#    The wizard walks through:
#       - Requirements       (PHP version + extensions + permissions)
#       - Database           (MySQL / MariaDB host, port, user, password — with "Test connection")
#       - Site settings      (name, URL, contact email/phone, timezone)
#       - Admin account      (name, email, password)
#       - Install            (writes .env, generates APP_KEY, migrates, seeds, creates admin)

# 5. Once finished, storage/app/installed.lock is created.
#    The /install routes 404 on subsequent visits; all traffic redirects to the site.
```

**Security notes:**
- The installer only runs while `storage/app/installed.lock` is absent. Delete that file to re-run (also clears the wizard's state).
- All traffic is redirected to `/install` until setup completes, so nothing is exposed.
- After install, `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_DRIVER=file`, `CACHE_STORE=file`.

## 1. Prerequisites (manual CLI path — legacy)
- XAMPP with Apache + MySQL running
- PHP 8.2+ (bundled with XAMPP or separate)
- Composer 2.x

## 2. Database
1. Open **phpMyAdmin** at http://localhost/phpmyadmin
2. Create database `aigrowbot` (utf8mb4_unicode_ci)
3. The `.env` is pre-configured for `root` / no password. Adjust if yours differs.

## 3. First-run install
```bash
cd D:\ClaudeProjects\AIGrowBot\app-laravel

composer install                      # if cloning fresh
php artisan key:generate              # if APP_KEY empty
php artisan migrate:fresh --seed      # creates tables + seeds admin/roles/SEO/settings
php artisan storage:link              # for uploaded cover images
```

## 4. Admin login
- URL: http://localhost:8000/admin
- Email: `admin@aigrowbot.local`
- Password: `ChangeMe!123`  ← **change immediately** from Users → Edit

## 5. Run
**Option A — Laravel dev server (fastest path):**
```bash
php artisan serve
```
Visit http://localhost:8000

**Option B — XAMPP Apache:**
1. Copy or symlink the project into `C:\xampp\htdocs\aigrowbot`:
   ```bash
   mklink /D C:\xampp\htdocs\aigrowbot D:\ClaudeProjects\AIGrowBot\app-laravel\public
   ```
   (run as admin; only the `public/` directory goes into htdocs)
2. Add to `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
   ```
   <VirtualHost *:80>
       ServerName aigrowbot.local
       DocumentRoot "D:/ClaudeProjects/AIGrowBot/app-laravel/public"
       <Directory "D:/ClaudeProjects/AIGrowBot/app-laravel/public">
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
3. Add `127.0.0.1 aigrowbot.local` to `C:\Windows\System32\drivers\etc\hosts`
4. Restart Apache. Visit http://aigrowbot.local

Set `APP_URL=http://aigrowbot.local` in `.env` after switching.

## 6. Production hardening
```bash
# Switch env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
SESSION_SECURE_COOKIE=true

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
composer install --no-dev --optimize-autoloader
```

## 7. What's included

### Admin panel (`/admin`)
- **Dashboard** — stats for posts, users, messages
- **Blog Posts** — full CRUD with rich-text editor (TinyMCE), cover image, category, tags, per-post SEO overrides, draft/published, scheduled publishing
- **Categories** / **Tags** — CRUD
- **Users** — create/edit/delete users, assign roles and permissions
- **SEO** — per-route (home, blog.index, contact, etc.) manage title, description, OG image, canonical, noindex
- **Settings** — site name, contact email/phone/WhatsApp, social links, Google Analytics, Google verification, Facebook Pixel
- **Messages** — view contact form submissions

### Roles & permissions
- **Admin** — all permissions
- **Editor** — blog + categories + tags + SEO
- **Author** — create/edit own posts only (cannot publish)

Create users from admin panel and assign roles.

### SEO
- Dynamic `<title>`, meta description, keywords, Open Graph, Twitter cards per page (driven by `seo_meta` table + per-post overrides)
- JSON-LD schema: Organization (site-wide), WebSite with SearchAction (home), BlogPosting (blog post), FAQPage (home FAQ)
- `/sitemap.xml` auto-generated from published posts (cached 1h)
- `/robots.txt` with admin/login disallowed
- Canonical URLs
- Configurable noindex per page / per post
- Google Site Verification meta tag (from Settings)
- Google Analytics GA4 auto-injected (from Settings)

### Performance
- Config/route/view caching
- HTTP cache headers on home (10 min) and blog (2 min index, 5 min show)
- Sitemap cached 1 hour
- Settings cached forever (auto-flushed on save)
- SEO meta cached 10 min
- Images served with `loading="lazy"` + `decoding="async"`
- Fonts preconnected
- CSS/JS served with filemtime cache-busting

### Security
- Bcrypt passwords (12 rounds)
- CSRF on all forms
- Rate limiting: 10/min on login + contact
- Permission checks via Spatie + Laravel Gate
- Admin panel requires `access admin` permission
- Input validation on every controller
- HTML sanitization on blog body via HTMLPurifier
- Honeypot on contact form
- Security headers: X-Content-Type-Options, X-Frame-Options, Referrer-Policy, Permissions-Policy, XSS-Protection, HSTS (HTTPS only), CSP
- Session cookie `HttpOnly`, `SameSite=Lax`

## 8. File layout

```
app-laravel/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           ← admin CRUD
│   │   ├── Auth/            ← login/logout
│   │   ├── BlogController.php
│   │   ├── ContactController.php
│   │   ├── HomeController.php
│   │   └── SitemapController.php
│   ├── Http/Middleware/SecurityHeaders.php
│   ├── Models/              ← User, Post, Category, Tag, SeoMeta, Setting, ContactMessage
│   └── Services/SeoService.php
├── config/seo.php           ← site-wide SEO defaults
├── database/
│   ├── migrations/          ← schema
│   └── seeders/             ← roles, admin, SEO, settings
├── public/
│   ├── styles.css           ← your original CSS (untouched for UI parity)
│   ├── script.js            ← your original JS (untouched)
│   ├── admin.css
│   └── img/                 ← your image assets
├── resources/views/
│   ├── layouts/{app,admin}.blade.php
│   ├── partials/{head,navbar,footer}.blade.php
│   ├── home/                ← hero, features, pricing, etc. (1-1 from index.html)
│   ├── home.blade.php       ← includes all home partials
│   ├── blog/{index,show}.blade.php
│   ├── admin/               ← admin views
│   ├── auth/login.blade.php
│   └── pages/{privacy,terms,refund,security}.blade.php
└── routes/web.php
```

## 9. Creating a blog post

1. Log into `/admin`
2. Click **Blog Posts → New Post**
3. Fill title, body (TinyMCE), optional excerpt + cover image
4. Set SEO overrides if needed
5. Save as **Draft** or **Published**
6. Post appears on `/blog` and auto-added to `/sitemap.xml`

## 10. Troubleshooting

- **500 error after editing code** — run `php artisan config:clear route:clear view:clear`
- **Permission denied on admin** — user needs a role with `access admin`
- **Images not displaying** — run `php artisan storage:link`
- **MySQL connection failed** — verify XAMPP MySQL is running, DB `aigrowbot` exists
