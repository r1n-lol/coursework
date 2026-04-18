<?php

namespace App\Http\Controllers;

use App\Models\Drink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminDrinkController extends Controller
{
    public function index(): View
    {
        return view('admin.drinks.index', [
            'drinks' => Drink::query()->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.drinks.form', [
            'drink' => new Drink([
                'catalog' => 'menu',
                'is_active' => true,
                'sort_order' => 0,
            ]),
            'categories' => $this->categoryOptions(),
            'catalogOptions' => $this->catalogOptions(),
            'formAction' => route('admin.drinks.store'),
            'formMethod' => 'POST',
            'pageTitle' => 'Добавить товар',
            'sizeOptionsText' => '',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['size_options'] = $this->parseSizeOptions($request->input('size_options'));
        $data['image_path'] = $this->storeImage($request);

        Drink::query()->create($data);

        return redirect()->route('admin.drinks.index')->with('admin_message', 'Товар добавлен.');
    }

    public function edit(Drink $drink): View
    {
        return view('admin.drinks.form', [
            'drink' => $drink,
            'categories' => $this->categoryOptions(),
            'catalogOptions' => $this->catalogOptions(),
            'formAction' => route('admin.drinks.update', $drink),
            'formMethod' => 'PUT',
            'pageTitle' => 'Редактировать товар',
            'sizeOptionsText' => $this->stringifySizeOptions($drink->normalizedSizeOptions()),
        ]);
    }

    public function update(Request $request, Drink $drink): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['name'], $drink->id);
        $data['size_options'] = $this->parseSizeOptions($request->input('size_options'));
        $data['image_path'] = $drink->image_path;

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeImage($request);
            $this->deleteUploadedImage($drink->image_path);
        }

        $drink->update($data);

        return redirect()->route('admin.drinks.index')->with('admin_message', 'Товар обновлен.');
    }

    public function destroy(Drink $drink): RedirectResponse
    {
        $this->deleteUploadedImage($drink->image_path);
        $drink->delete();

        return redirect()->route('admin.drinks.index')->with('admin_message', 'Товар удален.');
    }

    private function validatedData(Request $request): array
    {
        $categories = $this->categoryOptions();
        $categoryMap = collect($categories)->pluck('name', 'key')->all();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'catalog' => ['required', 'string', Rule::in(array_keys($this->catalogOptions()))],
            'category_key' => ['required', 'string', 'max:255', Rule::in(array_keys($categoryMap))],
            'description' => ['nullable', 'string'],
            'ingredients' => ['nullable', 'string'],
            'volume' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];

        $data['category_name'] = $categoryMap[$data['category_key']];

        return $data;
    }

    private function parseSizeOptions(?string $raw): array
    {
        $lines = preg_split('/\r\n|\r|\n/', (string) $raw);
        $options = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || ! str_contains($line, ':')) {
                continue;
            }

            [$label, $price] = array_map('trim', explode(':', $line, 2));

            if ($label === '' || ! is_numeric($price)) {
                continue;
            }

            $options[] = [
                'label' => $label,
                'price' => (int) $price,
            ];
        }

        return $options;
    }

    private function stringifySizeOptions(array $options): string
    {
        return implode(PHP_EOL, array_map(
            fn (array $option) => $option['label'].': '.$option['price'],
            $options,
        ));
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (
            Drink::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $directory = public_path('media/uploads/drinks');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'media/uploads/drinks/'.$filename;
    }

    private function deleteUploadedImage(?string $imagePath): void
    {
        if (! $imagePath || ! str_starts_with($imagePath, 'media/uploads/drinks/')) {
            return;
        }

        $fullPath = public_path($imagePath);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    private function categoryOptions(): array
    {
        $existingCategories = Drink::query()
            ->select('category_key', 'category_name')
            ->whereNotNull('category_key')
            ->whereNotNull('category_name')
            ->distinct()
            ->orderBy('category_name')
            ->get()
            ->map(fn (Drink $drink) => [
                'key' => $drink->category_key,
                'name' => $drink->category_name,
            ])
            ->values()
            ->all();

        $presetCategories = [
            ['key' => 'classic', 'name' => 'Классика'],
            ['key' => 'milk', 'name' => 'На молоке'],
            ['key' => 'signature', 'name' => 'Авторские'],
            ['key' => 'cold-coffee', 'name' => 'Холодный кофе'],
            ['key' => 'non-coffee', 'name' => 'Без кофе'],
            ['key' => 'drip-bags', 'name' => 'Дрип-пакеты'],
            ['key' => 'beans', 'name' => 'Кофе в зернах'],
            ['key' => 'cocoa-home', 'name' => 'Какао'],
            ['key' => 'hot-chocolate-home', 'name' => 'Горячий шоколад'],
        ];

        return collect([...$presetCategories, ...$existingCategories])
            ->unique('key')
            ->sortBy('name')
            ->values()
            ->all();
    }

    private function catalogOptions(): array
    {
        return [
            'menu' => 'Меню',
            'home' => 'Домой',
        ];
    }
}
