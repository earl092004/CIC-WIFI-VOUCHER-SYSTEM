<?php

it('includes a real Omada service adapter ready for production credentials', function () {
    expect(class_exists(App\Services\RealOmadaService::class))->toBeTrue()
        ->and(method_exists(App\Services\RealOmadaService::class, 'createVoucher'))->toBeTrue()
        ->and(method_exists(App\Services\RealOmadaService::class, 'getVoucher'))->toBeTrue();
});
