# Nuxt Sanctum Authentication Guide

This document provides a guide for using the `@qirolab/nuxt-sanctum-authentication` module in this project.

## Overview

The `@qirolab/nuxt-sanctum-authentication` module integrates Laravel Sanctum with Nuxt for a secure authentication process. It supports both Client-Side Rendering (CSR) and Server-Side Rendering (SSR), automatic CSRF token management, and Bearer token management.

## Project Configuration

The module is configured in `nuxt.config.ts` as follows:

```typescript
// ✅ Laravel Sanctum Configuration
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

- **`apiUrl`**: The base URL of the Laravel API.
- **`authMode`**: The authentication mode, which is set to `cookie` for session-based authentication.
- **`userResponseWrapperKey`**: The key in the user response that contains the user data.
- **`sanctumEndpoints`**: The endpoints for CSRF, login, and logout.

## Composables

The module provides two main composables: `useSanctum` and `useSanctumFetch`.

### `useSanctum()`

The `useSanctum()` composable is the primary tool for managing authentication.

#### Properties

- **`user`**: Holds the data of the currently authenticated user.
- **`isLoggedIn`**: A boolean property indicating whether a user is currently authenticated.
- **`options`**: Provides access to the module's configuration options.

#### Methods

- **`login(credentials, clientOptions?, callback?)`**: Authenticates users by sending their credentials to the Laravel backend.
- **`logout()`**: Logs out the authenticated user.
- **`refreshUser()`**: Manually re-fetches and updates the current user's data from the backend.

**Example Usage:**

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

### `useSanctumFetch()`

The `useSanctumFetch()` composable is a wrapper around Nuxt's `useFetch` that automatically handles authentication and CSRF tokens for API requests. It ensures that the CSRF token is obtained before making any state-modifying requests (e.g., POST, PUT, DELETE).

**Example Usage:**

```vue
<script setup lang="ts">
import { useSanctumFetch } from '@qirolab/nuxt-sanctum-authentication';

const { data, error } = await useSanctumFetch('/api/user/profile', {
  method: 'GET',
});
</script>
```
