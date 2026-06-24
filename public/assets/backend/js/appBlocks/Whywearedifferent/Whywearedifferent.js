import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TabbedContent from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/TabbedContent.js";
import {blockSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/blockSelectors.js";
import {events} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/events.js";
import {tabbedContentSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/tabbedContentSelectors.js";

export default class Whywearedifferent extends Block {

    container;
    titleInput;
    subtitleInput;
    coloredSubtitleInput;
    descriptionInput;
    footerTextInput;
    blockBuilder;
    #tabbedContent;
    #nextAvailableReasonId = 0;

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
            this.coloredSubtitleInput,
            this.descriptionInput,
            this.footerTextInput,
        ] = this.blockBuilder
            .buildInput({
                label: 'Title',
                inputName: `${this.getBaseInputName()}[${this.getName()}][title]`,
                value: this.data?.title ?? null
            })
            .buildInput({
                label: 'Subtitle',
                inputName: `${this.getBaseInputName()}[${this.getName()}][subtitle]`,
                value: this.data?.subtitle ?? null
            })
            .buildInput({
                label: 'Colored Subtitle',
                inputName: `${this.getBaseInputName()}[${this.getName()}][coloredSubtitle]`,
                value: this.data?.coloredSubtitle ?? null
            })
            .buildTextArea({
                label: 'Description',
                inputName: `${this.getBaseInputName()}[${this.getName()}][description]`,
                value: this.data?.description ?? null,
                type: 'textarea'
            })
            .buildInput({
                label: 'Footer Text',
                inputName: `${this.getBaseInputName()}[${this.getName()}][footerText]`,
                value: this.data?.footerText ?? null
            })
            .getSpread();

        this.#generateReasons();
    }

    #generateReasons() {
        const label = document.createElement('label');
        label.classList.add('reasonsLabel');
        label.innerText = 'Reasons';
        this.container.appendChild(label);

        const tabs = document.createElement('div');
        tabs.classList.add(tabbedContentSelectors.classes.tabs);
        this.container.appendChild(tabs);

        this.#tabbedContent = new TabbedContent(this.container, {
            tabText: 'Reason',
            appendNumberToTabText: true,
            tabContent: null,
        });
        this.#tabbedContent.init();
        this.#listenToTabbedEvents();

        if (this.data?.reasons) {
            this.data.reasons.forEach((reasonData) => {
                const tab = this.#tabbedContent.addTab(false);
                this.#populateTabContent(tab, reasonData);
            });
        }
    }

    #listenToTabbedEvents() {
        this.#tabbedContent.eventEmitter.on(events.tabAdded, (data) => {
            this.#populateTabContent(data);
        });
    }

    #populateTabContent(data, reasonData = null) {
        const container = data.tabContent;
        container.classList.add(blockSelectors.classes.blockContentContainer, BlockBuilder.CONTAINER_TYPES.TWO_COLUMNS);

        const titleInput = BlockBuilder.getInput({
            label: 'Title',
            value: reasonData?.title ?? '',
            placeholder: 'Title',
            inputName: `${this.getBaseInputName()}[${this.getName()}][reasons][${this.#nextAvailableReasonId}][title]`,
        });
        container.appendChild(titleInput);

        const descriptionInput = BlockBuilder.getTextArea({
            label: 'Description',
            value: reasonData?.description ?? '',
            placeholder: 'Description',
            inputName: `${this.getBaseInputName()}[${this.getName()}][reasons][${this.#nextAvailableReasonId}][description]`,
        });
        container.appendChild(descriptionInput);

        this.#nextAvailableReasonId++;
    }

    getBlockInputData() {
        const reasons = [];
        this.#tabbedContent.getTabContents().forEach((tabContent) => {
            reasons.push({
                title: tabContent.querySelector('input[name*="[title]"]').value,
                description: tabContent.querySelector('textarea').value,
            });
        });

        return {
            title: this.titleInput.value,
            subtitle: this.subtitleInput.value,
            coloredSubtitle: this.coloredSubtitleInput.value,
            description: this.descriptionInput.value,
            footerText: this.footerTextInput.value,
            reasons: reasons,
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
