/**
 * Translate a key using the translations provided by the server
 * 
 * @param {string} key - The translation key
 * @param {string} fallback - Fallback text if translation is not found
 * @param {object} replacements - Object with replacements for placeholders
 * @returns {string} - The translated text
 */
export function __(key, fallback = '', replacements = {}) {
  const language = localStorage.getItem('language') || 'uk';
  
  // Get translations from the page props
  const translations = window.Inertia?.page?.props?.translations;
  
  let text = '';
  
  if (translations && translations[language] && translations[language][key]) {
    text = translations[language][key];
  } else {
    text = fallback || key;
  }
  
  // Replace placeholders
  if (replacements && typeof replacements === 'object') {
    Object.keys(replacements).forEach(placeholder => {
      text = text.replace(new RegExp(`:${placeholder}`, 'g'), replacements[placeholder]);
    });
  }
  
  return text;
} 