import BlockHandler from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockHandler.js";
import Login from "./Login.js";

export default class LoginHandler extends BlockHandler {
    constructor({
                    contentEditorId,
                    lockBlockActions,
                    customBlockActions,
                }) {
        const icon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M217.9 105.9L340.7 228.7c7.5 7.5 11.7 17.7 11.7 28.3s-4.2 20.8-11.7 28.3L217.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-96 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l96 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM352 416l64 0c17.7 0 32-14.3 32-32l0-256c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0c53 0 96 43 96 96l0 256c0 53-43 96-96 96l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/></svg>`;
        super({
            contentEditorId,
            lockBlockActions,
            label: 'Login',
            icon,
            customBlockActions,
        });
    }

    getBlock(id, data = null) {
        return new Login({
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
