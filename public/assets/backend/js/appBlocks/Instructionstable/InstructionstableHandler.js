import BlockHandler from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockHandler.js";
import Instructionstable from "./Instructionstable.js";

export default class InstructionstableHandler extends BlockHandler {
    constructor({
                    contentEditorId,
                    lockBlockActions,
                    customBlockActions,
                }) {
        const icon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M64 256l160 0 0 128-160 0 0-128zm0 192l160 0 0 64-160 0c-35.3 0-64-28.7-64-64l0-64 64 0 0 64zm224 64l0-64 160 0 0 64-160 0zm224-64c0 35.3-28.7 64-64 64l0-64 64 0zM448 384l-160 0 0-128 160 0 0 128zM288 192l0-128 160 0 0 128-160 0zM224 64l0 128L64 192 64 64l160 0z"/></svg>`;
        super({
            contentEditorId,
            lockBlockActions,
            label: 'Instructions Table',
            icon,
            customBlockActions,
        });
    }

    getBlock(id, data = null) {
        return new Instructionstable({
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
