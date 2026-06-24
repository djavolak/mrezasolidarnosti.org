import BlockHandler from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockHandler.js";
import Connect from "./Connect.js";

export default class ConnectHandler extends BlockHandler {
    constructor({
                    contentEditorId,
                    lockBlockActions,
                    customBlockActions,
                }) {
        const icon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114l-112 112c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C50.7 244.9 57.2 323.7 107.2 373.7c56.5 56.5 148 56.5 204.5 0l112-112zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C81.8 372.1 81.8 321.1 113.3 289.6l112-112c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C589.3 267.1 582.8 188.3 532.8 138.3c-56.5-56.5-148-56.5-204.5 0l-112 112z"/></svg>`;
        super({
            contentEditorId,
            lockBlockActions,
            label: 'Connect',
            icon,
            customBlockActions,
        });
    }

    getBlock(id, data = null) {
        return new Connect({
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
