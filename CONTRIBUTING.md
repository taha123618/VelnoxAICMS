# Contributing to VelnoxAICMS

Thank you for your interest in contributing to **VelnoxAICMS**! We welcome community contributions to help build the best AI-native modular CMS and drag-and-drop page builder.

---

## 🚀 How to Contribute

### 1. Reporting Bugs & Feature Requests
- Check existing [GitHub Issues](https://github.com/taha123618/VelnoxAICMS/issues) before opening a new one to prevent duplicates.
- Provide detailed steps to reproduce bugs, including your PHP version, Node version, browser details, and stack traces if applicable.

### 2. Local Development Setup
1. Fork the repository on GitHub.
2. Clone your fork locally:
   ```bash
   git clone https://github.com/YOUR_USERNAME/VelnoxAICMS.git
   cd VelnoxAICMS
   ```
3. Install dependencies and set up `.env`:
   ```bash
   composer install
   npm install
   cp .env.example .env
   php artisan key:generate
   ```
4. Run migrations and seed data:
   ```bash
   php artisan migrate --seed
   ```
5. Transform TypeScript types:
   ```bash
   php artisan typescript:transform
   ```
6. Boot the development server:
   ```bash
   composer run dev
   ```

---

## 🛠️ Code Standards & Guidelines

### PHP Standards
- Follow Laravel Boost & PSR-12 conventions.
- Format modified PHP code using **Laravel Pint**:
  ```bash
  vendor/bin/pint --dirty
  ```
- Verify static analysis type safety using **PHPStan**:
  ```bash
  composer phpstan
  ```

### Vue 3 & TypeScript Standards
- Write modular Vue 3 components using Composition API `<script setup lang="ts">`.
- Use Nuxt UI v3 components styled with Tailwind CSS v4.
- Always run `npm run build` or `npm run dev` to verify client builds.

### Testing
- Write automated tests for new features using **Pest v4**:
  ```bash
  composer test
  ```

---

## 📄 Pull Request Process

1. Create a descriptive branch for your fix or feature:
   ```bash
   git checkout -b feature/amazing-feature
   ```
2. Commit your changes with clear commit messages.
3. Ensure all tests and static analysis checks pass (`composer analyse` & `composer test`).
4. Push to your fork and submit a Pull Request to the `main` branch.
5. Provide a summary of changes and visual screenshots or recordings for UI updates.

Thank you for helping make VelnoxAICMS open-source software better for everyone!
