<?php

namespace Solidarity\Backend\Blocks\Testimonials;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;

class Testimonials implements BlockParserInterface
{
    const NAME = 'testimonials';

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
            'description' => $blockData['description'] ?? '',
            'testimonials' => $this->parseTestimonials($blockData['testimonials'] ?? []),
        ];

        foreach ($customDataKeys as $key) {
            if (isset($data[$key])) {
                $parsedData[$key] = $data[$key];
            }
        }

        return $parsedData;
    }

    protected function parseTestimonials(array $testimonials): array
    {
        $testimonialsData = [];
        foreach ($testimonials as $testimonial) {
            $testimonialsData[] = [
                'text' => $testimonial['text'] ?? '',
            ];
        }
        return $testimonialsData;
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
