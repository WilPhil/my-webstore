<?php

declare(strict_types=1);

use App\Data\ProductCategoryData;
use App\Data\ProductData;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public array $selectProductsCategories = [];

    public string $searchProducts = '';

    public string $sortByProducts = 'newest';

    protected function queryString()
    {
        return [
            'selectProductsCategories' => [
                'as' => 'category',
                'except' => '',
            ],
            'searchProducts' => [
                'as' => 'search',
            ],
            'sortByProducts' => [
                'as' => 'sort',
            ],
        ];
    }

    protected function rules()
    {
        return [
            'selectProductsCategories' => 'array',
            'selectProductsCategories.*' => 'integer|exists:tags,id',
            'searchProducts' => 'nullable|min:3|max:30',
            'sortByProducts' => 'in:newest,oldest,price_desc,price_asc',
        ];
    }

    protected function messages()
    {
        return [
            'selectProductsCategories.*.integer' => 'One or more selected category is invalid',
            'selectProductsCategories.*.exists' => 'One or more selected category is invalid',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'selectProductCategories' => 'category',
            'searchProducts' => 'search',
            'sortByProducts' => 'sort',
        ];
    }

    public function mount()
    {
        $this->validate();
    }

    public function applyFilters()
    {
        $this->validate();
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset();
        $this->resetPage();
        $this->resetErrorBag();
    }

    #[Computed]
    public function products()
    {
        // Early return if there're any errors
        if ($this->getErrorBag()->isNotEmpty()) {
            return ProductData::collect([]);
        }

        $productQuery = Product::query();

        if (! empty($this->selectProductsCategories)) {
            $productQuery->whereHas('tags', function (Builder $q) {
                $q->whereIn('id', $this->selectProductsCategories);
            });
        }

        if ($this->searchProducts) {
            $productQuery->where('name', 'like', "%{$this->searchProducts}%");
        }

        switch ($this->sortByProducts) {
            case 'oldest':
                $productQuery->oldest();
                break;
            case 'price_desc':
                $productQuery->orderBy('price', 'desc');
                break;
            case 'price_asc':
                $productQuery->orderBy('price', 'asc');
                break;
            default:
                $productQuery->latest();
                break;
        }

        $paginatedProducts = $productQuery->paginate(9);

        return ProductData::collect($paginatedProducts);
    }

    #[Computed]
    public function tags()
    {
        // Early return if there're any errors
        if ($this->getErrorBag()->isNotEmpty()) {
            return ProductCategoryData::collect([]);
        }

        $collectionProducts = Tag::query()->whereType('category')->withCount('products')->get();

        return ProductCategoryData::collect($collectionProducts);
    }
};
