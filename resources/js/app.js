import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    // Function to close all dropdowns
    function closeAllDropdowns() {
        const allDropdowns = document.querySelectorAll('ul[id^="categoryDropdown-"], #calculatorDropdown, #userDropdown');
        allDropdowns.forEach(dropdown => dropdown.classList.add('hidden'));
    }

    // Attach event listeners for category dropdown toggles
    const categoryDropdownToggles = document.querySelectorAll('[id^="categoryDropdownToggle-"]');

    // Loop through each category dropdown toggle and set up event listener
    categoryDropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            const dropdownId = toggle.getAttribute('id').replace('Toggle', ''); // Get dropdown ID
            const dropdown = document.getElementById(dropdownId); // Get the corresponding dropdown element

            if (dropdown) {
                // Close all other dropdowns before opening the clicked one
                closeAllDropdowns();

                // Toggle the clicked dropdown's visibility
                dropdown.classList.toggle('hidden');
            }
        });
    });

    // Calculator dropdown toggle
    const calculatorDropdownToggle = document.getElementById('calculatorDropdownToggle');
    if (calculatorDropdownToggle) {
        calculatorDropdownToggle.addEventListener('click', () => {
            closeAllDropdowns(); // Close other dropdowns before showing the calculator dropdown
            const calculatorDropdown = document.getElementById('calculatorDropdown');
            if (calculatorDropdown) {
                calculatorDropdown.classList.toggle('hidden');
            }
        });
    }

    // User dropdown toggle
    const userDropdownToggle = document.getElementById('userDropdownToggle');
    if (userDropdownToggle) {
        userDropdownToggle.addEventListener('click', () => {
            closeAllDropdowns(); // Close other dropdowns before showing the user dropdown
            const userDropdown = document.getElementById('userDropdown');
            if (userDropdown) {
                userDropdown.classList.toggle('hidden');
            }
        });
    }

    // Optional: Close dropdowns when clicking outside of them
    document.addEventListener('click', (event) => {
        const targetElement = event.target; // Clicked element

        // Check if the click is outside any dropdown or its toggle
        if (!targetElement.closest('li.relative')) {
            closeAllDropdowns(); // Close all dropdowns if clicked outside
        }
    });
});

function toggleDarkMode() {
    const htmlElement = document.documentElement;
    const isDarkMode = htmlElement.classList.toggle('dark');

    // Store user preference in localStorage
    localStorage.setItem('darkMode', isDarkMode);

    // Update button visibility based on dark mode
    updateDarkModeButton(isDarkMode);
}

// Make sure this function is also in the global scope
function updateDarkModeButton(isDarkMode) {
    const darkModeToggleOn = document.querySelector('#dark-mode-toggle-on');
    const darkModeToggleOff = document.querySelector('#dark-mode-toggle-off');

    if (isDarkMode) {
        darkModeToggleOn.classList.remove('hidden');
        darkModeToggleOff.classList.add('hidden');
    } else {
        darkModeToggleOn.classList.add('hidden');
        darkModeToggleOff.classList.remove('hidden');
    }
}

// Initial setup to check user preference and set state accordingly
const preferDark = localStorage.getItem('darkMode') === 'true';
if (preferDark) {
    document.documentElement.classList.add('dark');
}

updateDarkModeButton(preferDark);

window.toggleDarkMode = function toggleDarkMode() {
    const htmlElement = document.documentElement;
    const isDarkMode = htmlElement.classList.toggle('dark');

    // Store user preference in localStorage
    localStorage.setItem('darkMode', isDarkMode);

    // Update button visibility based on dark mode
    updateDarkModeButton(isDarkMode);
};

// Also expose `updateDarkModeButton` if needed
window.updateDarkModeButton = function updateDarkModeButton(isDarkMode) {
    const darkModeToggleOn = document.querySelector('#dark-mode-toggle-on');
    const darkModeToggleOff = document.querySelector('#dark-mode-toggle-off');

    if (isDarkMode) {
        darkModeToggleOn.classList.remove('hidden');
        darkModeToggleOff.classList.add('hidden');
    } else {
        darkModeToggleOn.classList.add('hidden');
        darkModeToggleOff.classList.remove('hidden');
    }
};