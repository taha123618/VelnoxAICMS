# VelnoxAICMS

> **Next-Generation AI-Native Modular CMS & Visual Drag-and-Drop Page Builder**

VelnoxAICMS is an enterprise-ready, modular Content Management System and drag-and-drop page builder built on the **VILT** stack (**Vue 3**, **Inertia.js v2**, **Laravel 13**, and **Tailwind CSS v4**). It seamlessly integrates AI content & plugin generation, visual workflow automation, headless CMS capabilities, and high-performance real-time infrastructure.

![Layout Builder](./documentation/images/layout-builder.png)

## 📌 Quick Access

- 🚀 [Getting Started Guide](documentation/1-get-started.md)
- 🎨 [Page Builder Guide](documentation/2-builder.md)
- 💻 [Developer Architecture Notes](documentation/3-developer.md)
- ⚙️ [Developer Setup & Workflow Guide](documentation/4-developer-guide.md)

> For a practical setup and development guide, see [documentation/4-developer-guide.md](documentation/4-developer-guide.md).

---

## 🌟 Key Features

### 🧩 Visual Drag-and-Drop Builder
* **Pragmatic Drag & Drop Engine**: Smooth, accessible component dragging powered by `@atlaskit/pragmatic-drag-and-drop`.
* **Rich Text & Inline Editing**: Powered by TipTap editor with full inline formatting, typography, links, and color controls.
* **Live Layout & Component Customization**: Real-time canvas manipulation with custom CodeMirror CSS editing and custom section ordering.
* **Responsive Breakpoint Previews**: Desktop, tablet, and mobile previews out-of-the-box.

### 🤖 AI-Generated Marketplace & Engine
* **AI Plugin Generator**: Generate full modular CMS plugins and concepts on the fly using multi-provider AI (OpenAI, Gemini, Anthropic, DeepSeek).
* **AI Concept Download & Install**: Inspect generated plugin specs, export downloadable ZIP packages, and install custom modules seamlessly.
* **Smart Content & Page Generation**: AI assistance for page copywriting, SEO metadata generation, and content creation.

### ⚡ Real-Time & High-Performance Stack
* **Laravel Octane Engine**: Powered by FrankenPHP long-running workers for lightning-fast request performance.
* **Laravel Horizon Integration**: Redis queue monitoring, background job tracking, and auto-scaling workers with automated metrics snapshots.
* **Laravel Reverb WebSockets**: Native, real-time WebSocket broadcasting with Laravel Echo.
* **Nuxt UI v3 Component System**: Sleek, accessible UI elements styled with Tailwind CSS v4 and dark mode support.

### ⚙️ Automation & Workflow Engine
* **Visual Workflow Builder**: Interactive node-based automation workflow editor for event-driven logic.
* **Webhooks Integration**: Outbound and inbound webhooks for third-party service integration.

### 📦 Modular Architecture (23 Modules)
Built with `nwidart/laravel-modules` into clean, decoupled domain modules:
* **Builder**: Page & section drag-and-drop builder.
* **Marketplace**: AI plugin generator, spec viewer, and module installer.
* **Workflow & Automation**: Visual workflow engine & webhook integrations.
* **Page & Content**: Pages, blog posts, categories, and Headless CMS collections.
* **Media**: Advanced file manager & asset media library (Spatie MediaLibrary).
* **Layout & Menu**: Header/Footer layout builder and hierarchical menu builder.
* **Acl & Auth**: Spatie Laravel Permission (Roles & Permissions) and user authentication.
* **Localization**: Multi-language translation management (Spatie Translatable).
* **AuditLog & Visits**: Activity logging (Spatie Activitylog) and visitor analytics.
* **ApiTokens & Settings**: API tokens management for headless delivery and platform configuration.

---

## 🛠️ Technology Stack

| Layer | Technology |
|---|---|
| **Backend Framework** | [Laravel 13](https://laravel.com) (PHP 8.5+) |
| **Frontend Framework** | [Vue 3.5](https://vuejs.org) (Composition API, `<script setup>`) |
| **Monolith Bridge** | [Inertia.js v2](https://inertiajs.com) (`@inertiajs/vue3`) |
| **Styling & UI Components** | [Tailwind CSS v4](https://tailwindcss.com) & [Nuxt UI v3](https://ui.nuxt.com) |
| **High-Performance Server** | [Laravel Octane](https://laravel.com/docs/octane) (FrankenPHP) |
| **Queue Management** | [Laravel Horizon](https://laravel.com/docs/horizon) (Redis) |
| **WebSockets** | [Laravel Reverb](https://laravel.com/docs/reverb) & [Laravel Echo](https://laravel.com/docs/broadcasting) |
| **State Management** | [Pinia](https://pinia.vuejs.org) & VueUse |
| **Type Generation** | [Spatie TypeScript Transformer](https://github.com/spatie/laravel-typescript-transformer) |
| **Package Architecture** | [nwidart/laravel-modules](https://nwidart-modules.com) |

---

## 🚀 Quick Start Guide

### Prerequisites
Ensure your environment meets the following requirements:
* **PHP**: 8.5+ (with `redis`, `pdo_mysql`, `mbstring`, `gd` or `imagick` extensions)
* **Node.js**: 20+ and `npm`
* **Composer**: 2.6+
* **Redis**: Running on `127.0.0.1:6379`
* **Database**: MySQL 8.0+ / PostgreSQL / SQLite

### 1. Installation & Setup

Clone the repository and install Composer & NPM dependencies:

```bash
git clone https://github.com/taha123618/VelnoxAICMS.git
cd VelnoxAICMS

composer install
npm install
```

### 2. Environment Configuration

Copy the example environment file and configure your database and Redis settings:

```bash
cp .env.example .env
php artisan key:generate
```

Verify the following key `.env` configurations:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=VelnoxAIcms
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=redis
CACHE_STORE=redis

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

OCTANE_SERVER=frankenphp
HORIZON_PREFIX=velnoxaicms_horizon:
```

### 3. Database Migration & Seeders

Run database migrations and seed default data:

```bash
php artisan migrate --seed
```

### 4. Spatie TypeScript Types Generation

Generate frontend TypeScript interfaces from PHP Spatie Data DTOs:

```bash
php artisan typescript:transform
```

---

## 💻 Development Workflow

### Start All-In-One Dev Server

VelnoxAICMS features a single command development environment that concurrently boots **Octane**, **Horizon**, **Pail** (log tailing), **Reverb** (WebSockets), and **Vite**:

```bash
composer run dev
```

This starts:
* **Octane (FrankenPHP)** with hot-reloading (`--watch`)
* **Horizon** Redis queue worker manager
* **Reverb** WebSocket server with debug output
* **Vite** HMR dev server
* **Pail** real-time log monitoring

### Useful Development Commands

| Command | Description |
|---|---|
| `composer run dev` | Launch Octane, Horizon, Reverb, Pail, and Vite concurrently |
| `php artisan typescript:transform` | Regenerate `resources/js/types/generated.d.ts` from PHP DTOs |
| `composer analyse` | Run **PHPStan** static analysis & **Rector** automated refactoring |
| `vendor/bin/pint --dirty` | Format modified PHP files according to Laravel Pint rules |
| `composer test` | Run **Pest v4** test suite |

---

## 🔐 Security & Monitoring

### Horizon Queue Dashboard
* Access the Horizon queue dashboard at `/horizon`.
* **Authorization**: Gated to users with the `admin` role in non-local environments (`App\Providers\HorizonServiceProvider`).
* **Metrics Snapshot**: Metrics automatically snapshot every 5 minutes via `routes/console.php`.

### Role-Based Access Control
* Powered by `spatie/laravel-permission`.
* Manage roles, permissions, and user assignments via the **Access Management** panel.

---

## 📚 Documentation & References

For detailed guides, explore the [`documentation/`](./documentation) directory:
* 📖 [Getting Started Guide](./documentation/1-get-started.md)
* 🎨 [Page Builder Guide](./documentation/2-builder.md)
* 💻 [Developer Architecture Notes](./documentation/3-developer.md)
* 🚀 [Developer Setup Guide](./documentation/4-developer-guide.md)

---

## 📄 License & Credits

* **Author**: [Taha Ahmed](mailto:tahaahmedanees2@gmail.com)
* **License**: Open-source under the [MIT License](LICENSE).
