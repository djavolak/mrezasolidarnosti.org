<?php

namespace Solidarity\Backend\Blocks\Sidebyside;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;

class Sidebyside implements BlockParserInterface
{
    const NAME = 'sidebyside';

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
            'description' => $blockData['description'] ?? '',
            'linkText' => $blockData['linkText'] ?? '',
            'linkUrl' => $blockData['linkUrl'] ?? '',
            'topPadding' => $blockData['topPadding'] ?? 'big',
            'bottomPadding' => $blockData['bottomPadding'] ?? 'big',
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
