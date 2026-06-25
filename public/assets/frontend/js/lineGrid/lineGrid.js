export default class LineGrid {

    #initComplete = false;
    #maxProgress = 0;
    #maxProgressMobile = 0;

    container;
    mobileContainer;
    progressLine;
    progressLineMobile;
    collapsibleHandles;
    collapsibleObserver;
    collapsibleHandlesMobile;
    constructor(container, mobileContainer) {
        this.container = container;
        this.mobileContainer = mobileContainer;
    }

    init() {
        if (this.#initComplete) {
            throw new Error('GridLine is already initialized');
        }
        this.collapsibleHandles = this.container.querySelectorAll('.collapsibleHandle');
        this.collapsibleHandlesMobile = this.mobileContainer.querySelectorAll('.collapsibleHandle');
        this.progressLine = this.container.querySelector('.mainLine');
        this.progressLineMobile = this.mobileContainer.querySelector('.mainLine');
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
        this.collapsibleHandlesMobile.forEach((handle) => {
            handle.addEventListener('click', this.collapsibleHandleCallback)
        });
    }


    collapsibleHandleCallback = (e) => {
        const content = e.target.parentElement.querySelector('.collapsible');
        content.classList.toggle('active');
    };


    // Opens collapsible(s) once scrolling reaches their anchor (the viewport
    // vertical midline). Once opened they stay open. Desktop rows anchor on the
    // grid's center icon/number; mobile collapsibles anchor on their handle.
    #addScrollActivation() {
        const targets = [];

        // Desktop: anchor each grid's collapsible(s) to the row's center icon/number.
        this.container.querySelectorAll('.grid').forEach((grid) => {
            const center = grid.querySelector('.center');
            const collapsibles = grid.querySelectorAll('.collapsible');
            if (center && collapsibles.length) {
                targets.push({anchor: center, collapsibles: Array.from(collapsibles)});
            }
        });

        // Mobile: anchor each collapsible to its own handle.
        this.mobileContainer.querySelectorAll('.collapsible').forEach((collapsible) => {
            const handle = collapsible.parentElement
                ? collapsible.parentElement.querySelector('.collapsibleHandle')
                : null;
            targets.push({anchor: handle || collapsible, collapsibles: [collapsible]});
        });

        if (!targets.length) {
            return;
        }

        this.collapsibleObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    targets
                        .filter((target) => target.anchor === entry.target)
                        .forEach((target) => {
                            target.collapsibles.forEach((collapsible) => collapsible.classList.add('active'));
                        });
                    this.collapsibleObserver.unobserve(entry.target);
                });
            },
            {
                root: null,
                rootMargin: '-50% 0px -50% 0px',
                threshold: 0,
            }
        );

        targets.forEach((target) => this.collapsibleObserver.observe(target.anchor));
    }

    #addScrollListener() {
        window.addEventListener('scroll', this.onScroll, { passive: true });
        window.addEventListener('resize', this.onScroll);

        this.onScroll();
    }

    onScroll = () => {
        this.#maxProgress = this.#fillProgressLine(this.container, this.progressLine, this.#maxProgress);
        this.#maxProgressMobile = this.#fillProgressLine(this.mobileContainer, this.progressLineMobile, this.#maxProgressMobile);
    };

    // Fills a main line based on how far the container has scrolled past the
    // viewport. The fill only ever advances (never retreats on scroll back).
    // Returns the updated high-water-mark progress.
    #fillProgressLine(container, progressLine, maxProgress) {
        if (!container || !progressLine) {
            return maxProgress;
        }

        const rect = container.getBoundingClientRect();

        // Hidden containers (display:none on the inactive breakpoint) report a
        // zero-height rect — skip them so their progress isn't falsely advanced.
        if (!rect.height) {
            return maxProgress;
        }

        const viewportHeight = window.innerHeight;
        const start = viewportHeight * 0.55;
        const end = rect.height + viewportHeight * 0.15;
        const rawProgress = (start - rect.top) / end;
        const progress = Math.max(0, Math.min(1, rawProgress));

        const newMaxProgress = Math.max(maxProgress, progress);
        const fill = newMaxProgress * 100;

        progressLine.style.background = `
            linear-gradient(
                to bottom,
                rgba(38, 33, 133,1) 0%,
                rgba(38, 33, 133,1) ${fill}%,
                rgba(38,33,133,0.2) ${fill}%,
                rgba(38,33,133,0.2) 100%
            )
        `;

        return newMaxProgress;
    }


    destroy() {
        if(this.collapsibleHandles) {
            this.collapsibleHandles.forEach((handle) => {
                handle.removeEventListener('click', this.collapsibleHandleCallback);
            });
        }
        if(this.collapsibleHandlesMobile) {
            this.collapsibleHandlesMobile.forEach((handle) => {
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