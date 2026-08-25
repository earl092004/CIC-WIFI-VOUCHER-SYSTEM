<?php

use App\Services\RealOmadaService;

it('includes a real Omada service adapter ready for production credentials', function () {
    expect(class_exists(RealOmadaService::class))->toBeTrue()
        ->and(method_exists(RealOmadaService::class, 'createVoucher'))->toBeTrue()
        ->and(method_exists(RealOmadaService::class, 'getVoucher'))->toBeTrue();
});
