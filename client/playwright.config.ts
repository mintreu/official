import { defineConfig } from '@playwright/test'

export default defineConfig({
  testDir: './tests',
  timeout: 30000,
  expect: {
    timeout: 5000
  },
  use: {
    baseURL: 'http://localhost:3000',
    trace: 'on-first-retry',
    viewport: { width: 1280, height: 720 },
    headless: true
  },
  webServer: {
    command: 'npm run dev -- --port=3000',
    port: 3000,
    reuseExistingServer: true
  }
})
