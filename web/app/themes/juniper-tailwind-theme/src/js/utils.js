const utils = {
  throttle: (func, limit) => {
    let inThrottle;
    return function () {
      const args = arguments;
      const context = this;
      if (!inThrottle) {
        func.apply(context, args);
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  },

  debounce: (func, delay) => {
    let inDebounce;
    return function () {
      const context = this;
      const args = arguments;
      clearTimeout(inDebounce);
      inDebounce = setTimeout(() => func.apply(context, args), delay);
    };
  },

  /**
	 * scale initial values between range
	 * @param {Number} num num
	 * @param {Number} in_min in_min
	 * @param {Number} in_max in_max
	 * @param {Number} out_min out_min
	 * @param {Number} out_max out_max
	 */
  scale: (num, in_min, in_max, out_min = 0, out_max = 100) => (num - in_min) * (out_max - out_min) / (in_max - in_min) + out_min,

  /**
	 * return random int
	 * @param {Number} min min
	 * @param {Number} max max
	 */
  random: (min = 0, max = 9999) => Math.floor(Math.random() * (max - min + 1) + min),

  /**
	 * Return a shuffled copy of an Array
	 * @param {Array} array array
	 */
  shuffle: (array) => array.slice().sort(() => Math.random() - 0.5),

  /**
	 * Flatterns multidimensional Array
	 * @param {Array} array array
	 */
  flatten: (array) => [].concat(...array),

  removeDupliates(array) {
    return [...new Set(array)];
  },

  getHex() {
    return `#${Math.floor(Math.random() * 0xffffff).toString(16).padEnd(6, '0')}`;
  },

  getQuery() {
    return document.location.search.replace(/(^\?)/, '').split('&').reduce((o, n) => { n = n.split('='); o[n[0]] = n[1]; return o; }, {});
  },

  /**
	 * Detect phone/tablet
	 */
  isMobile: () => {
    if (navigator.userAgent.match(/Android/i)
			|| navigator.userAgent.match(/webOS/i)
			|| navigator.userAgent.match(/iPhone/i)
			|| navigator.userAgent.match(/iPad/i)
			|| navigator.userAgent.match(/iPod/i)
			|| navigator.userAgent.match(/BlackBerry/i)
			|| navigator.userAgent.match(/Windows Phone/i)
    ) {
      return true;
    }

    return false;
  },

  /**
	 * Go to srollpos
	 * @param {Int} y Offset Y
	 */
  goTo: (selector, callback) => {
    document.body.scrollTop = document.documentElement.scrollTop = y;
  },

  getElem: (selector, callback) => {
    const selectors = document.querySelectorAll(selector);
    [].forEach.call(selectors, callback);
  },

  clone(el) {
    this.getElem(el, (element) => {
      const count = element.getAttribute('data-clone');
      for (let i = 0; i < +count - 1; i++) {
        const node = element.cloneNode(true);
        node.setAttribute('data-clone', 'clone');
        element.parentElement.appendChild(node);
      }
    });
  },
};

export default utils;
