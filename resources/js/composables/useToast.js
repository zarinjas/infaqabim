import { useToastStore } from '../stores/toast'

/**
 * Convenience composable for firing toast notifications from any component.
 * Usage: const toast = useToast(); toast.success('Saved!'); toast.error('Failed');
 */
export function useToast() {
  const store = useToastStore()

  return {
    success: (message, duration) => store.push(message, 'success', duration),
    error: (message, duration) => store.push(message, 'danger', duration),
    info: (message, duration) => store.push(message, 'info', duration),
    warning: (message, duration) => store.push(message, 'warning', duration),
  }
}
