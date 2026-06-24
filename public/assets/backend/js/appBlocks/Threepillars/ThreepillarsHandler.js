import BlockHandler from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockHandler.js";
import Threepillars from "./Threepillars.js";

export default class ThreepillarsHandler extends BlockHandler {
    constructor({
                    contentEditorId,
                    lockBlockActions,
                    customBlockActions,
                }) {
        const icon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l0 320c-17.7 0-32 14.3-32 32s14.3 32 32 32l320 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-320c17.7 0 32-14.3 32-32s-14.3-32-32-32L32 32zM96 128l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32zm0 128l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32zm96 128c0-17.7 14.3-32 32-32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-17.7 0-32-14.3-32-32zM224 128l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32zM192 256c0-17.7 14.3-32 32-32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-17.7 0-32-14.3-32-32zM96 384l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/></svg>`;
        super({
            contentEditorId,
            lockBlockActions,
            label: 'Three Pillars',
            icon,
            customBlockActions,
        });
    }

    getBlock(id, data = null) {
        return new Threepillars({
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
