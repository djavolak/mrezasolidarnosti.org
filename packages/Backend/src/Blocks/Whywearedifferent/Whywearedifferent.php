<?php

namespace Solidarity\Backend\Blocks\Whywearedifferent;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;

class Whywearedifferent implements BlockParserInterface
{
    const NAME = 'whywearedifferent';

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
            'subtitle' => $blockData['subtitle'] ?? '',
            'coloredSubtitle' => $blockData['coloredSubtitle'] ?? '',
            'description' => $blockData['description'] ?? '',
            'footerText' => $blockData['footerText'] ?? '',
            'reasons' => $this->parseReasons($blockData['reasons'] ?? []),
        ];

        foreach ($customDataKeys as $key) {
            if (isset($data[$key])) {
                $parsedData[$key] = $data[$key];
            }
        }

        return $parsedData;
    }

    protected function parseReasons(array $reasons): array
    {
        $reasonsData = [];
        foreach ($reasons as $reason) {
            $reasonsData[] = [
                'title' => $reason['title'] ?? '',
                'description' => $reason['description'] ?? '',
            ];
        }
        return $reasonsData;
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
