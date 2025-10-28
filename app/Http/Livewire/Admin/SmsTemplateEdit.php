<?php

namespace App\Http\Livewire\Admin;

use App\Models\SmsTemplate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SmsTemplateEdit extends Component
{
    public $title = "Edit Template";

    public SmsTemplate $template;

    public $name;
    public $body;

    protected $rules = [
        "name" => "required",
        "body" => "required",
    ];

    public function mount()
    {
        $this->fill($this->template->only(['name', 'body']));
    }

    public function submit()
    {
        $data = $this->validate();

        $this->template->update($data);

        flash('Sms Template updated successfully');

        return redirect()->route('admin.sms-template');
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.admin.sms-template-edit')->extends('admin.layouts.master', ['title' => $this->title]);
    }
}
