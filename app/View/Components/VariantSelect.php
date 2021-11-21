<?php

namespace App\View\Components;

use App\Repositories\VariantRepository;
use Illuminate\View\Component;

class VariantSelect extends Component
{
    public $variants;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(VariantRepository $repository)
    {
        $this->variants = $repository->getAllVariantsWithDistinctProductVariants();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.variant-select');
    }
}
