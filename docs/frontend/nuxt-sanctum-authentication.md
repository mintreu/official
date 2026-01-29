# Nuxt Sanctum Authentication Guide

This document provides a guide for using the `@qirolab/nuxt-sanctum-authentication` module in this project.

## Overview

The `@qirolab/nuxt-sanctum-authentication` module integrates Laravel Sanctum with Nuxt for a secure authentication process. It supports both Client-Side Rendering (CSR) and Server-Side Rendering (SSR), automatic CSRF token management, and Bearer token management.

## Project Configuration

The module is configured in `nuxt.config.ts` as follows:

```typescript
// Laravel Sanctum Configuration
laravelSanctum: {
  apiUrl: 'https://localhost:8000',
  authMode: 'cookie',
  userResponseWrapperKey: 'data',
  sanctumEndpoints: {
    csrf: '/sanctum/csrf-cookie',
    login: '/api/login',
    logout: '/api/logout',
  },
},
```

- `apiUrl`: Base URL of the Laravel API
- `authMode`: Authentication mode (cookie for session-based auth)
- `userResponseWrapperKey`: Key that contains user data
- `sanctumEndpoints`: Endpoints for CSRF, login, and logout

## Composables

The module provides two main composables: `useSanctum` and `useSanctumFetch`.

### useSanctum()

The `useSanctum()` composable is the primary tool for managing authentication.

Properties:
- `user`: Current authenticated user
- `isLoggedIn`: Boolean for auth state
- `options`: Module configuration options

Methods:
- `login(credentials, clientOptions?, callback?)`
- `logout()`
- `refreshUser()`

Example:

```vue
<script setup lang="ts">
import { useSanctum } from '@qirolab/nuxt-sanctum-authentication';

const { login, logout, user, isLoggedIn } = useSanctum();

const credentials = {
  email: 'user@example.com',
  password: 'password',
};

async function handleLogin() {
  await login(credentials);
}

async function handleLogout() {
  await logout();
}
</script>
```

### useSanctumFetch()

`useSanctumFetch()` wraps Nuxt's `useFetch` and handles CSRF + auth automatically.

Example:

```vue
<script setup lang="ts">
import { useSanctumFetch } from '@qirolab/nuxt-sanctum-authentication';

const { data, error } = await useSanctumFetch('/api/user/profile', {
  method: 'GET',
});
</script>
```
