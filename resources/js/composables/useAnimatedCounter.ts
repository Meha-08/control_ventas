import { ref, onMounted, onUnmounted } from 'vue'

export function useAnimatedCounter(target: number, duration = 2000) {
  const count = ref(0)
  const startTime = ref<number | null>(null)
  let rafId: number | null = null

  const animate = (timestamp: number) => {
    if (!startTime.value) startTime.value = timestamp
    const elapsed = timestamp - startTime.value
    const progress = Math.min(elapsed / duration, 1)
    const eased = progress * (2 - progress)
    count.value = Math.round(eased * target)
    if (progress < 1) {
      rafId = requestAnimationFrame(animate)
    }
  }

  const start = () => {
    startTime.value = null
    if (rafId) cancelAnimationFrame(rafId)
    rafId = requestAnimationFrame(animate)
  }

  onMounted(start)
  onUnmounted(() => {
    if (rafId) cancelAnimationFrame(rafId)
  })

  return { count, start }
}
