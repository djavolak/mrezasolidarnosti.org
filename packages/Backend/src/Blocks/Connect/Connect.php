<?php

namespace Solidarity\Backend\Blocks\Connect;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;

class Connect implements BlockParserInterface
{
    const NAME = 'connect';

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
            'description' => $blockData['description'] ?? '',
            'buttonText' => $blockData['buttonText'] ?? '',
            'buttonLink' => $blockData['buttonLink'] ?? '',
            'segments' => $this->parseSegments($blockData['segments'] ?? []),
        ];

        foreach ($customDataKeys as $key) {
            if (isset($data[$key])) {
                $parsedData[$key] = $data[$key];
            }
        }

        return $parsedData;
    }

    protected function parseSegments(array $segments): array
    {
        $segmentsData = [];
        foreach ($segments as $segment) {
            $segmentsData[] = [
                'title' => $segment['title'] ?? '',
                'description' => $segment['description'] ?? '',
            ];
        }
        return $segmentsData;
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
