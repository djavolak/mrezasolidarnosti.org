import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";

export default class Herotext extends Block {

    container;
    titleInput;
    subtitleInput;
    descriptionInput;
    blockBuilder;

    constructor({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions}) {
        super({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions});
        this.blockBuilder = new BlockBuilder({containerType: BlockBuilder.CONTAINER_TYPES.ONE_COLUMN});
        this.#generateView();
    }

    #generateView() {
        [
            this.container,
            this.titleInput,
            this.subtitleInput,
            this.descriptionInput,
        ] = this.blockBuilder
            .buildInput({
                label: 'Title',
                inputName: `${this.getBaseInputName()}[${this.getName()}][title]`,
                value: this.data?.title ?? null
            })
            .buildTextArea({
                label: 'Subtitle',
                inputName: `${this.getBaseInputName()}[${this.getName()}][subtitle]`,
                value: this.data?.subtitle ?? null,
                type: 'textarea'
            })
            .buildTextArea({
                label: 'Description',
                inputName: `${this.getBaseInputName()}[${this.getName()}][description]`,
                value: this.data?.description ?? null,
                type: 'textarea'
            })
            .getSpread();
    }

    getBlockInputData() {
        return {
            title: this.titleInput.value,
            subtitle: this.subtitleInput.value,
            description: this.descriptionInput.value,
        };
    }

    destroy() {
        super.destroyBase();
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
