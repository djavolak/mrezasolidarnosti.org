<?php

namespace Solidarity\Backend\Blocks\Instructionsintro;

use Skeletor\ContentEditor\Contracts\BlockViewFilterInterface;
use Solidarity\Transaction\Service\Transaction;

class InstructionsintroViewFilter implements BlockViewFilterInterface
{
    const NAME = 'instructionsintro';

    public function __construct(private Transaction $transaction)
    {

    }

    public function filter(array $data): array
    {
        $buttonText = "";
        if ($this->transaction->hasUnmetNeeds()) {
            $buttonText = $data['buttonText'];
        }
        $data['buttonText'] = $buttonText;

        return $data;
    }
}
