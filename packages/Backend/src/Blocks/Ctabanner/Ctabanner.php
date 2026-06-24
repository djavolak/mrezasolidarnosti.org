<?php

namespace Solidarity\Backend\Blocks\Ctabanner;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;

class Ctabanner implements BlockParserInterface
{
    const NAME = 'ctabanner';

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
            'description' => $blockData['description'] ?? '',
            'buttons' => $this->parseButtons($blockData['buttons'] ?? []),
        ];

        foreach ($customDataKeys as $key) {
            if (isset($data[$key])) {
                $parsedData[$key] = $data[$key];
            }
        }

        return $parsedData;
    }

    protected function parseButtons(array $buttons): array
    {
        $buttonsData = [];
        foreach ($buttons as $button) {
            $buttonsData[] = [
                'buttonTitle' => $button['buttonTitle'] ?? '',
                'buttonUrl' => $button['buttonUrl'] ?? '',
                'type' => $button['type'] ?? 'primary',
            ];
        }
        return $buttonsData;
    }

    protected function getDefaultDataKeys(): array
    {
        return [
            'blockHTMLId',
            'blockHTMLClassName',
            'blockViewMode',
            'containerMarginTop',
            'containerMarginBottom',
            'containerMarginLeft',
            'containerMarginRight',
            'containerPaddingTop',
            'containerPaddingBottom',
            'containerPaddingLeft',
            'containerPaddingRight'
        ];
    }
}
