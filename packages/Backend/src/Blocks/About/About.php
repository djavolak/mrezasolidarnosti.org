<?php

namespace Solidarity\Backend\Blocks\About;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;

class About implements BlockParserInterface
{
    const NAME = 'about';

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'firstTitle' => $blockData['firstTitle'] ?? '',
            'firstDescription' => $blockData['firstDescription'] ?? '',
            'firstFooterText' => $blockData['firstFooterText'] ?? '',
            'secondTitle' => $blockData['secondTitle'] ?? '',
            'secondDescription' => $blockData['secondDescription'] ?? '',
            'secondFooterText' => $blockData['secondFooterText'] ?? '',
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
                'svg' => $project['svg'] ?? '',
                'title' => $project['title'] ?? '',
                'description' => $project['description'] ?? '',
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
