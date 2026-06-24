<?php

namespace Solidarity\Backend\Blocks\Howitworks;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;
use Skeletor\Core\Service\Contracts\CrudServiceInterface;

class Howitworks implements BlockParserInterface
{
    const NAME = 'howitworks';

    public function __construct(protected CrudServiceInterface $imageService)
    {

    }

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $image = empty($blockData['imageId']) ? null : $this->imageService->getById($blockData['imageId']);

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
            'description' => $blockData['description'] ?? '',
            'linkText' => $blockData['linkText'] ?? '',
            'linkUrl' => $blockData['linkUrl'] ?? '',
            'buttonText' => $blockData['buttonText'] ?? '',
            'buttonLink' => $blockData['buttonLink'] ?? '',
            'imageId' => $image?->id,
            'filename' => $image?->filename,
            'alt' => $image?->alt,
            'steps' => $this->parseSteps($blockData['steps'] ?? []),
        ];

        foreach ($customDataKeys as $key) {
            if (isset($data[$key])) {
                $parsedData[$key] = $data[$key];
            }
        }

        return $parsedData;
    }

    protected function parseSteps(array $steps): array
    {
        $stepsData = [];
        foreach ($steps as $step) {
            $stepsData[] = [
                'title' => $step['title'] ?? '',
                'description' => $step['description'] ?? '',
            ];
        }
        return $stepsData;
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
