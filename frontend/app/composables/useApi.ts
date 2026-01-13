import type {
  Project,
  CaseStudy,
  Product,
  Article,
  HomePageData,
  ApiResponse,
  PaginatedResponse
} from '~/types/api'

/**
 * Enterprise-grade API composable for Mintreu frontend
 * Uses useSanctumFetch for all API calls with proper error handling
 */
export default function useApi() {
  // ==================== HOME ====================
  const getHomeData = (params?: Record<string, any>) => {
    return useSanctumFetch<ApiResponse<HomePageData>>('/api/home', {
      method: 'GET',
      params
    })
  }

  // ==================== PROJECTS ====================
  const getProjects = (params?: {
    page?: number
    per_page?: number
    category?: string
    featured?: boolean
    search?: string
    sort?: 'latest' | 'oldest' | 'title' | 'popular'
  }) => {
    return useSanctumFetch<PaginatedResponse<Project>>('/api/projects', {
      method: 'GET',
      params
    })
  }

  const getProject = (slug: string) => {
    return useSanctumFetch<ApiResponse<Project>>(`/api/projects/${slug}`, {
      method: 'GET'
    })
  }

  // ==================== CASE STUDIES ====================
  const getCaseStudies = (params?: {
    page?: number
    per_page?: number
    industry?: string
    featured?: boolean
    search?: string
    sort?: 'latest' | 'oldest' | 'title' | 'popular'
  }) => {
    return useSanctumFetch<PaginatedResponse<CaseStudy>>('/api/case-studies', {
      method: 'GET',
      params
    })
  }

  const getCaseStudy = (slug: string) => {
    return useSanctumFetch<ApiResponse<CaseStudy>>(`/api/case-studies/${slug}`, {
      method: 'GET'
    })
  }

  // ==================== PRODUCTS ====================
  const getProducts = (params?: {
    page?: number
    per_page?: number
    category?: string
    type?: string
    featured?: boolean
    search?: string
    sort?: 'latest' | 'popular' | 'price_low' | 'price_high'
  }) => {
    return useSanctumFetch<PaginatedResponse<Product>>('/api/products', {
      method: 'GET',
      params
    })
  }

  const getProduct = (slug: string) => {
    return useSanctumFetch<ApiResponse<Product>>(`/api/products/${slug}`, {
      method: 'GET'
    })
  }

  // ==================== ARTICLES/INSIGHTS ====================
  const getArticles = (params?: {
    page?: number
    per_page?: number
    category?: string
    tags?: string[]
    featured?: boolean
    search?: string
    sort?: 'latest' | 'popular' | 'title'
  }) => {
    return useSanctumFetch<PaginatedResponse<Article>>('/api/articles', {
      method: 'GET',
      params
    })
  }

  const getArticle = (slug: string) => {
    return useSanctumFetch<ApiResponse<Article>>(`/api/articles/${slug}`, {
      method: 'GET'
    })
  }

  // ==================== CONTACT FORM ====================
  const submitContact = (data: {
    name: string
    email: string
    project_type?: string
    budget?: string
    message: string
  }) => {
    return useSanctumFetch<ApiResponse<null>>('/api/contact', {
      method: 'POST',
      body: data
    })
  }

  // ==================== HEALTH CHECK ====================
  const healthCheck = () => {
    return useSanctumFetch<{ status: string; timestamp: string }>('/api/health', {
      method: 'GET'
    })
  }

  return {
    // Home
    getHomeData,

    // Projects
    getProjects,
    getProject,

    // Case Studies
    getCaseStudies,
    getCaseStudy,

    // Products/Marketplace
    getProducts,
    getProduct,

    // Articles/Insights
    getArticles,
    getArticle,

    // Contact Form
    submitContact,

    // Health
    healthCheck
  }
}
