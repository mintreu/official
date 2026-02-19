<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import type { ReferralDashboard, ReferralCommission } from '~/types/api'
import gsap from 'gsap'

definePageMeta({
  layout: 'dashboard',
  middleware: ['$auth'],
  title: 'Referral Earnings'
})

useSeoMeta({
  title: 'Referral Earnings | Mintreu Dashboard'
})

const currency = inject('currency', ref<'USD' | 'INR'>('USD'))
const { getReferralDashboard } = useApi()

const page = ref(1)
const referralData = ref<ReferralDashboard | null>(null)
const pending = ref(false)
const fetchError = ref<string | null>(null)
const copied = ref(false)

const fetchData = async () => {
  pending.value = true
  fetchError.value = null
  try {
    const response = await getReferralDashboard({ page: page.value, per_page: 10 }) as any
    referralData.value = response?.data ?? response ?? null
  } catch (err: any) {
    fetchError.value = 'Unable to load referral data. The referral program may not be active yet.'
  } finally {
    pending.value = false
  }
}

onMounted(() => { fetchData() })

const stats = computed(() => referralData.value?.stats ?? { total_earned: 0, pending_payout: 0, available_balance: 0, total_referrals: 0, active_referrals: 0 })
const referralLink = computed(() => referralData.value?.referral_link ?? '')
const referralCode = computed(() => referralData.value?.referral_code ?? '--')
const monthlyEarnings = computed(() => referralData.value?.monthly_earnings ?? [])
const commissions = computed<ReferralCommission[]>(() => referralData.value?.commissions?.data ?? [])
const commissionsMeta = computed(() => referralData.value?.commissions?.meta ?? null)

const earningsChartData = computed(() =>
  monthlyEarnings.value.map(e => ({ label: e.month, value: e.amount }))
)

const formatCurrency = (amount: number) => {
  if (currency.value === 'INR') return `\u20B9${(amount * 82).toLocaleString()}`
  return `$${amount.toLocaleString()}`
}

const copyLink = async () => {
  if (!referralLink.value) return
  try {
    await navigator.clipboard.writeText(referralLink.value)
    copied.value = true
    setTimeout(() => { copied.value = false }, 2000)
  } catch {
    // Fallback
    const input = document.createElement('input')
    input.value = referralLink.value
    document.body.appendChild(input)
    input.select()
    document.execCommand('copy')
    document.body.removeChild(input)
    copied.value = true
    setTimeout(() => { copied.value = false }, 2000)
  }
}

const statusColors: Record<string, string> = {
  pending: 'bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400',
  approved: 'bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400',
  paid: 'bg-emerald-100 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400',
  rejected: 'bg-rose-100 dark:bg-rose-900/20 text-rose-700 dark:text-rose-400'
}

// Sparkline data for stat cards
const earningsSparkline = computed(() => monthlyEarnings.value.map(e => e.amount))

// GSAP animations
const pageRef = ref<HTMLElement | null>(null)
let ctx: gsap.Context | null = null

const initAnimations = () => {
  if (!pageRef.value) return
  ctx = gsap.context(() => {
    const sections = gsap.utils.toArray('.referral-section') as HTMLElement[]
    if (sections.length) {
      gsap.set(sections, { opacity: 0, y: 30 })
      gsap.to(sections, { opacity: 1, y: 0, duration: 0.6, stagger: 0.12, ease: 'power2.out', delay: 0.1 })
    }
  }, pageRef.value)
}

onMounted(() => {
  nextTick(() => { setTimeout(initAnimations, 50) })
})

onUnmounted(() => { ctx?.revert() })
</script>

<template>
  <div ref="pageRef">
    <!-- Page Header -->
    <div>
      <h1 class="text-2xl font-heading font-black text-titanium-900 dark:text-white">Referral earnings</h1>
      <p class="text-sm text-titanium-500 dark:text-titanium-400 mt-1">Earn commissions by referring clients to Mintreu products.</p>
    </div>

    <!-- Referral Link Card -->
    <div class="referral-section">
      <DashboardDashboardCard title="Your referral link" subtitle="Share this link to earn commissions on every sale">
        <div class="flex flex-col sm:flex-row gap-3">
          <div class="flex-1 relative">
            <input
              type="text"
              :value="referralLink"
              readonly
              class="input-dashboard pr-20 font-mono text-sm"
            />
            <span class="absolute right-3 top-1/2 -translate-y-1/2 px-2 py-0.5 bg-titanium-100 dark:bg-titanium-700 text-titanium-500 dark:text-titanium-400 rounded text-[10px] font-heading font-bold uppercase tracking-wider">
              {{ referralCode }}
            </span>
          </div>
          <button
            @click="copyLink"
            :disabled="!referralLink"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300"
            :class="copied
              ? 'bg-emerald-600 text-white'
              : 'bg-mintreu-red-600 hover:bg-mintreu-red-700 text-white disabled:opacity-60 disabled:cursor-not-allowed'"
          >
            <Icon :name="copied ? 'lucide:check' : 'lucide:copy'" class="w-4 h-4" />
            {{ copied ? 'Copied!' : 'Copy Link' }}
          </button>
        </div>
        <div class="mt-4 p-3 rounded-xl bg-titanium-50 dark:bg-titanium-800/50 border border-titanium-200 dark:border-titanium-700">
          <p class="text-xs text-titanium-600 dark:text-titanium-400 font-subheading leading-relaxed">
            <Icon name="lucide:info" class="w-3.5 h-3.5 inline -mt-0.5 mr-1" />
            Share your referral link with friends and clients. When they purchase any Mintreu product, you earn a commission on their first purchase.
          </p>
        </div>
      </DashboardDashboardCard>
    </div>

    <!-- Stats Row -->
    <div class="referral-section grid grid-cols-1 sm:grid-cols-3 gap-4">
      <DashboardStatCard
        label="Total earned"
        :value="formatCurrency(stats.total_earned)"
        icon="lucide:wallet"
        color="emerald"
        subtitle="Lifetime earnings"
        :sparkline-data="earningsSparkline"
      />
      <DashboardStatCard
        label="Pending payout"
        :value="formatCurrency(stats.pending_payout)"
        icon="lucide:clock"
        color="amber"
        subtitle="Awaiting approval"
      />
      <DashboardStatCard
        label="Available balance"
        :value="formatCurrency(stats.available_balance)"
        icon="lucide:banknote"
        color="blue"
        subtitle="Ready to withdraw"
      />
    </div>

    <!-- Charts Row -->
    <div class="referral-section grid grid-cols-1 lg:grid-cols-5 gap-4">
      <!-- Earnings Chart -->
      <div class="lg:col-span-3">
        <DashboardDashboardCard title="Monthly earnings" subtitle="Commission income over time">
          <div class="flex items-end justify-center py-2 overflow-x-auto">
            <DashboardMiniBarChart
              :data="earningsChartData"
              color="#059669"
              :height="160"
            />
          </div>
        </DashboardDashboardCard>
      </div>

      <!-- Performance -->
      <div class="lg:col-span-2">
        <DashboardDashboardCard title="Performance" subtitle="Referral metrics">
          <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-3 rounded-xl bg-titanium-50 dark:bg-titanium-800/50 border border-titanium-200 dark:border-titanium-700">
              <p class="text-2xl font-heading font-black text-titanium-900 dark:text-white">{{ stats.total_referrals }}</p>
              <p class="text-[10px] text-titanium-500 dark:text-titanium-400 font-subheading font-semibold uppercase tracking-wider mt-0.5">Total referrals</p>
            </div>
            <div class="text-center p-3 rounded-xl bg-titanium-50 dark:bg-titanium-800/50 border border-titanium-200 dark:border-titanium-700">
              <p class="text-2xl font-heading font-black text-emerald-600 dark:text-emerald-400">{{ stats.active_referrals }}</p>
              <p class="text-[10px] text-titanium-500 dark:text-titanium-400 font-subheading font-semibold uppercase tracking-wider mt-0.5">Active referrals</p>
            </div>
            <div class="col-span-2 flex items-center justify-center py-2">
              <DashboardProgressArc
                :value="stats.active_referrals"
                :max="Math.max(stats.total_referrals, 1)"
                color="#059669"
                :size="100"
                label="Conversion"
              />
            </div>
          </div>
        </DashboardDashboardCard>
      </div>
    </div>

    <!-- Commission History -->
    <div class="referral-section">
      <DashboardDashboardCard title="Commission history" subtitle="All referral transactions">
        <DashboardSkeletonLoader v-if="pending" variant="row" :count="3" />

        <DashboardEmptyState
          v-else-if="commissions.length === 0"
          title="No commissions yet"
          description="Start sharing your referral link to earn commissions. When someone makes a purchase through your link, it will appear here."
          action-label="Copy Referral Link"
          @action="copyLink"
        />

        <div v-else>
          <!-- Table -->
          <div class="overflow-x-auto -mx-6">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-titanium-200 dark:border-titanium-700">
                  <th class="text-left px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Date</th>
                  <th class="text-left px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Product</th>
                  <th class="text-left px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400 hidden sm:table-cell">Buyer</th>
                  <th class="text-right px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Commission</th>
                  <th class="text-center px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-titanium-500 dark:text-titanium-400">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="commission in commissions"
                  :key="commission.id"
                  class="border-b border-titanium-100 dark:border-titanium-800 last:border-0 hover:bg-titanium-50 dark:hover:bg-titanium-800/30 transition-colors"
                >
                  <td class="px-6 py-3 text-titanium-600 dark:text-titanium-400 whitespace-nowrap">
                    {{ new Date(commission.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }}
                  </td>
                  <td class="px-6 py-3 font-semibold text-titanium-900 dark:text-white truncate max-w-[200px]">
                    {{ commission.product_title }}
                  </td>
                  <td class="px-6 py-3 text-titanium-600 dark:text-titanium-400 hidden sm:table-cell">
                    {{ commission.buyer_name }}
                  </td>
                  <td class="px-6 py-3 text-right font-heading font-bold text-titanium-900 dark:text-white whitespace-nowrap">
                    {{ formatCurrency(commission.amount) }}
                  </td>
                  <td class="px-6 py-3 text-center">
                    <span
                      class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                      :class="statusColors[commission.status] || statusColors.pending"
                    >
                      {{ commission.status }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="commissionsMeta && commissionsMeta.last_page > 1" class="mt-4 pt-4 border-t border-titanium-200 dark:border-titanium-700">
            <DashboardPaginationControls
              :current-page="page"
              :last-page="commissionsMeta.last_page"
              :total="commissionsMeta.total"
              @page="(p: number) => { page = p; fetchData() }"
            />
          </div>
        </div>
      </DashboardDashboardCard>
    </div>

    <!-- How It Works -->
    <div class="referral-section">
      <DashboardDashboardCard title="How referrals work">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="text-center p-4 rounded-xl bg-titanium-50 dark:bg-titanium-800/50 border border-titanium-200 dark:border-titanium-700">
            <div class="w-10 h-10 rounded-full bg-mintreu-red-100 dark:bg-mintreu-red-900/30 flex items-center justify-center mx-auto mb-3">
              <Icon name="lucide:link" class="w-5 h-5 text-mintreu-red-600 dark:text-mintreu-red-400" />
            </div>
            <h4 class="text-sm font-heading font-bold text-titanium-900 dark:text-white mb-1">1. Share your link</h4>
            <p class="text-xs text-titanium-500 dark:text-titanium-400 font-subheading">Send your unique referral link to friends, colleagues, or clients.</p>
          </div>
          <div class="text-center p-4 rounded-xl bg-titanium-50 dark:bg-titanium-800/50 border border-titanium-200 dark:border-titanium-700">
            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-3">
              <Icon name="lucide:shopping-cart" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
            </div>
            <h4 class="text-sm font-heading font-bold text-titanium-900 dark:text-white mb-1">2. They purchase</h4>
            <p class="text-xs text-titanium-500 dark:text-titanium-400 font-subheading">When someone buys through your link, the sale is tracked automatically.</p>
          </div>
          <div class="text-center p-4 rounded-xl bg-titanium-50 dark:bg-titanium-800/50 border border-titanium-200 dark:border-titanium-700">
            <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mx-auto mb-3">
              <Icon name="lucide:banknote" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
            </div>
            <h4 class="text-sm font-heading font-bold text-titanium-900 dark:text-white mb-1">3. Earn commission</h4>
            <p class="text-xs text-titanium-500 dark:text-titanium-400 font-subheading">You receive a commission for every successful purchase they make.</p>
          </div>
        </div>
      </DashboardDashboardCard>
    </div>
  </div>
</template>
