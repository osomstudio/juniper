/* eslint-disable max-len */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

const sectionTracker = {
  init() {
    this.appendTrackerItems();
    this.trackScrollPosition();
  },
  appendTrackerItems() {
    const sectionTrackerWrapper = document.querySelector('.section-tracker');
    const sectionTrackerElement = sectionTrackerWrapper.querySelector('.section-tracker__list');
    const sections = sectionTrackerWrapper.parentNode.parentNode.querySelectorAll('.wp-block-group');

    if (sections.length === 0 && !sectionTrackerElement) {
      return;
    }
    const trackerItems = [];
    sections.forEach(
      (item) => {
        const sectionID = item.id;
        if (sectionID) {
          const sectionTitle = sectionTracker.getSectionTitle(item);
          const sectionItem = sectionTracker.createNavigationItem(sectionID, sectionTitle);
          trackerItems.push(sectionItem);
        }
      },
    );

    sectionTracker.addItemsToContainerList(trackerItems);
  },
  getSectionTitle(section) {
    let sectionTitle = section.querySelectorAll('h1, h2, h3, h4, h5, h6');
    let headingText = null;
    if (sectionTitle.length === 0) {
      sectionTitle = section.id;
      if (!sectionTitle) {
        return 'Section';
      }
      return sectionTitle;
    }
    sectionTitle.forEach(
      (heading, index) => {
        if (index === 0) {
          headingText = heading.innerText;
        }
      },
    );
    return headingText;
  },
  createNavigationItem(ID, title) {
    const listItem = document.createElement('li');
    const navigationItem = document.createElement('a');
    navigationItem.href = `#${ID}`;
    navigationItem.innerText = title;
    navigationItem.addEventListener('click', sectionTracker.clickBehaviour);
    listItem.append(navigationItem);
    return listItem;
  },
  addItemsToContainerList(items) {
    const sectionTrackerElement = document.querySelector('.section-tracker__list');
    if (!sectionTrackerElement) {
      return;
    }
    items.forEach(
      (item) => {
        sectionTrackerElement.append(item);
      },
    );
  },
  clickBehaviour(item) {
    const parentItem = item.target.parentNode;
    parentItem.parentNode.querySelectorAll('li').forEach(
      (li) => {
        li.classList.remove('active');
      },
    );
    if (!parentItem.classList.contains('active')) {
      parentItem.classList.add('active');
    }
  },
  trackScrollPosition() {
    const sections = document.querySelectorAll('.wp-block-group[id]');

    gsap.registerPlugin(ScrollTrigger);
    document.addEventListener('wheel, touch', () => ScrollTrigger.update());

    sections.forEach((section) => {
      ScrollTrigger.create({
        trigger: `#${section.id}`,
        start: 'top center',
        end: 'bottom center',
        markers: false,
        onToggle: (self) => {
          const currentActiveItem = document.querySelector(`.section-tracker__list a[href="#${section.id}"]`);
          const parentCurrentActiveItem = currentActiveItem.parentNode;

          if (self.isActive) {
            parentCurrentActiveItem.classList.add('active');
          } else {
            parentCurrentActiveItem.classList.remove('active');
          }
        },
      });
    });
  },
};

export default sectionTracker;
sectionTracker.init();
