<template>
  <svg viewBox="0 0 300 100" xmlns="http://www.w3.org/2000/svg">
    <!-- Background gradient -->
    <defs>
      <!-- Blue gradient for text -->
      <linearGradient id="textGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#4A90E2" />
        <stop offset="100%" stop-color="#357ABD" />
      </linearGradient>
      
      <!-- Blue gradient for decorative elements -->
      <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#6CB4EE" />
        <stop offset="50%" stop-color="#4A90E2" />
        <stop offset="100%" stop-color="#357ABD" />
      </linearGradient>
    </defs>
    
    <!-- Background shape -->
    <rect x="10" y="10" width="280" height="80" rx="10" fill="#f8f8f8" opacity="0.5" />
    
    <!-- Main text "Шкільний календар" -->
    <text x="150" y="60" font-family="'Segoe UI', 'Roboto', sans-serif" font-size="36" font-weight="500" text-anchor="middle" fill="url(#textGradient)">Шкільний календар</text>
    
    <!-- Decorative elements -->
    <rect x="50" y="85" width="200" height="2" rx="1" fill="url(#goldGradient)" opacity="0.7" />
  </svg>
</template>
