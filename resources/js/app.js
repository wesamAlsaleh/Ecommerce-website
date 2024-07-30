import "./bootstrap";

import "preline";

document.addEventListener("livewire:navigated", () => {
    // Triggered as the final step of any page navigation...
    // Also triggered on page-load instead of "DOMContentLoaded"...
});
