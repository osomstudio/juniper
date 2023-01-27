const nav = {
	hamburger: () => {
		let hamburger = document.querySelector('.nav-toggle');
		if (!hamburger)
			return;

		let container = document.querySelector('body');
		hamburger.addEventListener('click', () => {
			hamburger.classList.toggle('nav--toggled')
			container.classList.toggle('nav--toggled')
		})
	}
}

export default nav;