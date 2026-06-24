<?php

namespace Solidarity\Backend\Blocks\Threepillars;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;
use Skeletor\Core\Service\Contracts\CrudServiceInterface;

class Threepillars implements BlockParserInterface
{
    const NAME = 'threepillars';

    public function __construct(protected CrudServiceInterface $imageService)
    {

    }

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $desktop = empty($blockData['imageDesktopId']) ? null : $this->imageService->getById($blockData['imageDesktopId']);
        $mobile = empty($blockData['imageMobileId']) ? null : $this->imageService->getById($blockData['imageMobileId']);

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
            'description' => $blockData['description'] ?? '',
            'imageDesktopId' => $desktop?->id,
            'imageDesktopFilename' => $desktop?->filename,
            'imageDesktopAlt' => $desktop?->alt,
            'imageDesktopSvg' => $blockData['imageDesktopSvg'] ?? '',
            'imageMobileId' => $mobile?->id,
            'imageMobileFilename' => $mobile?->filename,
            'imageMobileAlt' => $mobile?->alt,
            'imageMobileSvg' => $blockData['imageMobileSvg'] ?? '',
            'pillars' => $this->parsePillars($blockData['pillars'] ?? []),
        ];

        foreach ($customDataKeys as $key) {
            if (isset($data[$key])) {
                $parsedData[$key] = $data[$key];
            }
        }

        return $parsedData;
    }

    protected function parsePillars(array $pillars): array
    {
        $pillarsData = [];
        foreach ($pillars as $pillar) {
            $pillarsData[] = [
                'title' => $pillar['title'] ?? '',
                'description' => $pillar['description'] ?? '',
                'buttonText' => $pillar['buttonText'] ?? '',
                'buttonLink' => $pillar['buttonLink'] ?? '',
            ];
        }
        return $pillarsData;
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
