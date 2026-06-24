<?php

namespace Solidarity\Backend\Blocks\Direction;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;

class Direction implements BlockParserInterface
{
    const NAME = 'direction';

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'title' => $blockData['title'] ?? '',
            'description' => $blockData['description'] ?? '',
            'footerText' => $blockData['footerText'] ?? '',
            'projects' => $this->parseProjects($blockData['projects'] ?? []),
        ];

        foreach ($customDataKeys as $key) {
            if (isset($data[$key])) {
                $parsedData[$key] = $data[$key];
            }
        }

        return $parsedData;
    }

    protected function parseProjects(array $projects): array
    {
        $projectsData = [];
        foreach ($projects as $project) {
            $projectsData[] = [
                'projectHTMLId' => $project['projectHTMLId'] ?? '',
                'title' => $project['title'] ?? '',
                'description' => $project['description'] ?? '',
                'linkText' => $project['linkText'] ?? '',
                'linkUrl' => $project['linkUrl'] ?? '',
            ];
        }
        return $projectsData;
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
