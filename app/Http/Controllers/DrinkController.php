<?php

namespace App\Http\Controllers;

use App\Models\Drink;
use Illuminate\Http\Request;

class DrinkController extends Controller
{
    public function index(Request $request)
    {
        return $this->catalogView(
            request: $request,
            catalog: 'menu',
            view: 'pages.menu',
            routeName: 'menu',
            title: 'Меню напитков',
            kicker: 'Собрали любимое',
            emptyTitle: 'По этому фильтру напитков пока нет',
            emptyText: 'Попробуйте выбрать другую категорию или вернуться ко всем позициям.',
            allLabel: 'Все напитки',
        );
    }

    public function home(Request $request)
    {
        return $this->catalogView(
            request: $request,
            catalog: 'home',
            view: 'pages.home',
            routeName: 'home',
            title: 'Домой',
            kicker: 'Для дома',
            emptyTitle: 'По этому фильтру товаров пока нет',
            emptyText: 'Попробуйте выбрать другую категорию или вернуться ко всем товарам для дома.',
            allLabel: 'Все товары',
        );
    }

    public function show(Drink $drink)
    {
        abort_unless($drink->is_active && $drink->catalog === 'menu', 404);

        return view('pages.drink', [
            'drink' => $drink,
            'sizeOptions' => $drink->normalizedSizeOptions(),
        ]);
    }

    public function showHome(Drink $drink)
    {
        abort_unless($drink->is_active && $drink->catalog === 'home', 404);

        return view('pages.drink', [
            'drink' => $drink,
            'sizeOptions' => $drink->normalizedSizeOptions(),
        ]);
    }

    private function catalogView(
        Request $request,
        string $catalog,
        string $view,
        string $routeName,
        string $title,
        string $kicker,
        string $emptyTitle,
        string $emptyText,
        string $allLabel,
    ) {
        $categories = Drink::query()
            ->select('category_key', 'category_name')
            ->where('catalog', $catalog)
            ->where('is_active', true)
            ->distinct()
            ->orderBy('category_name')
            ->get();

        $selectedCategory = $request->string('category')->toString();

        $drinks = Drink::query()
            ->where('catalog', $catalog)
            ->where('is_active', true)
            ->when($selectedCategory !== '', function ($query) use ($selectedCategory) {
                $query->where('category_key', $selectedCategory);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view($view, [
            'categories' => $categories,
            'drinks' => $drinks,
            'selectedCategory' => $selectedCategory,
            'catalogRoute' => $routeName,
            'catalogTitle' => $title,
            'catalogKicker' => $kicker,
            'emptyTitle' => $emptyTitle,
            'emptyText' => $emptyText,
            'allLabel' => $allLabel,
        ]);
    }
}
