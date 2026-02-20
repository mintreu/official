import { ref } from 'vue'
import type { HomePageData } from '~/types/api'

const homeDataState = ref<HomePageData | null>(null)
const homePendingState = ref(false)
const homeLoadedState = ref(false)

export default function useHomeData() {
  const { getHomeData } = useApi()

  const loadHomeData = async () => {
    if (homeLoadedState.value || homePendingState.value) {
      return homeDataState.value
    }

    homePendingState.value = true

    try {
      const response = await getHomeData() as any

      if (response?.data && !Array.isArray(response.data)) {
        homeDataState.value = response.data as HomePageData
      } else {
        homeDataState.value = response as HomePageData
      }

      homeLoadedState.value = true
      return homeDataState.value
    } catch {
      homeDataState.value = homeDataState.value ?? {
        featured_projects: [],
        case_studies: [],
        products: [],
      }
      return homeDataState.value
    } finally {
      homePendingState.value = false
    }
  }

  return {
    homeData: homeDataState,
    homePending: homePendingState,
    loadHomeData,
  }
}
