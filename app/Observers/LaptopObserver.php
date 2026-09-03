<?php

namespace App\Observers;

use App\Models\Laptop;
use App\Support\AdminNotifier;

class LaptopObserver
{
    /** "Dell Latitude 7440 (SN-123)" */
    private function label(Laptop $laptop)
    {
        return sprintf('%s %s (%s)', $laptop->brand, $laptop->model, $laptop->serial_number);
    }

    public function created(Laptop $laptop)
    {
        AdminNotifier::send(
            'Laptop added',
            sprintf('%s was added to the fleet.', $this->label($laptop)),
            'ri-macbook-line',
            'bg-success-subtle',
            route('laptop.index')
        );
    }

    public function updated(Laptop $laptop)
    {
        AdminNotifier::send(
            'Laptop updated',
            sprintf('%s was updated.', $this->label($laptop)),
            'ri-edit-2-line',
            'bg-warning-subtle',
            route('laptop.index')
        );
    }

    public function deleted(Laptop $laptop)
    {
        AdminNotifier::send(
            'Laptop removed',
            sprintf('%s was removed from the fleet.', $this->label($laptop)),
            'ri-delete-bin-line',
            'bg-danger-subtle',
            route('laptop.index')
        );
    }
}
