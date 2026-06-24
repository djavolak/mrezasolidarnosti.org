<?php

namespace Solidarity\Backend\Blocks\Contactcards;

use Skeletor\ContentEditor\Contracts\BlockParserInterface;
use Skeletor\Core\Service\Contracts\CrudServiceInterface;

class Contactcards implements BlockParserInterface
{
    const NAME = 'contactcards';

    public function __construct(protected CrudServiceInterface $imageService)
    {

    }

    public function parse(array $data, array $customDataKeys = []): array
    {
        $customDataKeys = array_merge($customDataKeys, $this->getDefaultDataKeys());
        $blockData = $data[static::NAME] ?? [];

        $parsedData = [
            'type' => static::NAME,
            'cards' => $this->parseCards($blockData['cards'] ?? []),
        ];

        foreach ($customDataKeys as $key) {
            if (isset($data[$key])) {
                $parsedData[$key] = $data[$key];
            }
        }

        return $parsedData;
    }

    protected function parseCards(array $cards): array
    {
        $cardsData = [];
        foreach ($cards as $card) {
            $cardsData[] = $this->parseCard($card);
        }
        return $cardsData;
    }

    protected function parseCard(array $card): array
    {
        $image = empty($card['imageId']) ? null : $this->imageService->getById($card['imageId']);

        return [
            'title' => $card['title'] ?? '',
            'email' => $card['email'] ?? '',
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
