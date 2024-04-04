<?php

declare(strict_types=1);

namespace Tests;

use App\Enums\PanelTypeEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['panel' => PanelTypeEnum::ADMIN,]);

        $this->actingAs($user);
    }
}
