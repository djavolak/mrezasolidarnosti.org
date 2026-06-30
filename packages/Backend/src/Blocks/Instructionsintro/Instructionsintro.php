<?php

namespace Solidarity\Backend\Blocks\Instructionsintro;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;

class Instructionsintro implements BlockParserInterface
{
    const NAME = 'instructionsintro';

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
            'description' => $blockData['description'] ?? '',
            'linkText' => $blockData['linkText'] ?? '',
            'buttonText' => $blockData['buttonText'] ?? '',
            'buttonSvg' => $blockData['buttonSvg'] ?? '',
            'infoTitle' => $blockData['infoTitle'] ?? '',
            'infoDescription' => $blockData['infoDescription'] ?? '',
        ];

        foreach ($customDataKeys as $key) {
            if (isset($data[$key])) {
                $parsedData[$key] = $data[$key];
            }
        }

        return $parsedData;
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
