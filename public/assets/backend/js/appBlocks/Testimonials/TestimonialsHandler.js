import BlockHandler from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockHandler.js";
import Testimonials from "./Testimonials.js";

export default class TestimonialsHandler extends BlockHandler {
    constructor({
                    contentEditorId,
                    lockBlockActions,
                    customBlockActions,
                }) {
        const icon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M448 96c0-35.3-28.7-64-64-64L64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320zM160 268c0 33.2-21.8 61.4-48.3 70.9c-10.9 3.9-21.7-5-21.7-16.6l0-1.4c0-6.6 4.2-12.3 10.1-15.3c8.6-4.4 15.2-12.2 18.2-21.6l-4.3 0c-17.7 0-32-14.3-32-32l0-32c0-17.7 14.3-32 32-32l16 0c17.7 0 32 14.3 32 32l0 16 0 16 0 48zm160 0c0 33.2-21.8 61.4-48.3 70.9c-10.9 3.9-21.7-5-21.7-16.6l0-1.4c0-6.6 4.2-12.3 10.1-15.3c8.6-4.4 15.2-12.2 18.2-21.6l-4.3 0c-17.7 0-32-14.3-32-32l0-32c0-17.7 14.3-32 32-32l16 0c17.7 0 32 14.3 32 32l0 16 0 16 0 48z"/></svg>`;
        super({
            contentEditorId,
            lockBlockActions,
            label: 'Testimonials',
            icon,
            customBlockActions,
        });
    }

    getBlock(id, data = null) {
        return new Testimonials({
            contentEditorId: this.contentEditorId,
            id,
            data,
            label: this.label,
            eventEmitter: this.eventEmitter,
            lockBlockActions: this.lockBlockActions,
            customBlockActions: this.customBlockActions,
        });
    }
}
