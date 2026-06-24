import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TabbedContent from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/TabbedContent.js";
import MediaLibrary from "https://skeletor.greenfriends.systems/skeletorjs/src/MediaLibrary/MediaLibrary.js";
import {blockSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/blockSelectors.js";
import ImageInForm from "https://skeletor.greenfriends.systems/skeletorjs/src/MediaLibrary/ImageInForm/ImageInForm.js";
import {events} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/events.js";
import {tabbedContentSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/tabbedContentSelectors.js";

export default class Howitworks extends Block {

    container;
    titleInput;
    descriptionInput;
    linkTextInput;
    linkUrlInput;
    buttonTextInput;
    buttonLinkInput;
    blockBuilder;
    #image;
    #imageContainer;
    #tabbedContent;
    #nextAvailableStepId = 0;

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
            this.linkTextInput,
            this.linkUrlInput,
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
                label: 'Link Text',
                inputName: `${this.getBaseInputName()}[${this.getName()}][linkText]`,
                value: this.data?.linkText ?? null
            })
            .buildInput({
                label: 'Link URL',
                inputName: `${this.getBaseInputName()}[${this.getName()}][linkUrl]`,
                value: this.data?.linkUrl ?? null
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

        this.#generateImage();
        this.#generateSteps();
    }

    #generateImage() {
        const label = document.createElement('label');
        label.classList.add('howItWorksImageLabel');
        label.innerText = 'Image';
        this.container.appendChild(label);

        this.#imageContainer = ImageInForm.generateHTML({
            inputName: `${this.getBaseInputName()}[${this.getName()}][imageId]`,
            label: 'Image',
            chooseImageText: 'Choose image',
            imageId: this.data?.imageId ?? null,
            src: this.data?.filename ?? null,
        });
        this.#image = new ImageInForm(this.#imageContainer);
        this.container.appendChild(this.#imageContainer);
        this.#image.init();
    }

    #generateSteps() {
        const label = document.createElement('label');
        label.classList.add('howItWorksStepsLabel');
        label.innerText = 'Steps';
        this.container.appendChild(label);

        const tabs = document.createElement('div');
        tabs.classList.add(tabbedContentSelectors.classes.tabs);
        this.container.appendChild(tabs);

        this.#tabbedContent = new TabbedContent(this.container, {
            tabText: 'Step',
            appendNumberToTabText: true,
            tabContent: null,
        });
        this.#tabbedContent.init();
        this.#listenToTabbedEvents();

        if (this.data?.steps) {
            this.data.steps.forEach((stepData) => {
                const tab = this.#tabbedContent.addTab(false);
                this.#populateTabContent(tab, stepData);
            });
        }
    }

    #listenToTabbedEvents() {
        this.#tabbedContent.eventEmitter.on(events.tabAdded, (data) => {
            this.#populateTabContent(data);
        });
    }

    #populateTabContent(data, stepData = null) {
        const container = data.tabContent;
        container.classList.add(blockSelectors.classes.blockContentContainer, BlockBuilder.CONTAINER_TYPES.TWO_COLUMNS);

        const titleInput = BlockBuilder.getInput({
            label: 'Title',
            value: stepData?.title ?? '',
            placeholder: 'Title',
            inputName: `${this.getBaseInputName()}[${this.getName()}][steps][${this.#nextAvailableStepId}][title]`,
        });
        container.appendChild(titleInput);

        const descriptionInput = BlockBuilder.getTextArea({
            label: 'Description',
            value: stepData?.description ?? '',
            placeholder: 'Description',
            inputName: `${this.getBaseInputName()}[${this.getName()}][steps][${this.#nextAvailableStepId}][description]`,
        });
        container.appendChild(descriptionInput);

        this.#nextAvailableStepId++;
    }

    getBlockInputData() {
        let filename = null;
        const image = this.#imageContainer.querySelector('img');
        if (image) {
            const url = new URL(image.src.replace(MediaLibrary.imagePath, ''));
            filename = url.pathname;
        }

        const steps = [];
        this.#tabbedContent.getTabContents().forEach((tabContent) => {
            steps.push({
                title: tabContent.querySelector('input[name*="[title]"]').value,
                description: tabContent.querySelector('textarea').value,
            });
        });

        return {
            title: this.titleInput.value,
            description: this.descriptionInput.value,
            linkText: this.linkTextInput.value,
            linkUrl: this.linkUrlInput.value,
            buttonText: this.buttonTextInput.value,
            buttonLink: this.buttonLinkInput.value,
            imageId: this.#imageContainer.querySelector('input[name*="[imageId]"]').value,
            filename: filename,
            steps: steps,
        };
    }

    destroy() {
        super.destroyBase();
        this.#image.destroy();
        this.#image = null;
        this.#imageContainer = null;
        this.#tabbedContent.destroy();
        this.#tabbedContent = null;
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
