<template>
    <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-500">
        <!-- Background Body of the Logo -->
        <rect x="15" y="15" width="70" height="70" rx="20" fill="currentColor" fill-opacity="0.15" />

        <!-- The "Smart Slot" Path -->
        <path d="M30 50L45 65L75 35" stroke="currentColor" stroke-width="12" stroke-linecap="round"
            stroke-linejoin="round" />

        <!-- Accent Geometric Point (Represents the 'Smart' aspect) -->
        <circle cx="75" cy="25" r="8" fill="currentColor" />

        <!-- Bottom Structural Bar (Represents the Calendar/Grid) -->
        <rect x="30" y="75" width="25" height="8" rx="4" fill="currentColor" />
    </svg>
</template>

<style scoped>
/* Adds a subtle hover animation if used outside a group */
svg:hover {
    transform: scale(1.05);
    filter: drop-shadow(0 0 8px currentColor);
}
</style>