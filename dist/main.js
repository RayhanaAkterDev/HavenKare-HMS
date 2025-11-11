document.addEventListener('DOMContentLoaded', function () {
	const submenuBtns = document.querySelectorAll(
		'.dashboard-sidebar__nav-item--has-sub'
	)

	function findChevron(btn) {
		return btn.querySelector('.dashboard-sidebar__nav-dropdown-icon')
	}

	function toggleSubmenu(btn) {
		const submenu = btn.nextElementSibling
		if (!submenu) return
		const chevron = findChevron(btn)
		const isOpen = submenu.classList.contains('open')

		if (isOpen) {
			submenu.classList.remove('open')
			if (chevron) chevron.style.transform = 'rotate(0deg)'
		} else {
			submenu.classList.add('open')
			if (chevron) chevron.style.transform = 'rotate(180deg)'
		}
	}

	// CLOSE all submenus by default
	document
		.querySelectorAll('.dashboard-sidebar__sub-menu')
		.forEach((sub) => sub.classList.remove('open'))

	submenuBtns.forEach((btn) => {
		btn.addEventListener('click', function (e) {
			e.preventDefault() // prevent page reload
			toggleSubmenu(btn)
		})
	})

	// Highlight only active file link
	const currentPage = window.location.pathname.split('/').pop()
	document
		.querySelectorAll(
			'.dashboard-sidebar__nav-item, .dashboard-sidebar__sub-item'
		)
		.forEach((link) => {
			const href = link.getAttribute('href')
			if (href && href.includes(currentPage)) {
				link.classList.add('dashboard-sidebar__nav-item--active')

				// Open parent submenu if child is active
				const submenu = link.closest('.dashboard-sidebar__sub-menu')
				if (submenu) {
					submenu.classList.add('open')
					const parentBtn = submenu.previousElementSibling
					const chevron = parentBtn?.querySelector(
						'.dashboard-sidebar__nav-dropdown-icon'
					)
					if (chevron) chevron.style.transform = 'rotate(180deg)'
				}
			}
		})
})

// ===================================================
// ===== Sidebar toggle for mobile =====
const sidebar = document.getElementById('sidebar')
const mobileToggle = document.getElementById('mobileToggle')

// Open sidebar on hamburger click
mobileToggle.addEventListener('click', (e) => {
	e.stopPropagation() // prevent event bubbling
	sidebar.classList.toggle('-translate-x-full') // hide/show
})

// Close sidebar if click outside (anywhere on content)
document.addEventListener('click', (e) => {
	if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
		if (!sidebar.classList.contains('-translate-x-full')) {
			sidebar.classList.add('-translate-x-full')
		}
	}
})

// Optional: close sidebar on link click inside sidebar (for mobile)
const sidebarLinks = sidebar.querySelectorAll('a, button')

sidebarLinks.forEach((link) => {
	link.addEventListener('click', (e) => {
		// Ignore clicks on buttons that have submenus
		if (link.hasAttribute('data-has-sub')) return

		// Only close sidebar for real navigation links on mobile
		if (window.innerWidth < 1024) {
			sidebar.classList.add('-translate-x-full')
		}
	})
})

// ===================================================

// profile submenu open
document.addEventListener('DOMContentLoaded', () => {
	const profileDropdown = document.getElementById('profileDropdown')

	// Toggle menu on click
	profileDropdown.addEventListener('click', (e) => {
		e.stopPropagation() // prevent click from bubbling up
		profileDropdown.classList.toggle('active')
	})

	// Close menu if clicked outside
	document.addEventListener('click', () => {
		profileDropdown.classList.remove('active')
	})
})
