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
  id: number
  title: string
  slug: string
  description: string
  excerpt?: string
  content: string
  image: string
  price: number
  category: string
  type: 'api' | 'template' | 'plugin' | 'freebie' | 'media'
  features?: string[]
  tech?: string[]
  download_url?: string
  demo_url?: string
  github_url?: string
  documentation_url?: string
  version: string
  downloads: number
  rating: number
  status: 'draft' | 'published' | 'archived'
  featured: boolean
  created_at: string
  updated_at: string
  engagement?: ProductEngagement
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
  is_active: boolean
  requests_this_month: number
  requests_today: number
  last_used_at?: string | null
  expires_at?: string | null
  environment: string
  plan?: {
    requests_per_month?: number | null
    requests_per_day?: number | null
  } | null
}

export interface LicenseSummary {
  id: number
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
  feature_flags: FeatureFlags
}

export interface DownloadLinkResponse {
  download_url: string
  expires_at: string
  file_name: string
  file_size?: number | null
}
