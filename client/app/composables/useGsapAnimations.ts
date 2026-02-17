import { ref, onMounted, onUnmounted, type Ref } from 'vue'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

export function useGsapAnimations() {
  const ctx = ref<gsap.Context | null>(null)

  /**
   * Initialize GSAP context scoped to a container element.
   * All animations created inside the callback are auto-cleaned on unmount.
   */
  const initContext = (container: Ref<HTMLElement | null>, setupFn: (self: gsap.Context) => void) => {
    onMounted(() => {
      if (!container.value) return
      ctx.value = gsap.context(setupFn, container.value)
    })

    onUnmounted(() => {
      ctx.value?.revert()
    })
  }

  /**
   * Fade-in-up animation triggered on scroll
   */
  const scrollFadeInUp = (selector: string, options?: {
    stagger?: number
    duration?: number
    y?: number
    delay?: number
    start?: string
  }) => {
    gsap.from(selector, {
      y: options?.y ?? 60,
      opacity: 0,
      duration: options?.duration ?? 0.8,
      stagger: options?.stagger ?? 0.15,
      delay: options?.delay ?? 0,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: selector,
        start: options?.start ?? 'top 85%',
        toggleActions: 'play none none none',
      },
    })
  }

  /**
   * Technical line-draw reveal (for border/divider elements)
   */
  const scrollLineReveal = (selector: string) => {
    gsap.from(selector, {
      scaleX: 0,
      transformOrigin: 'left center',
      duration: 1.2,
      ease: 'power2.out',
      scrollTrigger: {
        trigger: selector,
        start: 'top 90%',
        toggleActions: 'play none none none',
      },
    })
  }

  /**
   * Count-up animation for stat numbers
   */
  const countUp = (element: HTMLElement, target: number, options?: {
    duration?: number
    suffix?: string
    prefix?: string
  }) => {
    const obj = { value: 0 }
    gsap.to(obj, {
      value: target,
      duration: options?.duration ?? 2,
      ease: 'power1.out',
      onUpdate: () => {
        element.textContent = `${options?.prefix ?? ''}${Math.round(obj.value)}${options?.suffix ?? ''}`
      },
      scrollTrigger: {
        trigger: element,
        start: 'top 85%',
        toggleActions: 'play none none none',
      },
    })
  }

  /**
   * Staggered card entrance from different directions
   */
  const scrollStaggerCards = (selector: string, options?: {
    fromDirection?: 'left' | 'right' | 'bottom'
    stagger?: number
  }) => {
    const direction = options?.fromDirection ?? 'bottom'
    const from: gsap.TweenVars = {
      opacity: 0,
      duration: 0.8,
      stagger: options?.stagger ?? 0.2,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: selector,
        start: 'top 85%',
        toggleActions: 'play none none none',
      },
    }

    if (direction === 'left') from.x = -80
    else if (direction === 'right') from.x = 80
    else from.y = 60

    gsap.from(selector, from)
  }

  /**
   * Parallax scroll effect for background elements
   */
  const scrollParallax = (selector: string, speed: number = 0.5) => {
    gsap.to(selector, {
      yPercent: -20 * speed,
      ease: 'none',
      scrollTrigger: {
        trigger: selector,
        start: 'top bottom',
        end: 'bottom top',
        scrub: true,
      },
    })
  }

  /**
   * Character-by-character text reveal animation
   */
  const splitTextReveal = (element: HTMLElement, options?: {
    duration?: number
    stagger?: number
  }) => {
    const text = element.textContent || ''
    element.textContent = ''
    element.style.visibility = 'visible'

    const chars = text.split('').map((char) => {
      const span = document.createElement('span')
      span.textContent = char === ' ' ? '\u00A0' : char
      span.style.display = 'inline-block'
      span.style.opacity = '0'
      span.style.transform = 'translateY(20px)'
      element.appendChild(span)
      return span
    })

    gsap.to(chars, {
      opacity: 1,
      y: 0,
      duration: options?.duration ?? 0.05,
      stagger: options?.stagger ?? 0.03,
      ease: 'power2.out',
      scrollTrigger: {
        trigger: element,
        start: 'top 80%',
        toggleActions: 'play none none none',
      },
    })
  }

  /**
   * Mechanical "build-in" effect for cards (scale + rotate from blueprint)
   */
  const mechanicalBuildIn = (selector: string, stagger: number = 0.3) => {
    gsap.from(selector, {
      scale: 0.8,
      rotateX: 15,
      opacity: 0,
      duration: 1,
      stagger,
      ease: 'back.out(1.2)',
      scrollTrigger: {
        trigger: selector,
        start: 'top 85%',
        toggleActions: 'play none none none',
      },
    })
  }

  /**
   * Gauge fill animation (horizontal bar fill from left)
   */
  const gaugeAnimate = (selector: string, stagger: number = 0.15) => {
    gsap.from(selector, {
      scaleX: 0,
      transformOrigin: 'left center',
      duration: 1.2,
      stagger,
      ease: 'power2.out',
      scrollTrigger: {
        trigger: selector,
        start: 'top 80%',
        toggleActions: 'play none none none',
      },
    })
  }

  /**
   * Hero entrance timeline (for coordinated hero animations)
   */
  const heroEntrance = (selectors: {
    badge?: string
    heading?: string
    subheading?: string
    cta?: string
    stats?: string
  }) => {
    const tl = gsap.timeline({ delay: 0.3 })

    if (selectors.badge) {
      tl.from(selectors.badge, { y: -30, opacity: 0, duration: 0.6, ease: 'power3.out' })
    }
    if (selectors.heading) {
      tl.from(selectors.heading, { y: 40, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.3')
    }
    if (selectors.subheading) {
      tl.from(selectors.subheading, { y: 30, opacity: 0, duration: 0.7, ease: 'power3.out' }, '-=0.4')
    }
    if (selectors.cta) {
      tl.from(selectors.cta, { y: 20, opacity: 0, duration: 0.6, ease: 'power3.out' }, '-=0.3')
    }
    if (selectors.stats) {
      tl.from(selectors.stats, { y: 30, opacity: 0, duration: 0.6, stagger: 0.1, ease: 'power3.out' }, '-=0.2')
    }

    return tl
  }

  return {
    initContext,
    scrollFadeInUp,
    scrollLineReveal,
    countUp,
    scrollStaggerCards,
    scrollParallax,
    splitTextReveal,
    mechanicalBuildIn,
    gaugeAnimate,
    heroEntrance,
  }
}
