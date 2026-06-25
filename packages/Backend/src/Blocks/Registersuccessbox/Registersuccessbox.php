<?php

namespace Solidarity\Backend\Blocks\Registersuccessbox;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;

class Registersuccessbox implements BlockParserInterface
{
    const NAME = 'registersuccessbox';

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
            'description' => $blockData['description'] ?? '',
            'subtitle' => $blockData['subtitle'] ?? '',
            'secondDescription' => $blockData['secondDescription'] ?? '',
            'buttonText' => $blockData['buttonText'] ?? '',
            'buttonLink' => $blockData['buttonLink'] ?? '',
            'buttonSvg' => $blockData['buttonSvg'] ?? '',
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
