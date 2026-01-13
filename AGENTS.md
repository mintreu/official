# AGENTS.md - Coding Guidelines for Mintreu

## Build/Lint/Test Commands

### Backend (Laravel/PHP)
- **Full test suite**: `composer test` (runs `php artisan test`)
- **Single test**: `php artisan test --filter=TestClassName::testMethodName`
- **Code formatting**: `vendor/bin/pint` (Laravel Pint)
- **Dev server**: `php artisan serve`
- **Build assets**: `npm run build`

### Frontend (Nuxt/Vue)
- **Dev server**: `npm run dev`
- **Build**: `npm run build`
- **Generate static**: `npm run generate`

### Full-stack Development
- **Setup**: `composer run setup` (installs deps, generates key, migrates, builds assets)
- **Development**: `composer run dev` (runs server, queue worker, and Vite concurrently)

## Code Style Guidelines

### PHP/Laravel
- **Indentation**: 4 spaces (configured in .editorconfig)
- **Imports**: Group by type (classes, then functions/constants), alphabetical within groups
- **Naming**: PSR-4 autoloading, camelCase for methods/variables, PascalCase for classes
- **Types**: Use typed properties, method parameters, and return types where possible
- **DocBlocks**: Use PHPDoc for public methods, especially in controllers/models
- **Error handling**: Use try/catch with specific exceptions, avoid generic Exception
- **Traits**: Use single-line trait imports: `use HasFactory, Notifiable;`

### Vue/Nuxt/TypeScript
- **Script setup**: Prefer `<script setup lang="ts">` with Composition API
- **Imports**: Group Vue imports first, then external libraries, then local imports
- **Naming**: PascalCase for components, camelCase for variables/methods
- **Types**: Use TypeScript interfaces for props and complex data structures
- **Styling**: Use Tailwind CSS classes, prefer @apply for reusable component styles
- **Accessibility**: Include proper ARIA labels and semantic HTML

### General
- **Line endings**: LF (Unix-style, configured in .editorconfig)
- **Final newlines**: Required for all files
- **Trailing whitespace**: Trimmed automatically
- **Comments**: Minimal but descriptive; avoid obvious comments
- **Security**: Never log or expose secrets/keys; validate all inputs</content>
<parameter name="filePath">C:\laragon\www\mintreu\server\official\AGENTS.md