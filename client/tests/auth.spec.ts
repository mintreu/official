import { expect, test } from '@playwright/test'

const loginResponse = { token: 'demo-token' }

const userResponse = {
  id: 1,
  name: 'Client Demo',
  email: 'client@mintreu.com',
  email_verified_at: '2026-02-17T12:00:00Z',
  created_at: '2025-12-01T00:00:00Z',
  about: 'Founder of Mintreu'
}

const licensesResponse = {
  data: [
    {
      id: 1,
      license_key: 'DL-ABC-1234',
      type: 'downloadable',
      status: 'active',
      is_active: true,
      usage_count: 2,
      max_usage: 10,
      expires_at: '2027-01-01T00:00:00.000000Z',
      created_at: '2026-01-01T00:00:00.000000Z',
      next_renewal_at: '2027-01-01T00:00:00.000000Z',
      product: {
        slug: 'vue3-dashboard-template',
        title: 'Vue 3 Dashboard Template',
        short_description:
          'Free responsive dashboard kit built with Vue 3 and Tailwind CSS.',
        category: 'frontend',
        type: 'downloadable',
        image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800',
        version: '1.0.0',
        demo_url: 'https://vue-dashboard-demo.example.com',
        documentation_url: 'https://docs.example.com/vue3-dashboard',
        requires_auth: true,
        engagement: {
          downloads: 5420,
          rating: 4.7
        }
      },
      plan: null,
      download_sources: [
        {
          id: 1,
          name: 'Primary Mirror',
          description: 'USA mirror',
          version: '1.0.0',
          file_name: 'vue3-dashboard-template-v1.0.0.zip',
          file_size: 3145728,
          file_size_formatted: '3 MB',
          is_primary: true,
          provider: 'local',
          provider_label: 'Local Storage'
        }
      ],
      api_key: null
    },
    {
      id: 2,
      license_key: 'API-KEY-001',
      type: 'subscription',
      status: 'active',
      is_active: true,
      usage_count: 5000,
      max_usage: null,
      expires_at: '2027-02-01T00:00:00.000000Z',
      created_at: '2026-01-15T00:00:00.000000Z',
      next_renewal_at: '2026-03-01T00:00:00.000000Z',
      product: {
        slug: 'advanced-ecommerce-api',
        title: 'Advanced E-Commerce API',
        short_description: 'Production-ready REST API for e-commerce with analytics.',
        category: 'backend',
        type: 'api_service',
        image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800',
        version: '3.1.2',
        documentation_url: 'https://docs.example.com/ecommerce-api',
        requires_auth: true,
        engagement: {
          downloads: 420,
          rating: 4.8
        }
      },
      plan: {
        id: 1,
        name: 'Growth API Plan',
        slug: 'growth-api',
        description: 'Full-featured API plan',
        price: 199.99,
        price_formatted: '$199.99',
        billing_cycle: 'monthly',
        features: [],
        limits: [],
        requests_per_month: 100000,
        requests_per_day: 3333,
        requests_per_minute: 50,
        is_popular: true
      },
      download_sources: [],
      api_key: {
        id: 11,
        key_prefix: 'mintreu-api',
        is_active: true,
        requests_this_month: 8000,
        requests_today: 320,
        last_used_at: '2026-02-17T05:00:00Z',
        expires_at: '2027-02-01T00:00:00.000000Z',
        environment: 'prod',
        plan: {
          requests_per_month: 100000,
          requests_per_day: 3333
        }
      }
    }
  ],
  feature_flags: {
    downloads_enabled: true,
    api_access_enabled: true,
    licensing_enabled: true
  },
  meta: {
    current_page: 1,
    from: 1,
    last_page: 1,
    per_page: 6,
    to: 2,
    total: 2
  },
  filters: {
    type: 'all'
  }
}

const licenseSummaryResponse = {
  data: {
    totals: {
      licenses: 2,
      active_licenses: 2,
      active_api_subscriptions: 1,
      active_api_projects: 1,
      expiring_soon: 0,
      spaces: 2
    },
    billing_model: {
      recommended: 'overall_plus_site_addon',
      why: 'One product subscription reduces friction; site addon scales fairly.'
    },
    site_billing: [],
    project_insights: [],
    upcoming_renewals: []
  }
}

test('auth flow shows dashboard stats', async ({ page }) => {
  await page.addInitScript(() => {
    window.localStorage.clear()
  })

  let isAuthenticated = false
  await page.route('**/api/auth/login**', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify(loginResponse)
    })
    isAuthenticated = true
  })

  await page.route('**/api/user**', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify(isAuthenticated ? userResponse : null)
    })
  })

  await page.route('**/api/licenses**', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify(licensesResponse)
    })
  })

  await page.route('**/api/licenses/summary**', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify(licenseSummaryResponse)
    })
  })

  await page.goto('/auth/signin')
  await page.fill('input[type="email"]', 'client@mintreu.com')
  await page.fill('input[type="password"]', 'password')

  await page.click('button:has-text("Sign In securely")')

  await expect(page).toHaveURL(/\/dashboard/)
  await expect(page.getByRole('heading', { name: 'Dashboard overview' })).toBeVisible()
  await expect(page.getByRole('main').getByRole('link', { name: 'Licenses' })).toBeVisible()
})

