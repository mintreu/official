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
  results: string[] | string  // Can be array or JSON string from API
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
}

// Article Types
export interface Article {
  id: number
  title: string
  slug: string
  excerpt: string
  content: string
}

// Category Types
export interface Category {
  id: number
  slug: string
  name: string
  order?: number
  is_active?: boolean
}
