/**
 * Ensures an external URL has a valid protocol (https://) so browsers
 * do not treat it as a relative path on localhost or the domain.
 *
 * @param {string|null|undefined} url
 * @returns {string}
 */
export function formatExternalUrl(url) {
  if (!url) return ''
  const trimmed = String(url).trim()
  if (!trimmed) return ''
  
  if (/^https?:\/\//i.test(trimmed)) {
    return trimmed
  }
  
  if (trimmed.startsWith('//')) {
    return `https:${trimmed}`
  }
  
  return `https://${trimmed}`
}

/**
 * Direct web Gmail compose link generator.
 * Opens Gmail in a browser tab without triggering the Windows mailto modal.
 */
export function getGmailComposeUrl({ to = '', subject = '', body = '' } = {}) {
  const params = new URLSearchParams()
  params.set('view', 'cm')
  params.set('fs', '1')
  if (to) params.set('to', to)
  if (subject) params.set('su', subject)
  if (body) params.set('body', body)
  return `https://mail.google.com/mail/?${params.toString()}`
}
