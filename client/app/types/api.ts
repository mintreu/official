// Quote Wizard Request
export interface QuoteRequest {
  // Contact info
  name: string
  email: string
  company?: string
  mobile?: string
  // Project basics
  project_category: string
  project_type: string
  platforms: string[]
  // Scope & features
  features: string[]
  custom_features?: string
  description: string
  // Budget & timeline
  budget_range?: string
  currency: 'USD' | 'INR'
  timeline?: string
  // Extras
  has_existing_project: boolean
  existing_project_url?: string
  reference_urls?: string
  priority: 'normal' | 'urgent'
}

// API Response Types

export interface ApiResponse<T> {
  data: T
  message?: string
  status?: string
}

export interface PaginatedResponse<T> {
  data: T[]
  meta: {
    current_page: number
    from: number
    last_page: number
    per_page: number
    to: number
    total: number
  }
  links: {
    first: string
    last: string
    prev: string | null
    next: string | null
  }
}

// Project Types
export interface Project {
  id: number
  title: string
  slug: string
  description: string
  content: string
  image: string
  category: string
  technologies: string[]
  status: 'draft' | 'published' | 'archived'
  featured: boolean
  live_url?: string
  github_url?: string
  created_at: string
  updated_at: string
}

// Case Study Types
export interface CaseStudy {
  id: number
  title: string
  slug: string
  description: string
  content: string
  image: string
  client: string
  industry: string
  duration: string
  technologies: string[]
  challenge: string
  solution: string
  results: Array<{ value: string; label: string }> | string[] | string  // Can be objects, array, or JSON string from API
  status: 'draft' | 'published' | 'archived'
  featured: boolean
  github_url?: string
  created_at: string
  updated_at: string
}

// Product Types
export interface Product {
  id?: number
  title: string
  slug: string
  short_description?: string | null
  description: string
  excerpt?: string
  content?: string
  image?: string | null
  price: number
  category?: string | null
  type: 'downloadable' | 'api_service' | 'api_referral' | 'freebie' | 'demo' | string
  type_label?: string
  features?: string[]
  tech?: string[]
  download_url?: string
  demo_url?: string
  github_url?: string
  documentation_url?: string
  version: string
  downloads: number
  rating: number
  status?: 'draft' | 'published' | 'archived'
  featured: boolean
  requires_auth?: boolean
  meta?: Record<string, any> | null
  plans?: ProductPlan[] | null
  sources?: ProductSource[] | null
  created_at: string
  updated_at: string
  engagement?: ProductEngagement
}

export interface ProductPlan {
  id: number
  slug: string
  name: string
  description?: string | null
  price: number
  price_formatted: string
  billing_cycle: string
  billing_label: string
  requests_per_month?: number | null
  requests_per_day?: number | null
  requests_per_minute?: number | null
  features?: string[] | null
  limits?: Record<string, any> | null
  is_popular: boolean
}

export interface ProductSource {
  name: string
  description?: string | null
  version?: string | null
  file_name?: string | null
  file_size?: string | null
  is_primary: boolean
}

export interface ProductEngagement {
  downloads: number
  rating: number
  version: string
}

// Article Types
export interface Article {
  id: number
  title: string
  slug: string
  excerpt: string
  content: string
}

// Home Page Types
export interface HomePageData {
  featured_projects: Project[]
  case_studies: CaseStudy[]
  products: Product[]
  stats?: Array<{ value: number; suffix?: string; label: string }>
  services?: Array<{ title: string; icon: string; description: string; features: string[] }>
  comparisonData?: Array<{ feature: string; mintreu: string; agency: string; platform: string }>
  soloFeatures?: string[]
  teamFeatures?: string[]
  technologies?: string[]
  socials?: Array<{ name: string; url: string; icon: string }>
  paymentMethods?: Array<{ name: string; icon: string; color?: string }>
  quoteSteps?: Array<{ title: string; description: string }>
}

// Category Types
export interface Category {
  id: number
  slug: string
  name: string
  order?: number
  is_active?: boolean
}
// License & dashboard types
export interface LicenseProduct {
  id: number
  slug: string
  title: string
  short_description?: string | null
  category?: string | null
  type: string
  image?: string | null
  version?: string | null
  price: number
  requires_auth: boolean
  demo_url?: string | null
  documentation_url?: string | null
  engagement: {
    downloads: number
    rating: number
  }
}

export interface LicensePlanSummary {
  id: number
  name: string
  slug: string
  description?: string | null
  price: number
  price_formatted: string
  billing_cycle: string
  features: string[] | null
  limits: Record<string, any> | null
  requests_per_month?: number | null
  requests_per_day?: number | null
  requests_per_minute?: number | null
  is_popular: boolean
}

export interface LicenseDownloadSource {
  id: number
  name: string
  description?: string | null
  version?: string | null
  file_name?: string | null
  file_size?: number | null
  file_size_formatted?: string | null
  is_primary: boolean
  provider: string
  provider_label: string
}

export interface LicenseApiKeySummary {
  id: number
  key_prefix: string
  name?: string | null
  is_active: boolean
  requests_this_month: number
  requests_today: number
  last_used_at?: string | null
  expires_at?: string | null
  environment: string
  domain_restriction?: string | null
  allowed_domains?: string[]
  ip_whitelist?: string[]
  plan?: {
    requests_per_month?: number | null
    requests_per_day?: number | null
  } | null
}

export interface LicenseSummary {
  uuid: string
  license_key: string
  type: string
  type_label: string
  status: string
  is_active: boolean
  usage_count: number
  max_usage: number | null
  remaining_usages: number | null
  expires_at?: string | null
  next_renewal_at?: string | null
  created_at?: string | null
  product: LicenseProduct
  plan?: LicensePlanSummary | null
  download_sources: LicenseDownloadSource[]
  api_key?: LicenseApiKeySummary | null
}

export interface FeatureFlags {
  downloads_enabled: boolean
  api_access_enabled: boolean
  licensing_enabled: boolean
}

export interface LicenseDashboardPayload {
  data: LicenseSummary[]
  meta: {
    current_page: number
    from: number | null
    last_page: number
    per_page: number
    to: number | null
    total: number
  }
  filters: {
    type: 'all' | 'download' | 'api'
  }
  feature_flags: FeatureFlags
}

export interface LicenseSummaryTotals {
  licenses: number
  active_licenses: number
  active_api_subscriptions: number
  active_api_projects?: number
  expiring_soon: number
  spaces: number
}

export interface LicenseSiteBilling {
  license_uuid: string
  product_slug?: string | null
  plan?: string | null
  included_sites: number
  used_sites: number
  extra_sites: number
  site_addon_monthly_price: number
  estimated_extra_monthly_cost: number
}

export interface LicenseRenewal {
  license_uuid: string
  product_slug?: string | null
  plan?: string | null
  expires_at?: string | null
  days_left?: number | null
}

export interface LicenseProjectInsight {
  product_id?: number | null
  product_slug?: string | null
  product_title?: string | null
  active_licenses: number
  spaces: number
  requests_this_month: number
  requests_today: number
  latest_activity_at?: string | null
}

export interface LicenseOverviewSummaryPayload {
  data: {
    totals: LicenseSummaryTotals
    billing_model: {
      recommended: string
      why: string
    }
    site_billing: LicenseSiteBilling[]
    project_insights: LicenseProjectInsight[]
    upcoming_renewals: LicenseRenewal[]
  }
}

export interface MatchedFrontend {
  id: number
  slug: string
  title: string
  short_description?: string | null
  image?: string | null
  demo_url?: string | null
  documentation_url?: string | null
  price: number
  version?: string | null
}

export interface LicenseDetailPayload {
  data: LicenseSummary
  upgrade_options: LicensePlanSummary[]
  matched_frontends: MatchedFrontend[]
}

export interface ApiSpaceSummary {
  uuid: string
  name: string
  website: string
  environment: string
  status: 'active' | 'paused' | 'disabled'
  deleted_at?: string | null
  requests_this_month: number
  requests_today: number
  last_request_at?: string | null
  config: Record<string, any>
  insights: {
    avg_latency_ms?: number
    error_rate_percent?: number
    top_endpoint?: string | null
    [key: string]: any
  }
  api_key?: {
    id: number
    key_prefix: string
    name?: string | null
  } | null
  plan?: {
    name: string
    requests_per_month?: number | null
    requests_per_day?: number | null
  } | null
  product?: {
    id: number
    slug: string
    title: string
    type: string
  } | null
}

export interface DownloadLinkResponse {
  download_url: string
  expires_at: string
  file_name: string
  file_size?: number | null
}

export interface ProductReview {
  uuid: string
  rating: number
  review?: string | null
  updated_at?: string | null
}

// Referral & Affiliate Types
export interface ReferralDashboard {
  referral_code: string
  referral_link: string
  stats: {
    total_earned: number
    pending_payout: number
    available_balance: number
    total_referrals: number
    active_referrals: number
  }
  monthly_earnings: { month: string; amount: number }[]
  commissions: PaginatedResponse<ReferralCommission>
}

export interface ReferralCommission {
  id: number
  date: string
  product_title: string
  buyer_name: string
  amount: number
  currency: string
  status: 'pending' | 'approved' | 'paid' | 'rejected'
}
