<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'catalog',
        'category_key',
        'category_name',
        'description',
        'ingredients',
        'volume',
        'price',
        'size_options',
        'image_path',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'integer',
            'sort_order' => 'integer',
            'size_options' => 'array',
        ];
    }

    public function sizeSummary(): string
    {
        $options = $this->normalizedSizeOptions();

        if ($options === []) {
            return $this->volume ?: '';
        }

        if (count($options) === 1) {
            return $options[0]['label'];
        }

        return implode(' / ', array_map(fn (array $option) => $option['label'], $options));
    }

    public function startingPrice(): int
    {
        $options = $this->normalizedSizeOptions();

        if ($options === []) {
            return $this->price;
        }

        return (int) min(array_map(fn (array $option) => $option['price'], $options));
    }

    public function normalizedSizeOptions(): array
    {
        $options = $this->size_options ?? [];

        return array_values(array_filter(array_map(function ($option) {
            if (! is_array($option) || ! isset($option['label'], $option['price'])) {
                return null;
            }

            return [
                'label' => (string) $option['label'],
                'price' => (int) $option['price'],
            ];
        }, $options)));
    }

    public function defaultSizeOption(): array
    {
        $options = $this->normalizedSizeOptions();

        if ($options !== []) {
            return $options[0];
        }

        return [
            'label' => $this->volume ?: 'Стандарт',
            'price' => $this->price,
        ];
    }
}
