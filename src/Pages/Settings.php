<?php

namespace Reworck\FilamentSettings\Pages;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Panel;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Reworck\FilamentSettings\FilamentSettings;
use Spatie\Valuestore\Valuestore;

class Settings extends Page implements HasForms
{
    use InteractsWithFormActions;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament-settings::pages.settings';

    public function getMaxContentWidth(): ?string
    {
        return config('filament-settings.maxContentWidth');
    }

    public function getTitle(): string | Htmlable
    {
        return __('filament-settings::filament-settings.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-settings::filament-settings.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament-settings::filament-settings.group');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('filament-panels::pages/auth/edit-profile.notifications.saved.title');
    }

    public static function getNavigationIcon(): ?string
    {
        return static::$navigationIcon
            ?? config('filament-settings.navigationIcon')
            ?? (Filament::hasTopNavigation() ? 'heroicon-m-home' : 'heroicon-o-home');
    }

    public static function routes(Panel $panel): void
    {
        $slug = static::getSlug();
        Route::get("/{$slug}", static::class)
            ->middleware(static::getRouteMiddleware($panel))
            ->withoutMiddleware(static::getWithoutRouteMiddleware($panel))
            ->name('settings');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(FilamentSettings::$fields)
            ->statePath('data')
            ->inlineLabel(config('filament-settings.inlineLabel'))
            ->operation('edit')
            ->columns(config('filament-settings.columns'));
    }

    public function mount(): void
    {
        $this->form->fill(
            Valuestore::make(config('filament-settings.path'))->all()
        );
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();
        foreach ($this->data as $key => $data) {
            Valuestore::make(config('filament-settings.path'))->put($key, $data);
        }
        $this->getSavedNotification()?->send();
    }

    protected function getSavedNotification(): ?Notification
    {
        $title = $this->getSavedNotificationTitle();
        return blank($title) ? null : Notification::make()->success()->title($title);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
            ->submit('submit')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('back')
            ->label(__('filament-panels::pages/auth/edit-profile.actions.cancel.label'))
            ->url(filament()->getUrl())
            ->color('gray');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return config('filament-settings.hasFullWidthFormActions');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->canManageSettings() ?? true;
    }
}
