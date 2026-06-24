import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";

export default class Banner extends Block {

    container;
    titleInput;
    descriptionInput;
    buttonTextInput;
    buttonLinkInput;
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
            this.descriptionInput,
            this.buttonTextInput,
            this.buttonLinkInput,
        ] = this.blockBuilder
            .buildInput({
                label: 'Title',
                inputName: `${this.getBaseInputName()}[${this.getName()}][title]`,
                value: this.data?.title ?? null
            })
            .buildTextArea({
                label: 'Description',
                inputName: `${this.getBaseInputName()}[${this.getName()}][description]`,
                value: this.data?.description ?? null,
                type: 'textarea'
            })
            .buildInput({
                label: 'Button Text',
                inputName: `${this.getBaseInputName()}[${this.getName()}][buttonText]`,
                value: this.data?.buttonText ?? null
            })
            .buildInput({
                label: 'Button Link',
                inputName: `${this.getBaseInputName()}[${this.getName()}][buttonLink]`,
                value: this.data?.buttonLink ?? null
            })
            .getSpread();
    }

    getBlockInputData() {
        return {
            title: this.titleInput.value,
            description: this.descriptionInput.value,
            buttonText: this.buttonTextInput.value,
            buttonLink: this.buttonLinkInput.value,
        };
    }

    destroy() {
        super.destroyBase();
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
