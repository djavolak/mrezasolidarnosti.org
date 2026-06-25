export default class LineGrid {

    #initComplete = false;
    #maxProgress = 0;

    container;
    progressLine;
    collapsibleHandles;
    collapsibleObserver;
    constructor(container) {
        this.container = container;
    }

    init() {
        if (this.#initComplete) {
            throw new Error('GridLine is already initialized');
        }
        this.collapsibleHandles = this.container.querySelectorAll('.collapsibleHandle');
        this.progressLine = this.container.querySelector('.mainLine');
        this.#addListeners();
        this.#initComplete = true;
    }


    #addListeners() {
        this.#addCollapsibleListeners();
        this.#addScrollListener();
        this.#addScrollActivation();
    }


    #addCollapsibleListeners() {
        this.collapsibleHandles.forEach((handle) => {
           handle.addEventListener('click', this.collapsibleHandleCallback)
        });
    }


    collapsibleHandleCallback = (e) => {
        const content = e.target.parentElement.querySelector('.collapsible');
        content.classList.toggle('active');
    };

    // Opens a row's collapsible(s) once scrolling reaches that row's center
    // icon / number (the viewport vertical midline). Once opened it stays open.
    #addScrollActivation() {
        const centers = [];
        this.container.querySelectorAll('.grid').forEach((grid) => {
            const center = grid.querySelector('.center');
            const collapsibles = grid.querySelectorAll('.collapsible');
            if (center && collapsibles.length) {
                centers.push(center);
            }
        });

        if (!centers.length) {
            return;
        }

        this.collapsibleObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    const grid = entry.target.closest('.grid');
                    if (grid) {
                        grid.querySelectorAll('.collapsible').forEach((collapsible) => {
                            collapsible.classList.add('active');
                        });
                    }
                    this.collapsibleObserver.unobserve(entry.target);
                });
            },
            {
                root: null,
                rootMargin: '-50% 0px -50% 0px',
                threshold: 0,
            }
        );

        centers.forEach((center) => this.collapsibleObserver.observe(center));
    }

    #addScrollListener() {
        window.addEventListener('scroll', this.onScroll, { passive: true });
        window.addEventListener('resize', this.onScroll);

        this.onScroll();
    }

    onScroll = () => {
        const rect = this.container.getBoundingClientRect();
        const viewportHeight = window.innerHeight;

        const start = viewportHeight * 0.55;

        const end = rect.height + viewportHeight * 0.15;

        const rawProgress = (start - rect.top) / end;

        const progress = Math.max(0, Math.min(1, rawProgress));
        
        this.#maxProgress = Math.max(this.#maxProgress, progress);

        const fill = this.#maxProgress * 100;

        this.progressLine.style.background = `
            linear-gradient(
                to bottom,
                rgba(38, 33, 133,1) 0%,
                rgba(38, 33, 133,1) ${fill}%,
                rgba(38,33,133,0.2) ${fill}%,
                rgba(38,33,133,0.2) 100%
            )
        `;
    };


    destroy() {
        if(this.collapsibleHandles) {
            this.collapsibleHandles.forEach((handle) => {
                handle.removeEventListener('click', this.collapsibleHandleCallback);
            });
        }
        if(this.collapsibleObserver) {
            this.collapsibleObserver.disconnect();
        }
        window.removeEventListener('scroll', this.onScroll);
        window.removeEventListener('resize', this.onScroll);
    }
}