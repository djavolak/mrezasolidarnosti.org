import BlockHandler from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockHandler.js";
import Banner from "./Banner.js";

export default class BannerHandler extends BlockHandler {
    constructor({
                    contentEditorId,
                    lockBlockActions,
                    customBlockActions,
                }) {
        const icon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M0 32C0 14.3 14.3 0 32 0L480 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 64C14.3 64 0 49.7 0 32zM0 480c0-17.7 14.3-32 32-32l448 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 512c-17.7 0-32-14.3-32-32zM64 160c0-17.7 14.3-32 32-32l320 0c17.7 0 32 14.3 32 32l0 96c0 17.7-14.3 32-32 32L96 288c-17.7 0-32-14.3-32-32l0-96z"/></svg>`;
        super({
            contentEditorId,
            lockBlockActions,
            label: 'Banner',
            icon,
            customBlockActions,
        });
    }

    getBlock(id, data = null) {
        return new Banner({
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
