<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Reunions\ReunionResource;
use App\Models\Reunion;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class CalendrierReunions extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'Reunions';

    protected static ?string $navigationLabel = 'Calendrier';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Calendrier des reunions';

    protected static ?string $slug = 'calendrier-reunions';

    protected string $view = 'filament.pages.calendrier-reunions';

    public ?int $year = null;

    public ?int $month = null;

    public ?int $selectedId = null;

    public ?string $selectedDay = null;

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return (bool) ($user && $user->can('viewAny', \App\Models\Reunion::class));
    }

    public function mount(): void
    {
        $this->year = (int) now()->format('Y');
        $this->month = (int) now()->format('n');
    }

    public function getTitle(): string|Htmlable
    {
        return 'Calendrier des reunions';
    }

    public function prevMonth(): void
    {
        $d = \Carbon\Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->year = (int) $d->format('Y');
        $this->month = (int) $d->format('n');
    }

    public function nextMonth(): void
    {
        $d = \Carbon\Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->year = (int) $d->format('Y');
        $this->month = (int) $d->format('n');
    }

    public function rows(): array
    {
        $start = \Carbon\Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $items = Reunion::query()->with(['commission'])
            ->whereBetween('date_debut', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->orderBy('date_debut')->limit(300)->get();
        $byDay = [];
        foreach ($items as $r) {
            if (! auth()->user()?->can('view', $r)) {
                continue;
            }
            $k = $r->date_debut ? $r->date_debut->format('Y-m-d') : $start->format('Y-m-d');
            $byDay[$k][] = $r;
        }

        return $byDay;
    }

    public function weeks(): array
    {
        $first = \Carbon\Carbon::create($this->year, $this->month, 1);
        $start = $first->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        $end = $first->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);
        $weeks = [];
        $cur = $start->copy();
        while ($cur->lte($end)) {
            $w = [];
            for ($i = 0; $i < 7; $i++) {
                $w[] = $cur->copy();
                $cur->addDay();
            }
            $weeks[] = $w;
        }

        return $weeks;
    }

    public function selDay(string $day): void
    {
        $this->selectedId = null;
        $this->selectedDay = $day;
    }

    public function selReunion(int $id): void
    {
        $r = Reunion::query()->find($id);
        if (! $r || ! auth()->user()?->can('view', $r)) {
            Notification::make()->danger('Acces refuse.')->send();

            return;
        }
        $this->selectedId = $id;
    }

    public function delReunion(int $id): void
    {
        $r = Reunion::query()->find($id);
        if (! $r || ! auth()->user()?->can('delete', $r)) {
            Notification::make()->danger('Suppression non autorisee.')->send();

            return;
        }
        $r->delete();
        $this->selectedId = null;
        Notification::make()->success('Reunion supprimee.')->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Creer une reunion')
                ->icon('heroicon-o-plus')
                ->url(fn () => ReunionResource::getUrl('create', ['jour' => $this->selectedDay]))
                ->visible(fn () => auth()->user()?->can('create', Reunion::class) ?? false),
        ];
    }
}
