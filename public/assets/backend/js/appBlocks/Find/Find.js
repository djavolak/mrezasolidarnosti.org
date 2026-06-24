import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TabbedContent from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/TabbedContent.js";
import MediaLibrary from "https://skeletor.greenfriends.systems/skeletorjs/src/MediaLibrary/MediaLibrary.js";
import {blockSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/blockSelectors.js";
import {mediaLibrarySelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/MediaLibrary/mediaLibrarySelectors.js";
import ImageInForm from "https://skeletor.greenfriends.systems/skeletorjs/src/MediaLibrary/ImageInForm/ImageInForm.js";
import {events} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/events.js";
import {tabbedContentSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/tabbedContentSelectors.js";

export default class Find extends Block {

    container;
    titleInput;
    blockBuilder;
    #tabbedContent;
    #images = [];
    #nextAvailableSegmentId = 0;

    constructor({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions}) {
        super({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions});
        this.blockBuilder = new BlockBuilder({containerType: BlockBuilder.CONTAINER_TYPES.ONE_COLUMN});
        this.#generateView();
    }

    #generateView() {
        [
            this.container,
            this.titleInput,
        ] = this.blockBuilder
            .buildInput({
                label: 'Title',
                inputName: `${this.getBaseInputName()}[${this.getName()}][title]`,
                value: this.data?.title ?? null
            })
            .getSpread();

        this.#generateSegments();
    }

    #generateSegments() {
        const label = document.createElement('label');
        label.classList.add('findSegmentsLabel');
        label.innerText = 'Segments';
        this.container.appendChild(label);

        const tabs = document.createElement('div');
        tabs.classList.add(tabbedContentSelectors.classes.tabs);
        this.container.appendChild(tabs);

        this.#tabbedContent = new TabbedContent(this.container, {
            tabText: 'Segment',
            appendNumberToTabText: true,
            tabContent: null,
        });
        this.#tabbedContent.init();
        this.#listenToTabbedEvents();

        if (this.data?.segments) {
            this.data.segments.forEach((segmentData) => {
                const tab = this.#tabbedContent.addTab(false);
                this.#populateTabContent(tab, segmentData);
            });
        }
    }

    #listenToTabbedEvents() {
        this.#tabbedContent.eventEmitter.on(events.tabAdded, (data) => {
            this.#populateTabContent(data);
        });
        this.#tabbedContent.eventEmitter.on(events.beforeTabRemoved, (data) => {
            this.#destroyImagesInTabContent(data.tabContent);
        });
    }

    #populateTabContent(data, segmentData = null) {
        const container = data.tabContent;
        container.classList.add(blockSelectors.classes.blockContentContainer, BlockBuilder.CONTAINER_TYPES.FOUR_COLUMNS);

        const imageContainer = ImageInForm.generateHTML({
            inputName: `${this.getBaseInputName()}[${this.getName()}][segments][${this.#nextAvailableSegmentId}][imageId]`,
            label: 'Image',
            chooseImageText: 'Choose image',
            imageId: segmentData?.imageId ?? null,
            src: segmentData?.filename ?? null,
        });
        const image = new ImageInForm(imageContainer);
        container.appendChild(imageContainer);
        image.init();
        this.#images.push(image);

        const titleInput = BlockBuilder.getInput({
            label: 'Title',
            value: segmentData?.title ?? '',
            placeholder: 'Title',
            inputName: `${this.getBaseInputName()}[${this.getName()}][segments][${this.#nextAvailableSegmentId}][title]`,
        });
        container.appendChild(titleInput);

        const descriptionInput = BlockBuilder.getTextArea({
            label: 'Description',
            value: segmentData?.description ?? '',
            placeholder: 'Description',
            inputName: `${this.getBaseInputName()}[${this.getName()}][segments][${this.#nextAvailableSegmentId}][description]`,
        });
        container.appendChild(descriptionInput);

        const buttonTextInput = BlockBuilder.getInput({
            label: 'Button Text',
            value: segmentData?.buttonText ?? '',
            placeholder: 'Button Text',
            inputName: `${this.getBaseInputName()}[${this.getName()}][segments][${this.#nextAvailableSegmentId}][buttonText]`,
        });
        container.appendChild(buttonTextInput);

        const buttonLinkInput = BlockBuilder.getInput({
            label: 'Button Link',
            value: segmentData?.buttonLink ?? '',
            placeholder: 'Button Link',
            inputName: `${this.getBaseInputName()}[${this.getName()}][segments][${this.#nextAvailableSegmentId}][buttonLink]`,
        });
        container.appendChild(buttonLinkInput);

        this.#nextAvailableSegmentId++;
    }

    #destroyImagesInTabContent(tabContent) {
        const imageContainers = tabContent.querySelectorAll(`.${mediaLibrarySelectors.classes.initiator}`);
        imageContainers.forEach((imageContainer) => {
            this.#images.forEach((image, index) => {
                if (image.getContainer() === imageContainer) {
                    image.destroy();
                    this.#images.splice(index, 1);
                }
            });
        });
    }

    getBlockInputData() {
        const segments = [];
        this.#tabbedContent.getTabContents().forEach((tabContent) => {
            let filename = null;
            const image = tabContent.querySelector('img');
            if (image) {
                const url = new URL(image.src.replace(MediaLibrary.imagePath, ''));
                filename = url.pathname;
            }
            segments.push({
                imageId: tabContent.querySelector('input[name*="[imageId]"]').value,
                filename: filename,
                title: tabContent.querySelector('input[name*="[title]"]').value,
                description: tabContent.querySelector('textarea').value,
                buttonText: tabContent.querySelector('input[name*="[buttonText]"]').value,
                buttonLink: tabContent.querySelector('input[name*="[buttonLink]"]').value,
            });
        });

        return {
            title: this.titleInput.value,
            segments: segments,
        };
    }

    destroy() {
        super.destroyBase();
        this.#tabbedContent.destroy();
        this.#tabbedContent = null;
        this.#images.forEach((image) => image.destroy());
        this.#images = [];
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
