import nav from './nav';

export default class App {
  initCore() {
    nav.hamburger();
  }
}

function LoadApp() {
  const app = new App();
  app.initCore();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', LoadApp);
} else {
  LoadApp();
}
