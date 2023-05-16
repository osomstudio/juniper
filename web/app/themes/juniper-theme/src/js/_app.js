import '../css/_app.scss';
// import pageTransitions from './animations/transitions';
// import * as basicScroll from 'basicscroll'
import utils from './utils';
import nav from './nav';

export default class App {
  constructor() {
    this.initDev(); // tests
    this.initBody(); // runs on initialize & after barba
  }

  initBody() {
    // sliders();
  }

  initCore() {
    // pageTransitions(); // barbaajs
    nav.hamburger();
    console.log('core');
  }

  initDev() {
    utils.clone('[data-clone]');
  }
}

function LoadApp() {
  const app = new App();
  app.initCore(); // runs on initialize
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', LoadApp);
} else {
  LoadApp();
}
