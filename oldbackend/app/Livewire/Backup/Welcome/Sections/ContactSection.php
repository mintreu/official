<?php


namespace App\Livewire\Backup\Welcome\Sections;

use App\Models\Form\ContactForm;
use Livewire\Component;

class ContactSection extends Component
{
    public $name;
    public $email;
    public $contact;
    public $subject;
    public $message;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'contact' => 'nullable|string|max:20',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string',
    ];

    public function submit()
    {
        $this->validate();

        ContactForm::create([
            'name' => $this->name,
            'email' => $this->email,
            'contact' => $this->contact,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        session()->flash('message', 'Your message has been sent successfully!');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.welcome.sections.contact-section');
    }
}

