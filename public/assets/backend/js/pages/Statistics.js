export default class Statistics {
    constructor() {}

    init() {
        this.#initTabs();
        this.#initSubTabs();
    }

    #initTabs() {
        const tabs = document.querySelectorAll('.statsPage .tabs .tab');
        const contents = document.querySelectorAll('.statsPage > .tabContent');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = tab.getAttribute('data-tab');

                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                contents.forEach(c => {
                    if (c.getAttribute('data-tab') === target) {
                        c.classList.add('active');
                    } else {
                        c.classList.remove('active');
                    }
                });
            });
        });
    }

    #initSubTabs() {
        document.querySelectorAll('.subTabs').forEach(subTabsContainer => {
            const subTabs = subTabsContainer.querySelectorAll('.subTab');
            const section = subTabsContainer.closest('.statsSection');
            const subContents = section.querySelectorAll('.subTabContent');

            subTabs.forEach(subTab => {
                subTab.addEventListener('click', () => {
                    const target = subTab.getAttribute('data-subtab');

                    subTabs.forEach(t => t.classList.remove('active'));
                    subTab.classList.add('active');

                    subContents.forEach(c => {
                        if (c.getAttribute('data-subtab') === target) {
                            c.classList.add('active');
                        } else {
                            c.classList.remove('active');
                        }
                    });
                });
            });
        });
    }
}
