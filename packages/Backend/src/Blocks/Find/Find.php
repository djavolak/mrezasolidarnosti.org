<?php

namespace Solidarity\Backend\Blocks\Find;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;
use Skeletor\Core\Service\Contracts\CrudServiceInterface;

class Find implements BlockParserInterface
{
    const NAME = 'find';

    public function __construct(protected CrudServiceInterface $imageService)
    {

    }

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
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
            $segmentsData[] = $this->parseSegment($segment);
        }
        return $segmentsData;
    }

    protected function parseSegment(array $segment): array
    {
        $image = empty($segment['imageId']) ? null : $this->imageService->getById($segment['imageId']);

        return [
            'title' => $segment['title'] ?? '',
            'description' => $segment['description'] ?? '',
            'buttonText' => $segment['buttonText'] ?? '',
            'buttonLink' => $segment['buttonLink'] ?? '',
            'imageId' => $image?->id,
            'filename' => $image?->filename,
            'alt' => $image?->alt,
        ];
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
