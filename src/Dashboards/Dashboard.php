<?php

declare(strict_types=1);

namespace JPeters\Architect\Dashboards;

use JPeters\Architect\Dashboards\Cards\Card;
use JPeters\Architect\Dashboards\Cards\Chart;

class Dashboard extends AbstractDashboard
{
    public function cards(): array
    {
        return [
            Card::generate('Welcome')
                ->setContent('Welcome to your Architect Admin Panel'),

            Card::generate('Make your own!')
                ->setContent('Check the ArchitectServiceProvider class to add your own Dashboards')
                ->setWidth('half'),

            Card::generate('Alternate')
                ->setContent('Foo')
                ->setWidth('quarter'),

            Card::generate('Widths')
                ->setContent('Bar')
                ->setWidth('quarter'),

            Card::generate('Closure Cards')
                ->setContent(function () {
                    return 'You can also render the card content from a Closure that returns a string';
                }),

            Chart::generate('Charts')
                ->addLabels(['one', 'two', 'three'])
                ->addDataSet('foo', [1, 2, 3])
                ->addDataSet('bar', [3, 6, 9])
                ->addDataSet('baz', [2, 8, 16], 'line'),
        ];
    }
}
