<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class Sorting extends BaseComponent
{
    /**
     * Get the root selector for the component.
     */
    public function selector(): string
    {
        return '@sorting';
    }

    /**
     * Assert that the browser page contains the component.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return  array<string, string>
     */
    public function elements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }

    public function assertSorting(Browser $browser, string $column, string $ascending = 'true'): void
    {
        $browser->assertVisible("@$column");
        $browser
            ->click("@$column")
            ->waitFor($ascending == 'true' ? '@up_icon' : '@down_icon')
            ->assertQueryStringHas('column', $column)
            ->assertQueryStringHas('ascending', $ascending);
    }
}
