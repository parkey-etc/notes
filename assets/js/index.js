const menuToggle = document.getElementById('menu-toggle');
const sidebar = document.getElementById('sidebar');
const closeSidebar = document.getElementById('close-sidebar');
if (menuToggle && sidebar && closeSidebar) {
    menuToggle.addEventListener('click', () => sidebar.classList.add('pk-sidebar--open'));
    closeSidebar.addEventListener('click', () => sidebar.classList.remove('pk-sidebar--open'));
}

const themeToggleButton = document.getElementById('theme-toggle');
if (themeToggleButton) {
    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        if (currentTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'light');
            themeToggleButton.innerHTML = '<i class="material-icons">dark_mode</i>';
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            themeToggleButton.innerHTML = '<i class="material-icons">light_mode</i>';
            localStorage.setItem('theme', 'dark');
        }
    }
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
        themeToggleButton.innerHTML = savedTheme === 'dark' ? '<i class="material-icons">light_mode</i>' : '<i class="material-icons">dark_mode</i>';
    }
    themeToggleButton.addEventListener('click', toggleTheme);
}
const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
  document.documentElement.setAttribute('data-theme', savedTheme);
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('button');
    if (button && button.dataset.redirectB) {
        window.location.href = button.dataset.redirectB;
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const fields = document.querySelectorAll('[maxlength]');
    fields.forEach(field => {
        const counter = document.createElement('div');
        counter.classList.add('char-count');
        field.parentElement.appendChild(counter);
        function updateCharCount() {
            const maxLength = field.getAttribute('maxlength');
            const currentLength = field.value.length;
            counter.textContent = `${currentLength}/${maxLength}`;
        }
        updateCharCount();
        field.addEventListener('input', updateCharCount);
    });
});