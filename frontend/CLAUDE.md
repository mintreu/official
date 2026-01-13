# Frontend Development Guidelines for Mintreu

This document provides specific guidelines for frontend development to ensure consistency, security, and maintainability.

---

## API Integration Guidelines

### Using the API Composable

**ALWAYS use `useApi()` composable for all API calls.** This ensures consistent error handling, authentication, and response formatting.

```typescript
// ✅ CORRECT - Using the API composable
const { getProjects } = useApi()
const { data: projects } = await useAsyncData(
  'projects',
  () => getProjects({ page: 1, per_page: 10 })
)

// ❌ WRONG - Direct fetch calls
const { data } = await useSanctumFetch('/api/projects')
```

### Available API Methods

```typescript
// Home
getHomeData(params) → HomePageData

// Projects
getProjects(params) → PaginatedResponse<Project>
getProject(slug) → Project

// Case Studies
getCaseStudies(params) → PaginatedResponse<CaseStudy>
getCaseStudy(slug) → CaseStudy

// Products (formerly Marketplace)
getProducts(params) → PaginatedResponse<Product>
getProduct(slug) → Product

// Knowledge Base / Articles
getArticles(params) → PaginatedResponse<Article>
getArticle(slug) → Article
```

### Nuxt Sanctum Authentication

The project uses `@qirolab/nuxt-sanctum-authentication` for authentication. All API calls automatically include authentication cookies.

```typescript
// Authentication state
const { isLoggedIn, user, login, logout, register } = useSanctum()

// Login example
await login({ email, password })

// Protected API calls (auto-includes CSRF token and cookies)
const { data } = await useSanctumFetch('/api/user')
```

---

## Security: Never Expose Database IDs

### Rule: Use Unique Identifiers, Not Numeric IDs

**NEVER expose database auto-increment IDs in URLs or API responses.** This is a security requirement to prevent enumeration attacks.

```typescript
// ❌ WRONG - Exposes ID in URL
/articles/1
/projects/5
/products/3

// ✅ CORRECT - Uses slug (URL-friendly identifier)
articles/how-to-build-laravel-apis
projects/ecommerce-platform
products/premium-laravel-starter-kit
```

### Implementation Guidelines

1. **Database Models**: Always include a `slug` column for content types
   ```php
   // Migration
   $table->string('slug')->unique();

   // Model - Auto-generate slug
   protected static function booted(): void
   {
       static::creating(function ($model) {
           $model->slug = Str::slug($model->title);
       });
   }
   ```

2. **API Endpoints**: Use slug for lookups, not ID
   ```php
   // ❌ WRONG
   public function show(int $id) { ... }

   // ✅ CORRECT
   public function show(string $slug) { ... }
   ```

3. **Frontend Routes**: Use slug in dynamic routes
   ```typescript
   // ❌ WRONG
   /projects/[id].vue

   // ✅ CORRECT
   /projects/[slug].vue
   ```

4. **TypeScript Interfaces**: Do not expose internal IDs
   ```typescript
   // ❌ WRONG - Exposes internal ID
   interface Project {
     id: number
     // ...
   }

   // ✅ CORRECT - Uses public identifiers
   interface Project {
     slug: string
     // ...
   }
   ```

5. **API Responses**: Never return internal IDs
   ```php
   // ❌ WRONG - Returns internal ID
   return ['id' => $project->id, 'slug' => $project->slug, ...];

   // ✅ CORRECT - Only public identifiers
   return ['slug' => $project->slug, 'title' => $project->title, ...];
   ```

### Alternative Identifiers

When slug is not suitable, use these alternatives:
- `uuid` - For truly unique identifiers (generated via `Str::uuid()`)
- `url` - For SEO-friendly URLs with categories
- `code` - For SKU-style product codes (e.g., `PRD-001`)

---

## Route Naming Convention

### Use kebab-case for URLs

```typescript
// ✅ CORRECT
/projects/ecommerce-platform
/case-studies/healthcare-management-system
/products/laravel-starter-kit
/knowledge-base/laravel-best-practices

// ❌ WRONG
/projects/ecommercePlatform (camelCase)
/projects/Ecommerce-Platform (PascalCase)
/CaseStudies/healthcare (mixed)
```

### Page Component Naming

```typescript
// ✅ CORRECT
pages/projects/index.vue        → /projects
pages/projects/[slug].vue       → /projects/:slug
pages/products/index.vue        → /products
pages/case-studies/index.vue    → /case-studies

// ❌ WRONG
pages/Projects.vue              (single word, PascalCase)
pages/project-list.vue          (too verbose)
```

---

## Page Title Naming

For a software development/agency firm, use professional names:

| Model | Display Name | URL |
|-------|--------------|-----|
| Project | Work / Portfolio | /work |
| Case Study | Case Studies | /case-studies |
| Product | Products | /products |
| Article | Insights / Articles | /insights |

```typescript
// SEO meta for products page
useSeoMeta({
  title: 'Products | Mintreu - Premium Laravel & Nuxt Solutions',
  description: 'Browse our collection of premium code products, templates, and SaaS starter kits.'
})
```

---

## Component Architecture

### Use Composables for Shared Logic

```typescript
// composables/usePagination.ts
export function usePagination(defaultPerPage = 12) {
  const page = ref(1)
  const perPage = ref(defaultPerPage)

  const { data, pending, refresh } = await useAsyncData(
    key,
    () => api.getItems({ page: page.value, per_page: perPage.value }),
    { watch: [page] }
  )

  return { page, perPage, data, pending, refresh }
}
```

### Use TypeScript for All Components

```typescript
<script setup lang="ts">
import type { Project, PaginatedResponse } from '~/types/api'

// Define props with types
interface Props {
  featured?: boolean
  limit?: number
}

const props = withDefaults(defineProps<Props>(), {
  featured: false,
  limit: 6
})

// Use proper types for computed
const projects = computed<Project[]>(() => {
  return data.value?.data || []
})
</script>
```

---

## Error Handling

### Consistent Error States

```vue
<template>
  <!-- Loading -->
  <div v-if="pending" class="animate-pulse">
    <!-- Skeleton loader -->
  </div>

  <!-- Error -->
  <div v-else-if="error" class="text-center text-red-500">
    <Icon name="lucide:alert-circle" class="w-8 h-8 mx-auto mb-2" />
    <p>Failed to load data. Please try again.</p>
    <button @click="refresh()">Retry</button>
  </div>

  <!-- Empty -->
  <div v-else-if="!data?.data.length" class="text-center">
    <p>No items found.</p>
  </div>

  <!-- Data -->
  <div v-else>
    <!-- Content -->
  </div>
</template>
```

---

## Import Conventions

```typescript
// ✅ CORRECT - Using type imports
import type { Project, CaseStudy, Product, Article } from '~/types/api'

// ✅ CORRECT - Using api composable
const api = useApi()

// ✅ CORRECT - Auto-imported Nuxt components
<FeatureProjects />
<CaseStudies />

// ❌ WRONG - Not using type imports when needed
import { Project } from '~/types/api'
```

---

## Testing Requirements

1. **API Integration Tests**: Test all API endpoints
2. **Component Tests**: Test loading, error, and empty states
3. **Type Tests**: Ensure TypeScript types are correct
4. **E2E Tests**: Test complete user flows

---

## Performance Guidelines

1. **Lazy Loading**: Use `lazy: true` for non-critical data
2. **Image Optimization**: Use Nuxt Image for all images
3. **Code Splitting**: Nuxt auto-split by pages
4. **Caching**: Use `useAsyncData` with proper keys for hydration

```typescript
// With caching
const { data } = await useAsyncData(
  `project-${slug}`,  // Unique key for each item
  () => api.getProject(slug),
  { transform: (response) => response.data }
)
```
