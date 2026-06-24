import BlockHandler from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockHandler.js";
import About from "./About.js";

export default class AboutHandler extends BlockHandler {
    constructor({
                    contentEditorId,
                    lockBlockActions,
                    customBlockActions,
                }) {
        const icon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM216 336l24 0 0-64-24 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l48 0c13.3 0 24 10.7 24 24l0 88 8 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-80 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg>`;
        super({
            contentEditorId,
            lockBlockActions,
            label: 'About',
            icon,
            customBlockActions,
        });
    }

    getBlock(id, data = null) {
        return new About({
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
