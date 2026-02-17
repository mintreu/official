export function resolveApiError(error: unknown, fallback = 'Something went wrong. Please try again.') {
  if (!error) {
    return fallback
  }

  const normalized = error as { data?: { message?: string }; message?: string }

  if (normalized?.data?.message) {
    return normalized.data.message
  }

  if (typeof normalized?.message === 'string') {
    return normalized.message
  }

  return fallback
}
