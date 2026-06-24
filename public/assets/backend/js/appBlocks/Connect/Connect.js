import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TabbedContent from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/TabbedContent.js";
import {blockSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/blockSelectors.js";
import {events} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/events.js";
import {tabbedContentSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/tabbedContentSelectors.js";

export default class Connect extends Block {

    container;
    titleInput;
    descriptionInput;
    buttonTextInput;
    buttonLinkInput;
    blockBuilder;
    #tabbedContent;
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

        this.#generateSegments();
    }

    #generateSegments() {
        const label = document.createElement('label');
        label.classList.add('connectSegmentsLabel');
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
    }

    #populateTabContent(data, segmentData = null) {
        const container = data.tabContent;
        container.classList.add(blockSelectors.classes.blockContentContainer, BlockBuilder.CONTAINER_TYPES.TWO_COLUMNS);

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

        this.#nextAvailableSegmentId++;
    }

    getBlockInputData() {
        const segments = [];
        this.#tabbedContent.getTabContents().forEach((tabContent) => {
            segments.push({
                title: tabContent.querySelector('input[name*="[title]"]').value,
                description: tabContent.querySelector('textarea').value,
            });
        });

        return {
            title: this.titleInput.value,
            description: this.descriptionInput.value,
            buttonText: this.buttonTextInput.value,
            buttonLink: this.buttonLinkInput.value,
            segments: segments,
        };
    }

    destroy() {
        super.destroyBase();
        this.#tabbedContent.destroy();
        this.#tabbedContent = null;
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
