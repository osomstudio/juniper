import barba from '@barba/core';
import barbaPrefetch from '@barba/prefetch';
import App from '../_app';

export default function () {
	barba.use(barbaPrefetch);
	barba.init({
		transitions: [{
			leave: function (data) {
				var done = this.async();
				done();
				document.body.classList.remove('nav--toggled');
				document.querySelector('header').scrollTo({behavior: 'smooth'});
			},
			enter: function (data) {
				var done = this.async();
				done(); // call on transition end
				
				let app = new App; // reinit everything inside .main
			},
		}]
	});
};