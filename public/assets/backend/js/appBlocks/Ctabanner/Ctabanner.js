import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TabbedContent from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/TabbedContent.js";
import {blockSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/blockSelectors.js";
import {events} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/events.js";
import {tabbedContentSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/tabbedContentSelectors.js";

export default class Ctabanner extends Block {

    static BUTTON_TYPES = {primary: 'Primary', secondary: 'Secondary'};

    container;
    titleInput;
    descriptionInput;
    blockBuilder;
    #tabbedContent;
    #nextAvailableButtonId = 0;

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
            .getSpread();

        this.#generateButtons();
    }

    #generateButtons() {
        const label = document.createElement('label');
        label.classList.add('ctaButtonsLabel');
        label.innerText = 'Buttons';
        this.container.appendChild(label);

        const tabs = document.createElement('div');
        tabs.classList.add(tabbedContentSelectors.classes.tabs);
        this.container.appendChild(tabs);

        this.#tabbedContent = new TabbedContent(this.container, {
            tabText: 'Button',
            appendNumberToTabText: true,
            tabContent: null,
        });
        this.#tabbedContent.init();
        this.#listenToTabbedEvents();

        if (this.data?.buttons) {
            this.data.buttons.forEach((buttonData) => {
                const tab = this.#tabbedContent.addTab(false);
                this.#populateTabContent(tab, buttonData);
            });
        }
    }

    #listenToTabbedEvents() {
        this.#tabbedContent.eventEmitter.on(events.tabAdded, (data) => {
            this.#populateTabContent(data);
        });
    }

    #populateTabContent(data, buttonData = null) {
        const container = data.tabContent;
        container.classList.add(blockSelectors.classes.blockContentContainer, BlockBuilder.CONTAINER_TYPES.FOUR_COLUMNS);

        const titleInput = BlockBuilder.getInput({
            label: 'Button Title',
            value: buttonData?.buttonTitle ?? '',
            placeholder: 'Button Title',
            inputName: `${this.getBaseInputName()}[${this.getName()}][buttons][${this.#nextAvailableButtonId}][buttonTitle]`,
        });
        container.appendChild(titleInput);

        const urlInput = BlockBuilder.getInput({
            label: 'Button URL',
            value: buttonData?.buttonUrl ?? '',
            placeholder: 'Button URL',
            inputName: `${this.getBaseInputName()}[${this.getName()}][buttons][${this.#nextAvailableButtonId}][buttonUrl]`,
        });
        container.appendChild(urlInput);

        const typeSelect = this.#buildSelect({
            label: 'Type',
            inputName: `${this.getBaseInputName()}[${this.getName()}][buttons][${this.#nextAvailableButtonId}][type]`,
            options: Ctabanner.BUTTON_TYPES,
            value: buttonData?.type ?? 'primary',
        });
        container.appendChild(typeSelect);

        this.#nextAvailableButtonId++;
    }

    #buildSelect({label, inputName, options, value}) {
        const container = document.createElement('div');
        container.classList.add(blockSelectors.classes.inputContainer);

        const labelEl = document.createElement('label');
        labelEl.innerText = label;
        container.appendChild(labelEl);

        const select = document.createElement('select');
        select.name = inputName;
        select.classList.add(blockSelectors.classes.input);
        Object.keys(options).forEach((key) => {
            const option = document.createElement('option');
            option.value = key;
            option.innerText = options[key];
            if (value && value === key) {
                option.selected = true;
            }
            select.appendChild(option);
        });
        container.appendChild(select);

        return container;
    }

    getBlockInputData() {
        const buttons = [];
        this.#tabbedContent.getTabContents().forEach((tabContent) => {
            buttons.push({
                buttonTitle: tabContent.querySelector('input[name*="[buttonTitle]"]').value,
                buttonUrl: tabContent.querySelector('input[name*="[buttonUrl]"]').value,
                type: tabContent.querySelector('select').value,
            });
        });

        return {
            title: this.titleInput.value,
            description: this.descriptionInput.value,
            buttons: buttons,
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
