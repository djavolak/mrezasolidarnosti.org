<?php

namespace Solidarity\Backend\Blocks\Faq;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;

class Faq implements BlockParserInterface
{
    const NAME = 'faq';

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
            'buttonText' => $blockData['buttonText'] ?? '',
            'buttonLink' => $blockData['buttonLink'] ?? '',
            'sections' => $this->parseSections($blockData['sections'] ?? []),
        ];

        foreach ($customDataKeys as $key) {
            if (isset($data[$key])) {
                $parsedData[$key] = $data[$key];
            }
        }

        return $parsedData;
    }

    protected function parseSections(array $sections): array
    {
        $sectionsData = [];
        foreach ($sections as $section) {
            $sectionsData[] = [
                'question' => $section['question'] ?? '',
                'answer' => $section['answer'] ?? '',
            ];
        }
        return $sectionsData;
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
