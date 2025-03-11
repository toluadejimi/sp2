<?php

declare(strict_types=1);

namespace Infobip\Resources\RCS\Collections;

use Infobip\Resources\BaseCollection;
use Infobip\Resources\CollectionValidationInterface;
use Infobip\Resources\ModelValidationInterface;
use Infobip\Resources\RCS\Contracts\SuggestionInterface;
use Infobip\Validations\Rules;

final class SuggestionCollection extends BaseCollection implements CollectionValidationInterface
{
    /** @var array|SuggestionInterface[] */
    protected $items = [];

    public function add(SuggestionInterface $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function rules(): Rules
    {
        $rules = new Rules();

        /** @var ModelValidationInterface $item */
        foreach ($this->items as $item) {
            $rules->addModelRules($item);
        }

        return $rules;
    }
}
