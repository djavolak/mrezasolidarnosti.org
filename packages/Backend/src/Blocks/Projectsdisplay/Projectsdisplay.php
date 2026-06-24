<?php

namespace Solidarity\Backend\Blocks\Projectsdisplay;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;
use Skeletor\Core\Service\Contracts\CrudServiceInterface;

class Projectsdisplay implements BlockParserInterface
{
    const NAME = 'projectsdisplay';

    public function __construct(protected CrudServiceInterface $imageService)
    {

    }

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
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
            $projectsData[] = $this->parseProject($project);
        }
        return $projectsData;
    }

    protected function parseProject(array $project): array
    {
        $image = empty($project['imageId']) ? null : $this->imageService->getById($project['imageId']);

        return [
            'className' => $project['className'] ?? '',
            'title' => $project['title'] ?? '',
            'description' => $project['description'] ?? '',
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
