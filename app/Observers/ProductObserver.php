<?php

namespace App\Observers;

use App\Models\Product;
use App\Support\AdminNotifier;

class ProductObserver
{
    public function created(Product $product)
    {
        AdminNotifier::send(
            'Product added',
            sprintf('"%s" was added to the catalogue.', $product->name),
            'ri-shopping-bag-line',
            'bg-success-subtle',
            route('product.index')
        );
    }

    public function updated(Product $product)
    {
        AdminNotifier::send(
            'Product updated',
            sprintf('"%s" was updated.', $product->name),
            'ri-edit-2-line',
            'bg-warning-subtle',
            route('product.index')
        );
    }

    public function deleted(Product $product)
    {
        AdminNotifier::send(
            'Product removed',
            sprintf('"%s" was deleted from the catalogue.', $product->name),
            'ri-delete-bin-line',
            'bg-danger-subtle',
            route('product.index')
        );
    }
}
