import barba from '@barba/core';
import barbaPrefetch from '@barba/prefetch';
import App from '../_app';

export default function () {
  barba.use(barbaPrefetch);
  barba.init({
    transitions: [{
      leave(data) { // eslint-disable-line no-unused-vars
        const done = this.async();
        done();
        document.body.classList.remove('nav--toggled');
        document.querySelector('header').scrollTo({ behavior: 'smooth' });
      },
      enter(data) { // eslint-disable-line no-unused-vars
        const done = this.async();
        done(); // call on transition end

        App(); // reinit everything inside .main
      },
    }],
  });
}
