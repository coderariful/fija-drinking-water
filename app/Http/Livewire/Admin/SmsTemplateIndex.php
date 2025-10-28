<?php

namespace App\Http\Livewire\Admin;

use App\Models\SmsTemplate;
use Livewire\Component;

class SmsTemplateIndex extends Component
{
    public $title = "SMS Templates";

    public function render()
    {
        return view('livewire.admin.sms-template-index', [
            'templates' => $this->getTemplates(),
        ])->extends('admin.layouts.master', ['title' => $this->title]);
    }

    private function getTemplates()
    {
        return SmsTemplate::paginate(10);
    }
}
