<template>
  <section class="relative overflow-hidden" :class="bgGradient">
    <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px" />
    <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8 lg:py-28">
      <div class="max-w-xl">
        <h1 class="text-3xl font-bold leading-tight tracking-tight sm:text-4xl lg:text-5xl" :class="titleClass">
          {{ title }}
        </h1>
        <p class="mt-4 text-base leading-relaxed sm:text-lg" :class="subtitleClass">
          {{ subtitle }}
        </p>
        <div v-if="$slots.actions" class="mt-8 flex flex-col gap-3 sm:flex-row">
          <slot name="actions" />
        </div>
      </div>
    </div>
    <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full opacity-20 blur-3xl" :class="glowClass" />
    <div class="absolute -bottom-20 -left-20 h-60 w-60 rounded-full opacity-20 blur-3xl" :class="glowClass" />
  </section>
</template>

<script setup>
defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, default: '' },
  bgGradient: { type: String, default: 'bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-700' },
  titleClass: { type: String, default: 'text-white' },
  subtitleClass: { type: String, default: 'text-emerald-100' },
  glowClass: { type: String, default: 'bg-emerald-500/20' },
})
</script>
