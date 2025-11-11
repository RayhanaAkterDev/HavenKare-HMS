// Safe initialization for Glide and Accord
document.addEventListener('DOMContentLoaded', function () {
	if (document.querySelector('.glide')) {
		new Glide('.glide', {
			type: 'carousel',
			autoplay: 1200,
			perView: 1,
			gap: 0,
		}).mount()
	}

	if (document.querySelector('.accord')) {
		new Accord($('.accord'))
	}
})
