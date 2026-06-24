<?php

namespace Solidarity\Backend\Blocks\HeroStats;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;

class HeroStats implements BlockParserInterface
{
    const NAME = 'herostats';

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];
        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? null,
            'description' => $blockData['description'] ?? null
        ];
        foreach($customDataKeys as $key) {
            if(isset($data[$key])) {
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