<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\Page;

class RedirectToInvitation extends Page
{
    protected static ?string $navigationIcon = null;

    protected static string $resource = 'App\\Filament\\Resources\\InvitationResource';

    public function mount()
    {
        return redirect()->route('filament.admin.resources.invitations.create');
    }
}
