const loader = document.getElementById('loader-overlay')

// Lock scroll while loader is active
document.body.classList.add('overflow-hidden')
document.documentElement.classList.add('overflow-hidden')

window.addEventListener('load', () => {
	// fade out loader
	loader.classList.add('opacity-0')

	// remove scroll lock after fade
	setTimeout(() => {
		loader.remove()
		document.body.classList.remove('overflow-hidden')
		document.documentElement.classList.remove('overflow-hidden')
	}, 1500) // match fade duration
})
