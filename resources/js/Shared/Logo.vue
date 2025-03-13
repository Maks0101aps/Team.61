<template>
  <svg viewBox="0 0 300 100" xmlns="http://www.w3.org/2000/svg">
    <!-- Background gradient -->
    <defs>
      <!-- Text gradient -->
      <linearGradient id="textGradient" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#444444" />
        <stop offset="50%" stop-color="#222222" />
        <stop offset="100%" stop-color="#000000" />
      </linearGradient>
      
      <!-- Gold gradient for decorative elements -->
      <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#f6e27a" />
        <stop offset="50%" stop-color="#d4af37" />
        <stop offset="100%" stop-color="#c5a028" />
      </linearGradient>
      
      <!-- Drop shadow filter -->
      <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
        <feDropShadow dx="2" dy="3" stdDeviation="2" flood-color="#000000" flood-opacity="0.4" />
      </filter>
      
      <!-- 3D effect filter -->
      <filter id="bevel" x="-10%" y="-10%" width="120%" height="120%">
        <feGaussianBlur in="SourceAlpha" stdDeviation="1" result="blur" />
        <feSpecularLighting in="blur" surfaceScale="6" specularConstant="1" specularExponent="30" lighting-color="#FFFFFF" result="specOut">
          <fePointLight x="150" y="60" z="200" />
        </feSpecularLighting>
        <feComposite in="specOut" in2="SourceAlpha" operator="in" result="specOut" />
        <feComposite in="SourceGraphic" in2="specOut" operator="arithmetic" k1="0" k2="1" k3="1" k4="0" result="litPaint" />
      </filter>
      
      <!-- Glow effect -->
      <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
        <feGaussianBlur stdDeviation="2" result="blur" />
        <feComposite in="SourceGraphic" in2="blur" operator="over" />
      </filter>
    </defs>
    
    <!-- Background shape -->
    <rect x="10" y="10" width="280" height="80" rx="10" fill="#f8f8f8" opacity="0.5" />
    
    <!-- Main text "Шкільний календар" (School Calendar in Ukrainian) -->
    <g filter="url(#shadow)">
      <!-- First line with 3D effect -->
      <text x="150" y="45" font-family="Arial, sans-serif" font-size="30" font-weight="bold" text-anchor="middle" fill="url(#textGradient)" filter="url(#bevel)">Шкільний</text>
      
      <!-- Text shadow layers for depth -->
      <text x="151" y="46" font-family="Arial, sans-serif" font-size="30" font-weight="bold" text-anchor="middle" fill="rgba(0,0,0,0.1)">Шкільний</text>
      <text x="152" y="47" font-family="Arial, sans-serif" font-size="30" font-weight="bold" text-anchor="middle" fill="rgba(0,0,0,0.05)">Шкільний</text>
      
      <!-- Second line with 3D effect -->
      <text x="150" y="78" font-family="Arial, sans-serif" font-size="30" font-weight="bold" text-anchor="middle" fill="url(#textGradient)" filter="url(#bevel)">календар</text>
      
      <!-- Text shadow layers for second line -->
      <text x="151" y="79" font-family="Arial, sans-serif" font-size="30" font-weight="bold" text-anchor="middle" fill="rgba(0,0,0,0.1)">календар</text>
      <text x="152" y="80" font-family="Arial, sans-serif" font-size="30" font-weight="bold" text-anchor="middle" fill="rgba(0,0,0,0.05)">календар</text>
    </g>
    
    <!-- Decorative elements -->
    <rect x="50" y="85" width="200" height="3" rx="1.5" fill="url(#goldGradient)" filter="url(#glow)" />
    <rect x="80" y="90" width="140" height="2" rx="1" fill="url(#goldGradient)" opacity="0.7" />
    
    <!-- Calendar icon with gold accents -->
    <g transform="translate(245, 30) scale(0.6)">
      <rect x="0" y="0" width="40" height="40" rx="5" fill="url(#textGradient)" />
      <rect x="5" y="5" width="30" height="30" rx="3" fill="#ffffff" />
      <rect x="0" y="-5" width="40" height="10" rx="3" fill="url(#goldGradient)" />
      <rect x="10" y="15" width="5" height="5" rx="1" fill="#333333" />
      <rect x="18" y="15" width="5" height="5" rx="1" fill="#333333" />
      <rect x="26" y="15" width="5" height="5" rx="1" fill="#333333" />
      <rect x="10" y="23" width="5" height="5" rx="1" fill="#333333" />
      <rect x="18" y="23" width="5" height="5" rx="1" fill="#333333" />
      <rect x="26" y="23" width="5" height="5" rx="1" fill="#333333" />
    </g>
  </svg>
</template>
